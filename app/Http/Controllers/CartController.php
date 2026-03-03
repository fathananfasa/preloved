<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;


use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Product $product)
    {
        $cart = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            $cart->increment('quantity');
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => 1
            ]);
        }

        return back()->with('success', 'Ditambahkan ke keranjang');
    }

    public function index()
    {
        $carts = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        return view('buyer.cart', compact('carts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $cart->quantity = $request->quantity;
        $cart->save();

        return response()->json([
            'success' => true
        ]);
    }


    public function delete(Cart $cart)
    {
        $cart->delete();
        return back();
    }
}
