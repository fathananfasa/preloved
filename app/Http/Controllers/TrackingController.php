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

        $query = Transaction::where('buyer_id', auth()->id());

        if ($status) {
            $query->where('shipping_status', $status);
        }

        $transactions = $query->latest()->get();

        // Tambahkan tracking BinderByte
        foreach ($transactions as $trx) {
            

    if ($trx->resi && $trx->courier) {

        $response = Http::get('https://api.binderbyte.com/v1/track', [
            'api_key' => config('services.binderbyte.key'),
            'courier' => $trx->courier,
            'awb' => $trx->resi,
            'number' => substr($trx->phone ?? '00000', -5) // ambil 5 digit terakhir
        ]);

        $data = $response->json();

        // WAJIB di dalam sini
        if (isset($data['data'])) {

          $history = $data['data']['history'] ?? [];

$trx->history = $history;
$trx->last_history = $history[0] ?? null;

            $binderStatus = strtolower($data['data']['summary']['status'] ?? '');

            if (str_contains($binderStatus, 'delivered')) {
                $trx->shipping_status = 'selesai';
            } elseif (str_contains($binderStatus, 'on process') || str_contains($binderStatus, 'transit')) {
                $trx->shipping_status = 'dikirim';
            } else {
                $trx->shipping_status = 'dikemas';
            }
        }

    } else {
        $trx->history = [];
    }
}

        return view('buyer.order', compact('transactions', 'status'));
    }

    public function track($transactionId)
    {

        $transaction = Transaction::findOrFail($transactionId);

        $response = Http::get('https://api.binderbyte.com/v1/track', [

            'api_key' => config('services.binderbyte.key'),
            'courier' => $transaction->courier,
            'awb' => $transaction->resi

        ]);

        $data = $response->json();

        return view('buyer.tracking', [
            'tracking' => $data['data']
        ]);
    }
}
