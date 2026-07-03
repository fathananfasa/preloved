<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Negotiation;

class ProductPublicController extends Controller
{
    public function show(Product $product)
{
    $myNegotiation = null;

    if (auth()->check() && auth()->user()->role === 'buyer') {
        $myNegotiation = Negotiation::where('product_id', $product->id)
    ->where('user_id', auth()->id())
    ->whereNotNull('status')
    ->latest()
    ->first();
    }

    return view('products.show', compact('product', 'myNegotiation'));
}
}
