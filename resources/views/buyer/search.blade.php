<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <h2 class="text-xl font-semibold mb-4">Hasil Pencarian: "{{ request('search') }}"</h2>

        @if($products->count())
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($products as $product)
            <div class="border p-2 rounded-lg">

                <!-- Main Image -->
                <div class="bg-gray-100 rounded-lg flex items-center justify-center h-32 mb-2">
                    <img
                        src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : asset('default.png') }}"
                        alt="{{ $product->name }}"
                        class="max-h-full max-w-full object-contain rounded">
                </div>

                <!-- Product Name -->
                <h3 class="mt-2 font-medium">{{ $product->name }}</h3>

                <!-- Price -->
                <p class="text-sm text-gray-600">
                    Rp {{ number_format($product->price_original ?? $product->price, 0, ',', '.') }}
                </p>

                <!-- Detail Link -->
                <a href="{{ route('products.show', $product->id) }}" class="mt-1 inline-block text-blue-600">
                    Detail
                </a>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
        @else
        <p class="text-gray-500">Produk tidak ditemukan.</p>
        @endif

    </div>
</x-app-layout>