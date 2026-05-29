<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;

class TrackingController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Transaction::where(
            'user_id',
            auth()->id()
        );

        if ($status) {
            $query->where(
                'shipping_status',
                $status
            );
        }

        $transactions = $query
            ->latest()
            ->get();

        foreach ($transactions as $trx) {

            if (
                $trx->resi &&
                $trx->courier &&
                $trx->shipping_status !== 'selesai'
            ) {

                $response = Http::get(
                    'https://api.binderbyte.com/v1/track',
                    [
                        'api_key' => config('services.binderbyte.key'),
                        'courier' => strtolower(trim($trx->courier)),
                        'awb' => $trx->resi,
                        'number' => substr(
                            $trx->phone ?? '00000',
                            -5
                        )
                    ]
                );

                $data = $response->json();

                if (isset($data['data'])) {

                    $history = $data['data']['history'] ?? [];

                    $binderStatus = strtolower($data['data']['summary']['status'] ?? '');
                    $newStatus = 'dikemas';

                    if (str_contains($binderStatus, 'delivered')) {

                        $newStatus = 'selesai';
                    } elseif (
                        str_contains($binderStatus, 'on process') ||
                        str_contains($binderStatus, 'transit')
                    ) {

                        $newStatus = 'dikirim';
                    }

                    $trx->update([
                        'tracking_history' => $history,
                        'last_tracking' => $history[0] ?? null,
                        'shipping_status' => $newStatus
                    ]);

                    $trx->tracking_data = [
                        'history' => $history,
                        'last_history' => $history[0] ?? null
                    ];
                }
            } else {

                // pakai cache database
                $trx->tracking_data = [
                    'history' => $trx->tracking_history ?? [],
                    'last_history' => $trx->last_tracking
                ];
            }
        }

        return view(
            'buyer.order',
            compact(
                'transactions',
                'status'
            )
        );
    }
}
