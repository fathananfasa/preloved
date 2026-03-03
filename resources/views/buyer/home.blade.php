<x-app-layout>

    <!-- HERO -->
    <section class="relative bg-cover bg-center text-white"
        style="background-image: url('{{ asset('storage/kebutuhan/bg.webp') }}');">

        <div class="absolute inset-0 bg-black/60"></div>

        <div class="relative max-w-7xl mx-auto px-6 py-14 md:py-16 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">
                Preloved.id
            </h1>

            <p class="text-sm md:text-base text-gray-200 mb-6">
                Temukan barang preloved berkualitas dengan harga terbaik.
            </p>

            <a href="#produk"
                class="inline-block bg-white text-gray-900 px-6 py-2 rounded-full font-semibold hover:bg-gray-200 transition shadow">
                Lihat Produk
            </a>
        </div>
    </section>


    <!-- PRODUK -->
    <section id="produk" class="max-w-7xl mx-auto px-6 py-16">

        <div class="flex items-center justify-between mb-10">
            <h2 class="text-3xl font-bold text-gray-900">
                Produk Terbaru
            </h2>
        </div>

        <!-- hanya ubah gap di mobile -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">

            @foreach($products as $product)
            <a href="{{ route('products.show', $product->id) }}"
                class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition duration-300 overflow-hidden flex flex-col">

                <!-- Image -->
                <!-- ubah tinggi jadi aspect-square supaya mobile rapi -->
                <div class="relative bg-gray-50 aspect-square md:h-48 md:aspect-auto flex items-center justify-center overflow-hidden">
                    <img
                        src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('default.png') }}"
                        alt="{{ $product->name }}"
                        class="w-full h-full object-contain md:object-contain transition duration-300 group-hover:scale-110">

                    <!-- Stock Badge -->
                    <span class="absolute top-3 left-3 px-2 py-1 text-[10px] md:text-xs font-semibold rounded-full
                        {{ $product->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                        {{ $product->stock > 0 ? 'Tersedia' : 'Habis' }}
                    </span>
                </div>

                <!-- Content -->
                <div class="p-3 md:p-4 flex flex-col flex-grow">

                    <h3 class="text-sm md:text-base font-semibold text-gray-800 mb-1 line-clamp-2 group-hover:text-blue-600 transition">
                        {{ $product->name }}
                    </h3>

                    <p class="text-base md:text-lg font-bold text-blue-600 mt-auto">
                        Rp {{ number_format($product->price_original ?? $product->price, 0, ',', '.') }}
                    </p>

                </div>
            </a>
            @endforeach

        </div>
    </section>


    <!-- FOOTER -->
    <footer class="bg-gray-50 border-t mt-20">
        <div class="max-w-7xl mx-auto px-6 py-10 text-center text-sm text-gray-500">
            <p class="mb-2 font-semibold text-gray-700">
                Preloved.id
            </p>
            <p>
                © {{ date('Y') }} Preloved.id. All rights reserved.
            </p>
        </div>
    </footer>

</x-app-layout>
