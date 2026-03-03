<x-app-layout>

    <div class="max-w-xl p-6">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
            enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label>Nama Produk</label>
                <input type="text" name="name"
                    value="{{ old('name', $product->name) }}"
                    class="w-full border rounded p-2">
            </div>

            <div>
                <label>Deskripsi</label>
                <textarea name="description" class="w-full border rounded p-2">{{ old('description', $product->description) }}</textarea>
            </div>

            <div>
                <label>Harga</label>
                <input type="number" name="price_original"
                    value="{{ old('price_original', $product->price_original) }}"
                    class="w-full border rounded p-2">
            </div>

            <div>
                <label>Kategori</label>
                <select name="category_id" class="w-full border rounded p-2">
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Status</label>
                <select name="status" class="w-full border rounded p-2">
                    <option value="available" {{ $product->status === 'available' ? 'selected' : '' }}>
                        Tersedia
                    </option>

                    <option value="waiting_payment" {{ $product->status === 'waiting_payment' ? 'selected' : '' }}>
                        Menunggu Pembayaran
                    </option>

                    <option value="sold" {{ $product->status === 'sold' ? 'selected' : '' }}>
                        Terjual
                    </option>
                </select>
            </div>

            <div>
                <label class="font-semibold">Gambar Produk</label>

                <div class="grid grid-cols-3 gap-3 mt-2">
                    @foreach ($product->images as $image)
                    <div class="relative border rounded p-1">
                        <img src="{{ asset('storage/' . $image->image_path) }}" class="rounded">

                        <button type="button"
                            onclick="deleteImage({{ $image->id }})"
                            class="absolute top-1 right-1 bg-red-600 text-white text-xs px-2 py-1 rounded">
                            Hapus
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>


            <div>
                <label>Tambah Gambar Baru</label>
                <input type="file"
                    name="images[]"
                    multiple
                    class="w-full border rounded p-2">
            </div>


            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Update Produk
            </button>
        </form>
    </div>
</x-app-layout>

<script>
    function deleteImage(id) {
        if (!confirm('Hapus gambar ini?')) return;

        fetch(`/admin/product-images/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error('Gagal');
                location.reload();
            })
            .catch(err => alert(err.message));
    }
</script>