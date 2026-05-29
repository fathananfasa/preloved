<x-app-layout>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-12">

        <div class="mb-6 sm:mb-8 pb-5 border-b border-stone-200">
            <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Belanja</span>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900 mt-1">Keranjang</h1>
        </div>

        @if($carts->count() > 0)

        <form action="{{ route('buyer.checkout.cart') }}" method="GET" id="checkoutForm">

            <div class="bg-white rounded-2xl border border-stone-100 shadow-sm divide-y divide-stone-100">

                @foreach($carts as $item)
                <div class="p-4 sm:p-5 cart-row">

                    <div class="flex gap-3 sm:gap-4">

                        {{-- CHECKBOX --}}
                        <div class="pt-1">
                            <input type="checkbox"
                                name="selected_items[]"
                                value="{{ $item->id }}"
                                class="cart-checkbox w-4 h-4 accent-amber-500 cursor-pointer"
                                data-price="{{ $item->product->price_original }}">
                        </div>

                        {{-- GAMBAR --}}
                        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-stone-50 rounded-xl flex items-center justify-center overflow-hidden shrink-0 border border-stone-100">
                            <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}"
                                class="object-contain max-h-full max-w-full">
                        </div>

                        {{-- DETAIL --}}
                        <div class="flex-1 flex flex-col min-w-0">

                            <h2 class="font-medium text-sm sm:text-base text-stone-900 leading-snug line-clamp-2">
                                {{ $item->product->name }}
                            </h2>

                            <div class="w-5 h-px bg-stone-200 my-2"></div>

                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <p class="font-serif font-bold text-amber-700 text-sm sm:text-base">
                                    Rp {{ number_format($item->product->price_original, 0, ',', '.') }}
                                </p>

                                <input type="number"
                                    value="{{ $item->quantity }}"
                                    min="1"
                                    class="quantity-input w-16 rounded-xl border border-stone-200 px-2 py-1.5 text-center text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                                    data-id="{{ $item->id }}"
                                    data-price="{{ $item->product->price_original }}">
                            </div>

                            <div class="mt-2">
                                <button type="button"
                                    onclick="deleteCart({{ $item->id }})"
                                    class="text-[11px] font-semibold text-red-400 hover:text-red-600 border border-stone-200 hover:border-red-300 px-2.5 py-1 rounded-lg transition duration-200">
                                    Hapus
                                </button>
                            </div>

                            <div class="hidden subtotal">
                                Rp {{ number_format($item->product->price_original * $item->quantity, 0, ',', '.') }}
                            </div>

                        </div>

                    </div>

                </div>
                @endforeach

            </div>

            {{-- TOTAL --}}
            <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-4 sm:p-5 mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Total Pembayaran</span>
                    <p class="font-serif text-xl sm:text-2xl font-bold text-stone-900 mt-0.5">
                        Rp <span id="totalPrice">0</span>
                    </p>
                </div>

                <button type="submit"
                    id="checkoutBtn"
                    disabled
                    class="w-full sm:w-auto px-8 py-3 rounded-xl text-sm font-semibold tracking-wide transition duration-200 shadow cursor-not-allowed bg-stone-200 text-stone-400">
                    Checkout
                </button>
            </div>

        </form>

        <form id="deleteForm" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>

        @else

        <div class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-2xl border border-stone-100 shadow-sm">
            <div class="w-16 h-16 rounded-2xl bg-stone-100 flex items-center justify-center mb-4 text-2xl">
                🛒
            </div>
            <p class="font-serif text-lg font-bold text-stone-700 mb-1">Keranjang masih kosong</p>
            <p class="text-sm text-stone-400 mb-5">Yuk temukan produk preloved favoritmu.</p>
            <a href="{{ route('buyer.home') }}"
                class="inline-block bg-amber-400 hover:bg-amber-300 text-stone-900 px-6 py-2.5 rounded-xl text-sm font-semibold transition duration-200 shadow hover:shadow-md">
                Belanja Sekarang
            </a>
        </div>

        @endif

    </div>
    @include('partials.carts')
</x-app-layout>