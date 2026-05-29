<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <div class="mb-6 sm:mb-8 pb-4 sm:pb-5 border-b border-stone-200">
            <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Hasil Pencarian</span>
            <h2 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900 mt-1">
                "{{ request('search') }}"
            </h2>
        </div>

        @if($products->count())
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-5">
            @foreach($products as $product)
            <a href="{{ route('products.show', $product->id) }}"
                class="group bg-white rounded-xl sm:rounded-2xl border border-stone-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col">

                <!-- Image -->
                <div class="relative bg-stone-50 aspect-square overflow-hidden flex items-center justify-center">
                    <img
                        src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('default.png') }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105">

                    <span class="absolute top-2 left-2 sm:top-2.5 sm:left-2.5 px-2 sm:px-2.5 py-0.5 sm:py-1 text-[9px] sm:text-[10px] font-semibold rounded-full tracking-wide uppercase backdrop-blur-sm
                        {{ $product->stock > 0 ? 'bg-emerald-100/90 text-emerald-700' : 'bg-red-100/90 text-red-600' }}">
                        {{ $product->stock > 0 ? 'Tersedia' : 'Habis' }}
                    </span>
                </div>

                <!-- Content -->
                <div class="p-2.5 sm:p-3 md:p-4 flex flex-col flex-grow">
                    <p class="text-[11px] sm:text-xs md:text-sm font-medium text-stone-800 mb-2 sm:mb-3 line-clamp-2 group-hover:text-amber-700 transition-colors duration-200 leading-snug">
                        {{ $product->name }}
                    </p>
                    <div class="w-5 h-px bg-stone-200 mb-2 sm:mb-3"></div>
                    <p class="font-serif text-sm sm:text-base font-bold text-amber-700 mt-auto">
                        Rp {{ number_format($product->price_original ?? $product->price, 0, ',', '.') }}
                    </p>
                </div>

            </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>

        @else

        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 rounded-2xl bg-stone-100 flex items-center justify-center mb-4 text-2xl">
                🔍
            </div>
            <p class="font-serif text-lg font-bold text-stone-700 mb-1">Produk tidak ditemukan</p>
            <p class="text-sm text-stone-400">Coba kata kunci lain atau lihat koleksi kami.</p>
            <a href="{{ route('home') }}"
                class="mt-5 inline-block bg-amber-400 hover:bg-amber-300 text-stone-900 px-6 py-2.5 rounded-xl text-sm font-semibold transition duration-200 shadow hover:shadow-md">
                Lihat Semua Produk
            </a>
        </div>

        @endif

    </div>
</x-app-layout>