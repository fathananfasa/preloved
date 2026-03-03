<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class BuyerController extends Controller
{
        public function index(Request $request)
    {
        $query = Product::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(8)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('buyer.search', compact('products', 'categories'));
    }
}
