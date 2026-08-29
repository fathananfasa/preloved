<x-app-layout>

    <!-- HERO -->
    <!-- HERO -->
<section class="relative overflow-hidden bg-cover bg-center text-white min-h-[420px] md:min-h-[520px] flex items-center"
    style="background-image: url('{{ asset('storage/kebutuhan/bg.png') }}');">

    <!-- Overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-black/85 via-black/65 to-black/80"></div>

    <!-- Grid -->
    <div
        class="absolute inset-0 opacity-[0.03]"
        style="background-image:linear-gradient(#fff 1px,transparent 1px),linear-gradient(90deg,#fff 1px,transparent 1px);background-size:40px 40px;">
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-16 sm:py-20 text-center w-full">

        <span class="inline-block text-[10px] sm:text-[11px] font-medium tracking-[0.2em] uppercase text-white/60 border border-white/20 px-4 py-2 rounded-full mb-5 backdrop-blur-sm">
            Barang Bekas Pilihan
        </span>

        <div class="mb-6 flex justify-center">
            <img
                src="{{ asset('storage/kebutuhan/logo.png') }}"
                alt="Bekas Dicintai"
                class="w-full max-w-[450px] md:max-w-[550px] h-auto drop-shadow-[0_10px_30px_rgba(0,0,0,.5)]">
        </div>

        <p class="text-sm md:text-base text-white/65 mb-8 font-light max-w-xl mx-auto">
            Temukan barang preloved berkualitas dengan harga terbaik.
        </p>

        <div class="flex justify-center gap-3 flex-wrap mb-12">

            <a href="#produk"
                class="group inline-flex items-center gap-2 bg-amber-300 hover:bg-amber-200 text-stone-900 px-6 sm:px-7 py-3 rounded-full font-semibold text-sm shadow-lg shadow-amber-500/20 transition-all duration-300 hover:-translate-y-0.5">
                Lihat Produk

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-3.5 h-3.5 transition-transform duration-300 group-hover:translate-x-1"
                    viewBox="0 0 20 20"
                    fill="currentColor">

                    <path fill-rule="evenodd"
                        d="M10.293 3.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 9H3a1 1 0 110-2h9.586l-2.293-2.293a1 1 0 010-1.414z"
                        clip-rule="evenodd" />

                </svg>

            </a>

            @auth
            <button
                onclick="openModal()"
                class="border border-white/25 bg-white/10 backdrop-blur-md px-6 sm:px-7 py-3 rounded-full font-semibold text-sm hover:bg-white hover:text-stone-900 transition-all duration-300">
                Beri Komentar
            </button>
            @endauth

        </div>
    </div>

    <!-- Fade putih -->
    <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-white to-transparent"></div>

</section>

   <!-- CATEGORIES -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-10">

        <div class="flex items-end justify-between mb-6 sm:mb-8 pb-4 border-b border-stone-200">
            <h2 class="font-serif text-2xl sm:text-3xl md:text-4xl font-bold text-stone-900">
                Jelajahi
                <span class="block italic text-amber-700/80 text-[0.9em]">
                    Kategori
                </span>
            </h2>
            <span class="text-[10px] sm:text-[11px] uppercase tracking-widest text-stone-400 font-medium hidden sm:block">Pilih Sesuai Kebutuhan</span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">

            @foreach($categories as $category)

            <a href="{{ route('categories.show', $category) }}"
                class="group relative flex flex-col items-center text-center px-5 py-8 sm:py-10 overflow-hidden transition-all duration-500 hover:border-amber-300 hover:shadow-[0_20px_40px_-15px_rgba(180,120,40,0.25)] hover:-translate-y-1.5">

                <!-- Decorative background blob -->
                <div class="absolute -top-10 -right-10 w-28 h-28 rounded-full bg-gradient-to-br from-amber-100 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 blur-2xl"></div>

                <!-- Subtle top accent line -->
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-0 h-[3px] bg-gradient-to-r from-amber-400 to-amber-600 rounded-full group-hover:w-12 transition-all duration-500"></div>

                <!-- Icon badge -->
                <div class="relative mb-5">
                    <!-- outer glow ring -->
                    <div class="absolute -inset-2 rounded-full bg-gradient-to-br from-amber-300/50 to-amber-500/20 opacity-0 group-hover:opacity-100 blur-md transition-opacity duration-500"></div>

                    <div class="relative w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-gradient-to-br from-stone-50 to-white ring-1 ring-stone-200 shadow-sm flex items-center justify-center overflow-hidden group-hover:ring-2 group-hover:ring-amber-400 group-hover:scale-110 transition-all duration-500">

                        @switch($category->name)

                        @case('Fashion')
                         <img src="{{ asset('storage/kebutuhan/2.png') }}"
                            class="w-full h-full object-cover">
                        @break

                        @case('Elektronik')
                        <img src="{{ asset('storage/kebutuhan/3.png') }}"
                            class="w-full h-full object-cover">
                        @break

                        @case('Hobi')
                         <img src="{{ asset('storage/kebutuhan/4.png') }}"
                            class="w-full h-full object-cover">
                        @break

                        @case('Rumah Tangga')
                        <img src="{{ asset('storage/kebutuhan/1.png') }}"
                            class="w-full h-full object-cover">
                        @break

                        @default
                        <img src="{{ asset('storage/categories/default.png') }}"
                            class="w-full h-full object-cover">

                        @endswitch

                    </div>
                </div>

                <h3 class="font-serif text-sm sm:text-base text-stone-800 group-hover:text-amber-700 transition-colors duration-300">
                    {{ $category->name }}
                </h3>

                <span class="relative mt-1.5 text-[10px] sm:text-[11px] text-stone-400 group-hover:text-amber-600/70 transition-colors duration-300 text-center">
                    Lihat produk
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-full top-1/2 -translate-y-1/2 ml-1 w-3 h-3 -translate-x-1 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 9H3a1 1 0 110-2h9.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </span>

            </a>

            @endforeach

        </div>

    </section>

    <!-- PRODUK -->
    <section id="produk" class="max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-16">

        <div class="flex items-end justify-between mb-6 sm:mb-8 pb-4 sm:pb-5 border-b border-stone-200">
            <h2 class="font-serif text-2xl sm:text-3xl md:text-4xl font-bold text-stone-900 leading-tight">
                Produk
                <span class="block italic text-amber-700/80 text-[0.9em]">Terbaru</span>
            </h2>
            <span class="text-[10px] sm:text-[11px] uppercase tracking-widest text-stone-400 font-medium hidden sm:block">Koleksi</span>
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
    @auth
    @include('modals.testimonials')
    @endauth
    @include('partials.testimonials')
</x-app-layout>