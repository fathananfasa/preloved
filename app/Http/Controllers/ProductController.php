<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Transaction;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with([
            'images',
            'category'
        ]);

        // Guest dan buyer hanya lihat produk available
        if (
            !auth()->check() ||
            auth()->user()->role === 'buyer'
        ) {
            $query->where(
                'status',
                'available'
            );
        }

        // Filter kategori
        if ($request->filled('category_id')) {

            $query->where(
                'category_id',
                $request->category_id
            );
        }

        // Search
        if ($request->filled('search')) {

            $query->where(
                'name',
                'like',
                '%' . $request->search . '%'
            );
        }

        $products = $query
            ->latest()
            ->paginate(8)
            ->withQueryString();

        $categories = Category::orderBy(
            'name'
        )->get();

        // Ambil testimonial terbaru
        $testimonials = Testimonial::with(
            'user'
        )
            ->latest()
            ->take(6)
            ->get();

        // ======================
        // GUEST
        // ======================

        if (!auth()->check()) {

            return view(
                'welcome',
                compact(
                    'products',
                    'categories',
                    'testimonials'
                )
            );
        }

        // ======================
        // ADMIN
        // ======================

        if (
            auth()->user()->role === 'admin'
        ) {

            return view(
                'admin.products.index',
                compact(
                    'products',
                    'categories'
                )
            );
        }

        // ======================
        // BUYER
        // ======================

        $hasPurchased = Transaction::where(
            'user_id',
            auth()->id()
        )->exists();

        $alreadyCommented = Testimonial::where(
            'user_id',
            auth()->id()
        )->exists();

        return view(
            'buyer.home',
            compact(
                'products',
                'categories',
                'hasPurchased',
                'alreadyCommented',
                'testimonials'
            )
        );
    }

    public function create()
    {
        $categories = Category::all();

        return view(
            'admin.products.index',
            compact('categories')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'category_new' => 'nullable|string|max:255',
            'price_original' => 'required|integer',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // kategori baru
        if ($request->category_id === 'new') {

            $category = Category::firstOrCreate([
                'name' => $request->category_new
            ]);

            $categoryId = $category->id;

        } else {

            $categoryId = $request->category_id;
        }

        $product = Product::create([
            'name' => $request->name,
            'price_original' => $request->price_original,
            'bottom_price' => $request->bottom_price,
            'stock' => $request->stock,
            'weight' => $request->weight,
            'category_id' => $categoryId,
            'description' => $request->description,
        ]);

        // Upload gambar
        if ($request->hasFile('images')) {

            foreach (
                $request->file('images')
                as $image
            ) {

                $path = $image->store(
                    'products',
                    'public'
                );

                $product->images()->create([
                    'image_path' => $path
                ]);
            }
        }

        return redirect()
            ->route(
                'admin.products.index'
            )
            ->with(
                'success',
                'Produk berhasil ditambahkan'
            );
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view(
            'admin.products.edit',
            compact(
                'product',
                'categories'
            )
        );
    }

    public function update(
        Request $request,
        Product $product
    ) {

        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price_original' => 'required|numeric',
            'bottom_price' => 'required|numeric',
            'status' => 'required|in:available,waiting_payment,sold',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // upload gambar tambahan
        if ($request->hasFile('images')) {

            foreach (
                $request->file('images')
                as $image
            ) {

                $path = $image->store(
                    'products',
                    'public'
                );

                $product->images()->create([
                    'image_path' => $path
                ]);
            }
        }

        $product->update(
            $validated
        );

        return redirect()
            ->route(
                'admin.products.index'
            )
            ->with(
                'success',
                'Produk berhasil diupdate'
            );
    }

    public function destroy(
        Product $product
    ) {

        foreach (
            $product->images
            as $image
        ) {

            Storage::disk(
                'public'
            )->delete(
                $image->image_path
            );

            $image->delete();
        }

        $product->delete();

        return back();
    }

    public function storeImage(
        Request $request,
        Product $product
    ) {

        $request->validate([
            'image' =>
            'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request
            ->file('image')
            ->store(
                'products',
                'public'
            );

        $product->images()->create([
            'image_path' => $path
        ]);

        return back()
            ->with(
                'success',
                'Gambar berhasil ditambahkan'
            );
    }

    public function deleteImage(
        ProductImage $image
    ) {

        Storage::disk(
            'public'
        )->delete(
            $image->image_path
        );

        $image->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function storeCategory(
        Request $request
    ) {

        $request->validate([
            'name' =>
            'required|string|max:255',
            'description' =>
            'nullable|string'
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return back()
            ->with(
                'success',
                'Kategori berhasil ditambahkan'
            );
    }
}