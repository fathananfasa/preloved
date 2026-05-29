<?php

namespace App\Http\Controllers;

use App\Ai\Agents\NegotiationAgent;
use App\Models\Negotiation;
use App\Models\Product;
use Illuminate\Http\Request;

class NegotiationController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'offer_price' => 'required|numeric|min:1'
        ]);

        $offer = (int) $request->offer_price;

        $minimum = (int) $product->bottom_price;

        // =========================
        // AMBIL / BUAT NEGOTIATION
        // =========================
        $negotiation = Negotiation::firstOrNew([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        // JIKA BARU
        if (!$negotiation->exists) {

            $negotiation->attempt_count = 0;
            $negotiation->status = 'pending';
            $negotiation->is_blocked = false;
        }

        // =========================
        // CEK BLOCK
        // =========================
        if ($negotiation->is_blocked) {

            return response()->json([
                'blocked' => true,
                'message' => 'Fitur negosiasi telah diblokir'
            ]);
        }

        // =========================
        // HITUNG LOWBALL
        // =========================
        if ($offer < $minimum) {

            $negotiation->attempt_count += 1;
        }

        // =========================
        // BLOK JIKA 3X LOWBALL
        // =========================
        if ($negotiation->attempt_count >= 3) {

            $negotiation->is_blocked = true;
            $negotiation->status = 'rejected';

            $negotiation->save();

            return response()->json([
                'blocked' => true,
                'message' => 'Negosiasi diblokir karena terlalu banyak menawar di bawah harga minimum'
            ]);
        }

        // =========================
        // AI NEGOTIATION
        // =========================
        $response = NegotiationAgent::make()->prompt("
            Nama produk: {$product->name}

            Harga asli: {$product->price_original}

            Harga minimum: {$minimum}

            Tawaran pembeli: {$offer}
        ");

        // =========================
        // PARSE JSON AI
        // =========================
        $responseText = (string) $response;

        // hapus markdown kalau ada
        $responseText = str_replace([
            '```json',
            '```'
        ], '', $responseText);

        $responseText = trim($responseText);

        $response = json_decode($responseText, true);
        // fallback jika AI error
        if (!$response) {

            $response = [
                'status' => 'counter',
                'message' => 'Saya bisa bantu sedikit lebih tinggi.',
                'counter_price' => $minimum + 50000,
            ];
        }

        // =========================
        // SIMPAN NEGOTIATION
        // =========================
        $negotiation->offer_price = $offer;

        $negotiation->counter_price =
            $response['counter_price'] ?? null;

        $negotiation->ai_message =
            $response['message'] ?? null;

        $negotiation->save();

        // =========================
        // RESPONSE JSON
        // =========================
        return response()->json([
            'success' => true,
            'negotiation_id' => $negotiation->id,
            'data' => [
                'status' =>
                $response['status'] ?? 'counter',

                'message' =>
                $response['message'] ?? null,

                'counter_price' =>
                $response['counter_price'] ?? null,
            ],

            'attempt_count' =>
            $negotiation->attempt_count
        ]);
    }

    public function update(
        Request $request,
        Negotiation $negotiation
    ) {

        abort_if(
            $negotiation->user_id !== auth()->id(),
            403
        );

        abort_if(
            $negotiation->status !== 'pending',
            403
        );

        abort_if(
            $negotiation->is_blocked,
            403,
            'Negosiasi diblokir'
        );

        $request->validate([
            'offer_price' => 'required|numeric|min:1'
        ]);

        $offer = (int) $request->offer_price;

        $minimum = (int) $negotiation
            ->product
            ->bottom_price;

        // =========================
        // LOWBALL COUNT
        // =========================
        if ($offer < $minimum) {

            $negotiation->attempt_count += 1;
        }

        // =========================
        // BLOCK 3X
        // =========================
        if ($negotiation->attempt_count >= 3) {

            $negotiation->update([
                'is_blocked' => true,
                'status' => 'rejected'
            ]);

            return response()->json([
                'blocked' => true,
                'message' =>
                'Negosiasi diblokir karena terlalu banyak lowball'
            ]);
        }

        // =========================
        // AI RESPONSE
        // =========================
        $response = NegotiationAgent::make()->prompt("
            Nama produk: {$negotiation->product->name}

            Harga minimum: {$minimum}

            Tawaran terbaru pembeli: {$offer}
        ");

        $response = json_decode((string) $response, true);

        // fallback
        if (!$response) {

            $response = [
                'status' => 'counter',
                'message' =>
                'Saya bisa bantu sedikit lebih tinggi.',
                'counter_price' => $minimum + 50000,
            ];
        }

        // =========================
        // UPDATE DATA
        // =========================
        $negotiation->update([

            'offer_price' => $offer,

            'counter_price' =>
            $response['counter_price'] ?? null,

            'ai_message' =>
            $response['message'] ?? null,
        ]);

        return response()->json([
            'success' => true,

            'data' => [
                'status' =>
                $response['status'] ?? 'counter',

                'message' =>
                $response['message'] ?? null,

                'counter_price' =>
                $response['counter_price'] ?? null,
            ],

            'attempt_count' =>
            $negotiation->attempt_count
        ]);
    }

    public function acceptAi(Negotiation $negotiation)
    {
        abort_if(
            $negotiation->user_id !== auth()->id(),
            403
        );

        $negotiation->update([
            'final_price' => $negotiation->counter_price
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}
