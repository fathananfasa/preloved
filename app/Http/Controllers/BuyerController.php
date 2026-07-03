<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Http;


class BuyerController extends Controller
{
        public function index(Request $request)
{
    $categories = Category::orderBy('name')->get();

    if ($request->search) {

    $response = Http::post('http://127.0.0.1:5000/search', [
        'query' => $request->search
    ]);

    if (!$response->successful()) {
        $flaskResults = [];
    } else {
        $flaskResults = $response->json(); // sekarang [{id, name, score}]
    }

$flaskResults = $response->json('results') ?? [];

    // Pisahkan ids dan scores
    $ids = collect($flaskResults)->pluck('id')->toArray();
    $scores = collect($flaskResults)->pluck('score', 'id'); // [id => score]

    if (count($ids) > 0) {
        $products = Product::with('images')
    ->whereIn('id', $ids)
    ->orderByRaw("FIELD(id," . implode(',', $ids) . ")")
    ->paginate(8)
    ->withQueryString();
    } else {
        $products = Product::whereRaw('1 = 0')->paginate(8);
        $scores = collect();
    }

} else {
    $products = Product::latest()->paginate(8);
    $scores = collect();
}

return view('buyer.search', compact('products', 'categories', 'scores'));
}}