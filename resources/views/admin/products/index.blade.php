<x-app-layout>

    <div class="p-4 md:p-6">

        <!-- Filter & Search -->
        <div class="mb-4 flex flex-col md:flex-row md:items-center md:gap-4 gap-2">
            <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-2 w-full md:w-auto">

                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama produk..."
                    class="border rounded px-3 py-2 text-sm w-full sm:w-60 focus:ring-1 focus:ring-blue-400 focus:outline-none">

                <select name="category_id"
                    class="border rounded px-3 py-2 text-sm w-full sm:w-48 focus:ring-1 focus:ring-blue-400 focus:outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700 transition w-full sm:w-auto">
                    Cari
                </button>

                <a href="{{ route('admin.products.index') }}"
                    class="px-4 py-2 bg-red-500 text-white rounded text-sm hover:bg-red-300 transition w-full sm:w-auto text-center">
                    Reset
                </a>

            </form>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Harga</th>
                        <th class="px-4 py-3 text-left">Stok</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                        <th class="px-4 py-3 text-left">Gambar</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $product->name }}
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            Rp {{ number_format($product->price_original) }}
                        </td>

                        <td class="px-4 py-3 text-gray-700">
                            {{ $product->stock }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $product->status === 'active'
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-gray-200 text-gray-700' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700">
                            {{ $product->category->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 flex gap-2 flex-wrap">
                            <a href="{{ route('admin.products.edit', $product) }}"
                                class="text-blue-600 hover:text-blue-800 font-medium transition">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-800 font-medium transition">
                                    Hapus
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2 flex-wrap">
                                @forelse ($product->images as $image)
                                <div class="w-12 h-12 rounded overflow-hidden border bg-gray-100 flex-shrink-0">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                        class="w-full h-full object-cover">
                                </div>
                                @empty
                                <span class="text-gray-400 text-xs">Tidak ada</span>
                                @endforelse
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Modal Form Tambah Kategori -->
    <div id="addCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-1/3 p-6 relative">
            <h3 class="text-lg font-semibold mb-4">Tambah Kategori</h3>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Nama Kategori</label>
                    <input type="text" name="name" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent"></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button"
                        onclick="document.getElementById('addCategoryModal').classList.add('hidden')"
                        class="px-4 py-2 border rounded hover:bg-gray-100 transition">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    </div>
</x-app-layout>

<script>
    // Tutup modal saat klik di luar modal
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('addCategoryModal');
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
</script>