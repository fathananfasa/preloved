<x-app-layout>

    <!-- HERO -->
    <section class="relative bg-cover bg-center text-white min-h-[340px] md:min-h-[420px] flex items-center"
        style="background-image: url('{{ asset('storage/kebutuhan/bg.png') }}');">

        <div class="absolute inset-0 bg-gradient-to-br from-black/80 via-black/60 to-black/75"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-14 sm:py-20 text-center w-full">
            <span class="inline-block text-[10px] sm:text-[11px] font-medium tracking-[0.2em] uppercase text-white/50 border border-white/20 px-3 sm:px-4 py-1.5 rounded-full mb-4 sm:mb-5">
                Barang Bekas Pilihan
            </span>
            <div class="mb-6 flex justify-center">
    <img
        src="{{ asset('storage/kebutuhan/logo.png') }}"
        alt="Bekas Dicintai"
        class="w-full max-w-[450px] md:max-w-[550px] h-auto">
</div>
            <p class="text-xs sm:text-sm md:text-base text-white/60 mb-6 sm:mb-8 font-light max-w-sm sm:max-w-none mx-auto">
                Temukan barang preloved berkualitas dengan harga terbaik.
            </p>
            <div class="flex justify-center gap-3">

                <a href="#produk"
                    class="inline-block bg-amber-300 text-stone-900 px-6 sm:px-7 py-2.5 rounded-full font-semibold text-xs sm:text-sm">

                    Lihat Produk

                </a>

                <button
                    onclick="openModal()"
                    class="inline-block border border-white/30 bg-white/10 backdrop-blur px-6 sm:px-7 py-2.5 rounded-full font-semibold text-xs sm:text-sm hover:bg-white hover:text-stone-900">

                    Beri Komentar

                </button>

            </div>

        </div>
    </section>

    <!-- PRODUK -->
    <section id="produk" class="max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-16">

        <div class="flex items-end justify-between mb-6 sm:mb-8 pb-4 sm:pb-5 border-b border-stone-200">
            <h2 class="font-serif text-2xl sm:text-3xl md:text-4xl font-bold text-stone-900 leading-tight">
                Produk
                <span class="block italic text-amber-700/80 text-[0.9em]">Terbaru</span>
            </h2>
            <span class="hidden sm:block text-[10px] sm:text-[11px] uppercase tracking-widest text-stone-400 font-medium">Koleksi</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 sm:gap-4 md:gap-5">

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
    </section>

    @include('modals.testimonials')
    @include('partials.testimonials')
</x-app-layout>