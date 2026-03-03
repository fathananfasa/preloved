<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Midtrans\Notification;
use Midtrans\Config;

class MidtransController extends Controller
{
    public function callback(Request $request)
{
    Config::$serverKey = config('midtrans.server_key');
    $notif = new \Midtrans\Notification();

    $transactionStatus = $notif->transaction_status;
    $orderId = $notif->order_id;

    $transaction = \App\Models\Transaction::where(
        'id',
        str_replace('TRX-', '', $orderId)
    )->first();

    if (!$transaction) {
        return response()->json(['message' => 'Transaction not found'], 404);
    }

    // Supaya tidak double update
    if ($transaction->status === 'paid') {
        return response()->json(['message' => 'Already processed']);
    }

    if (in_array($transactionStatus, ['settlement', 'capture'])) {

        $transaction->update([
            'status' => 'paid'
        ]);

        // 🔥 Kurangi stok produk
        if ($transaction->product_id) {
            $product = \App\Models\Product::find($transaction->product_id);

            if ($product) {
                $product->decrement('stock', 1);
            }
        }
    }

    elseif ($transactionStatus == 'expire') {
        $transaction->update(['status' => 'expired']);
    }

    elseif ($transactionStatus == 'pending') {
        $transaction->update(['status' => 'pending']);
    }

    return response()->json(['message' => 'OK']);
}
}