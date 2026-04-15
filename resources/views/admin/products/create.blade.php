<x-app-layout>

    <div class="p-4 md:p-6 max-w-xl mx-auto">
        <form action="{{ route('admin.products.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6 bg-white p-6 rounded-lg shadow-md">

            @csrf

            <!-- Nama Produk -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Nama Produk</label>
                <input type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                    required>
            </div>

            <!-- Harga -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Harga</label>
                <input type="number"
                    name="price_original"
                    value="{{ old('price_original') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                    required>
            </div>
            <!-- Stok -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Stok</label>
                <input type="number"
                    name="stock"
                    value="{{ old('stock') }}"
                    min="0"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                    required>
            </div>

            <!-- Weight -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Berat Produk (gram)</label>
                <input type="number"
                    name="weight"
                    value="{{ old('weight') }}"
                    min="1"
                    placeholder="Contoh: 500"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                    required>
                <p class="text-sm text-gray-500 mt-1">Masukkan berat dalam gram. Contoh: 500 = 0.5 kg</p>
            </div>

            <!-- Kategori -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Kategori</label>
                <select id="categorySelect" name="category_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                    <option value="new">+ Buat kategori baru</option>
                </select>

                <input type="text" name="category_new" id="categoryNewInput"
                    placeholder="Masukkan kategori baru"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-2 hidden">
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                    rows="4">{{ old('description') }}</textarea>
            </div>

            <!-- Foto Produk -->
            <div>
                <label class="block font-medium text-gray-700 mb-1">Foto Produk</label>
                <input type="file"
                    name="images[]"
                    multiple
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                <p class="text-sm text-gray-500 mt-1">Bisa upload lebih dari satu foto</p>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-col sm:flex-row gap-3 mt-4">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                    Simpan
                </button>

                <a href="{{ route('admin.products.index') }}"
                    class="px-6 py-2 border bg-red-600 text-white border-gray-300 rounded-lg hover:bg-gray-100 text-center transition font-medium">
                    Batal
                </a>
            </div>

        </form>
    </div>
</x-app-layout>

<script>
    const select = document.getElementById('categorySelect');
    const input = document.getElementById('categoryNewInput');

    select.addEventListener('change', function() {
        if (this.value === 'new') {
            input.classList.remove('hidden');
            input.required = true;
        } else {
            input.classList.add('hidden');
            input.required = false;
        }
    });
</script>