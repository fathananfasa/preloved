<x-app-layout>

<section class="max-w-7xl mx-auto px-6 py-12">

    <h1 class="font-serif text-4xl font-bold">
        {{ $category->name }}
    </h1>

    <p class="text-stone-500 mt-2">
        Semua produk kategori {{ $category->name }}
    </p>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mt-8">

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
                    <p class="font-serif text-sm sm:text-base md:text-lg font-bold text-amber-700 mt-auto">
                        Rp {{ number_format($product->price_original ?? $product->price, 0, ',', '.') }}
                    </p>
                </div>
            </a>
        @endforeach

    </div>

    <div class="mt-10">
        {{ $products->links() }}
    </div>

</section>

</x-app-layout>