<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Midtrans\Notification;
use Midtrans\Config;
use Illuminate\Support\Facades\DB;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = false;

        try {

            \Log::info('CALLBACK MASUK');
            \Log::info($request->all());

            $notif = new Notification();

            $transactionStatus =
                $notif->transaction_status;

            $orderId =
                $notif->order_id;

            $id =
                explode('-', $orderId)[1];

            $transaction =
                Transaction::with(
                    'items.product'
                )->find($id);

            if (!$transaction) {

                return response()->json([
                    'message' => 'Transaction not found'
                ],404);
            }

            /*
            |--------------------------------------------------------------------------
            | Jangan proses dua kali
            |--------------------------------------------------------------------------
            */

            if (
                $transaction->status === 'paid'
            ) {

                return response()->json([
                    'message' => 'Already processed'
                ]);
            }

            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | STATUS MAPPING
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    $transactionStatus,
                    ['settlement','capture']
                )
            ) {

                $transaction->update([
                    'status' => 'paid'
                ]);

                /*
                |--------------------------------------------------------------------------
                | KURANGI STOK
                |--------------------------------------------------------------------------
                */

                foreach (
                    $transaction->items
                    as $item
                ) {

                    $product =
                        $item->product;

                    if (!$product) {
                        continue;
                    }

                    $newStock =
                        max(
                            $product->stock
                            - $item->qty,
                            0
                        );

                    $product->update([

                        'stock' =>
                        $newStock,

                        'status' =>
                        $newStock <= 0
                        ? 'sold'
                        : 'available'

                    ]);
                }

            }

            elseif (
                $transactionStatus == 'pending'
            ) {

                $transaction->update([
                    'status' =>
                    'waiting_payment'
                ]);

            }

            elseif (
                $transactionStatus == 'expire'
            ) {

                $transaction->update([
                    'status' =>
                    'expired'
                ]);

            }

            elseif (
                in_array(
                    $transactionStatus,
                    ['cancel','deny']
                )
            ) {

                $transaction->update([
                    'status' =>
                    'failed'
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'OK'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error(
                $e->getMessage()
            );

            return response()->json([
                'message' => 'Error',
                'error' => $e->getMessage()
            ],500);
        }
    }
}