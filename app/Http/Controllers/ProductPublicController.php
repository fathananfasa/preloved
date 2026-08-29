<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Negotiation;
use App\Models\Category;

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

public function category(Category $category)
{
    $products = Product::with(['images'])
        ->where('status', 'available')
        ->where('category_id', $category->id)
        ->latest()
        ->paginate(12);

    return view('categories.show', compact(
        'category',
        'products'
    ));
}
}
