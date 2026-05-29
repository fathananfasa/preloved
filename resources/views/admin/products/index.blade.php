<x-app-layout>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-6">

        <!-- Header -->
        <div class="flex items-end justify-between pb-5 border-b border-stone-200">
            <div>
                <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Admin</span>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900 mt-1">Produk</h1>
            </div>
            <button
                onclick="openProductModal()"
                class="bg-stone-900 hover:bg-stone-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold tracking-wide transition duration-200 shadow hover:shadow-md">
                + Tambah Produk
            </button>
        </div>

        <!-- Filter & Search -->
        <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-3">

            <div class="relative flex-1 sm:max-w-xs">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-stone-400 pointer-events-none"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama produk..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
            </div>

            <select
                name="category_id"
                class="sm:w-48 px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>

            <button
                class="px-5 py-2.5 bg-stone-900 hover:bg-stone-700 text-white rounded-xl text-sm font-semibold transition duration-200 shadow hover:shadow-md">
                Cari
            </button>

            <a href="{{ route('admin.products.index') }}"
                class="px-5 py-2.5 border bg-red-500 border-stone-200 hover:border-stone-400 text-black hover:text-stone-900 rounded-xl text-sm font-semibold transition duration-200 text-center">
                Reset
            </a>

        </form>

        <!-- Table -->
        <div class="bg-white rounded-2xl border border-stone-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-stone-100 text-sm">

                    <thead>
                        <tr class="bg-stone-50">
                            <th class="px-4 py-3.5 text-left text-[10px] uppercase tracking-widest text-stone-400 font-medium">Nama</th>
                            <th class="px-4 py-3.5 text-left text-[10px] uppercase tracking-widest text-stone-400 font-medium">Harga</th>
                            <th class="px-4 py-3.5 text-left text-[10px] uppercase tracking-widest text-stone-400 font-medium">Stok</th>
                            <th class="px-4 py-3.5 text-left text-[10px] uppercase tracking-widest text-stone-400 font-medium">Status</th>
                            <th class="px-4 py-3.5 text-left text-[10px] uppercase tracking-widest text-stone-400 font-medium">Kategori</th>
                            <th class="px-4 py-3.5 text-left text-[10px] uppercase tracking-widest text-stone-400 font-medium">Gambar</th>
                            <th class="px-4 py-3.5 text-left text-[10px] uppercase tracking-widest text-stone-400 font-medium">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-stone-50">
                        @foreach ($products as $product)
                        <tr class="hover:bg-stone-50 transition duration-150">

                            <td class="px-4 py-3.5 font-medium text-stone-900">
                                {{ $product->name }}
                            </td>

                            <td class="px-4 py-3.5 font-serif font-bold text-amber-700">
                                Rp {{ number_format($product->price_original) }}
                            </td>

                            <td class="px-4 py-3.5 text-stone-600">
                                {{ $product->stock }}
                            </td>

                            <td class="px-4 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wide
                                    {{ $product->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-100 text-stone-500' }}">
                                    {{ ucfirst($product->status) }}
                                </span>
                            </td>

                            <td class="px-4 py-3.5 text-stone-500 text-xs">
                                {{ $product->category->name ?? '-' }}
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex gap-1.5 flex-wrap">
                                    @forelse ($product->images as $image)
                                    <img
                                        src="{{ asset('storage/'.$image->image_path) }}"
                                        class="w-10 h-10 object-cover rounded-lg border border-stone-100">
                                    @empty
                                    <span class="text-stone-300 text-xs">—</span>
                                    @endforelse
                                </div>
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <button
                                        onclick="openEditModal({{ $product }})"
                                        class="text-xs font-semibold text-stone-600 hover:text-amber-700 border border-stone-200 hover:border-amber-400 px-3 py-1.5 rounded-xl transition duration-200">
                                        Edit
                                    </button>
                                    <form
                                        action="{{ route('admin.products.destroy', $product) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin hapus produk?')">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            class="text-xs font-semibold text-red-400 hover:text-red-600 border border-stone-200 hover:border-red-300 px-3 py-1.5 rounded-xl transition duration-200">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

        <div>
            {{ $products->links() }}
        </div>

    </div>

    @include('modals.product')

</x-app-layout>