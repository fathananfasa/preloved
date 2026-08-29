<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Transaction;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
            ->paginate(
                auth()->check() && auth()->user()->role === 'admin'
                    ? 10
                    : 4
            )
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
        'home',
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

            // dipakai modal edit untuk repopulate gambar lama
            // kalau validasi update gagal & halaman reload
            $oldEditProduct = old('product_id')
                ? Product::with('images')->find(old('product_id'))
                : null;

            return view(
                'admin.products.index',
                compact(
                    'products',
                    'categories',
                    'oldEditProduct'
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
            'home',
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required',
            'category_new' => 'nullable|string|max:255',
            'price_original' => 'required|integer|min:0',
            'bottom_price' => 'nullable|integer|min:0|lte:price_original',
            'stock' => 'required|integer|min:0',
            'weight' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'name.max' => 'Nama produk maksimal 255 karakter.',

            'category_id.required' => 'Kategori wajib dipilih.',

            'price_original.required' => 'Harga wajib diisi.',
            'price_original.integer' => 'Harga harus berupa angka bulat.',
            'price_original.min' => 'Harga tidak boleh kurang dari 0.',

            'bottom_price.integer' => 'Harga minimal harus berupa angka bulat.',
            'bottom_price.min' => 'Harga minimal tidak boleh kurang dari 0.',
            'bottom_price.lte' => 'Harga minimal tidak boleh lebih besar dari harga asli.',

            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka bulat.',
            'stock.min' => 'Stok tidak boleh kurang dari 0.',

            'weight.integer' => 'Berat harus berupa angka bulat.',
            'weight.min' => 'Berat tidak boleh kurang dari 0.',

            'images.max' => 'Maksimal 5 gambar yang bisa diupload.',
            'images.*.image' => 'File yang diupload harus berupa gambar.',
            'images.*.mimes' => 'Gambar harus berformat jpg, jpeg, png, atau webp.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'store')
                ->withInput()
                ->with('error', 'Produk gagal ditambahkan, cek isian ya');
        }

        $validated = $validator->validated();

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
            'name' => $validated['name'],
            'price_original' => $validated['price_original'],
            'bottom_price' => $validated['bottom_price'] ?? null,
            'stock' => $validated['stock'],
            'weight' => $validated['weight'] ?? null,
            'category_id' => $categoryId,
            'description' => $validated['description'] ?? null,
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
        try {

            Http::timeout(10)
                ->post('http://127.0.0.1:5000/reload');
        } catch (\Exception $e) {

            Log::error(
                'Gagal reload CBF: ' .
                    $e->getMessage()
            );
        }
        return redirect()
            ->route(
                'admin.products.index'
            )
            ->with('notify', ['message' => 'Produk berhasil ditambahkan', 'type' => 'success']);
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

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price_original' => 'required|integer|min:0',
            'bottom_price' => 'nullable|integer|min:0|lte:price_original',
            'stock' => 'required|integer|min:0',
            'weight' => 'nullable|integer|min:0',
            'status' => 'required|in:available,waiting_payment,sold',
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'name.max' => 'Nama produk maksimal 255 karakter.',

            'price_original.required' => 'Harga wajib diisi.',
            'price_original.integer' => 'Harga harus berupa angka bulat.',
            'price_original.min' => 'Harga tidak boleh kurang dari 0.',

            'bottom_price.integer' => 'Harga minimal harus berupa angka bulat.',
            'bottom_price.min' => 'Harga minimal tidak boleh kurang dari 0.',
            'bottom_price.lte' => 'Harga minimal tidak boleh lebih besar dari harga asli.',

            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa angka bulat.',
            'stock.min' => 'Stok tidak boleh kurang dari 0.',

            'weight.integer' => 'Berat harus berupa angka bulat.',
            'weight.min' => 'Berat tidak boleh kurang dari 0.',

            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',

            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak ditemukan.',

            'images.max' => 'Maksimal 5 gambar yang bisa diupload.',
            'images.*.image' => 'File yang diupload harus berupa gambar.',
            'images.*.mimes' => 'Gambar harus berformat jpg, jpeg, png, atau webp.',
            'images.*.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'update')
                ->withInput(array_merge($request->all(), ['product_id' => $product->id]))
                ->with('error', 'Produk gagal diupdate, cek isian ya');
        }

        $validated = $validator->validated();

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
        try {

            Http::timeout(10)
                ->post('http://127.0.0.1:5000/reload');
        } catch (\Exception $e) {

            Log::error(
                'Gagal reload CBF: ' .
                    $e->getMessage()
            );
        }

        return redirect()
            ->route(
                'admin.products.index'
            )
            ->with('notify', [
        'message' => 'Produk berhasil diupdate',
        'type' => 'success'
    ]);
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $product->delete();

        try {
            $response = Http::timeout(30)->post('http://127.0.0.1:5000/reload');

            if ($response->successful()) {
                Log::info('Reload CBF sukses: ' . $response->body());
            } else {
                Log::warning('Reload CBF gagal, status: ' . $response->status() . ' - ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Gagal reload CBF: ' . $e->getMessage());
        }

        return redirect()->route('admin.products.index')
            ->with('notify', ['message' => 'Produk berhasil dihapus', 'type' => 'danger']);
    }
    public function storeImage(
        Request $request,
        Product $product
    ) {

        $request->validate([
            'image' =>
            'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'image.required' => 'Gambar wajib diisi.',
            'image.image' => 'File yang diupload harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat jpg, jpeg, png, atau webp.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
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
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 255 karakter.',
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
