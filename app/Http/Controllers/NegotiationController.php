<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Negotiation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NegotiationController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'offer_price' => 'required|numeric|min:1'
        ]);

        Negotiation::create([
            'product_id' => $product->id,
            'buyer_id' => auth()->id(),
            'offer_price' => $request->offer_price,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Harga negosiasi berhasil dikirim');
    }

    public function update(Request $request, Negotiation $negotiation)
    {
        abort_if($negotiation->buyer_id !== auth()->id(), 403);

        // ❌ tidak boleh edit jika sudah diproses admin
        abort_if($negotiation->status !== 'pending', 403);

        $request->validate([
            'offer_price' => 'required|numeric|min:1'
        ]);

        $negotiation->update([
            'offer_price' => $request->offer_price
        ]);

        return back()->with('success', 'Harga negosiasi berhasil diubah');
    }
}
