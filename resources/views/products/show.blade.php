<x-app-layout>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-12">

        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-xl border border-stone-100 overflow-hidden">

            <div class="grid md:grid-cols-2 gap-0">

                {{-- LEFT : IMAGE --}}
                <div class="p-5 sm:p-8 border-b md:border-b-0 md:border-r border-stone-100">

                    <div class="relative bg-stone-50 rounded-2xl flex items-center justify-center h-64 sm:h-80 md:h-[420px] mb-4 overflow-hidden">
                        <img
                            id="mainImage"
                            src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                            class="max-h-full max-w-full object-contain transition duration-500 hover:scale-105">
                    </div>

                    <div class="flex gap-2 overflow-x-auto pb-1">
                        @foreach ($product->images as $image)
                        <img
                            src="{{ asset('storage/' . $image->image_path) }}"
                            class="w-16 h-16 sm:w-20 sm:h-20 flex-shrink-0 object-cover rounded-xl cursor-pointer border-2 border-transparent hover:border-amber-400 transition duration-200 opacity-70 hover:opacity-100"
                            onclick="document.getElementById('mainImage').src = this.src">
                        @endforeach
                    </div>

                </div>

                {{-- RIGHT : PRODUCT INFO --}}
                <div class="p-5 sm:p-8 flex flex-col gap-5">

                    {{-- Name --}}
                    <div>
                        <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Produk Preloved</span>
                        <h1 class="font-serif text-xl sm:text-2xl md:text-3xl font-bold text-stone-900 leading-snug mt-1">
                            {{ $product->name }}
                        </h1>
                    </div>

                    {{-- Price & Stock --}}
                    <div class="flex items-end justify-between gap-4 flex-wrap">
                        <div>
                            <p class="font-serif text-2xl sm:text-3xl font-bold text-amber-700">
                                Rp {{ number_format($product->price_original, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-stone-400 mt-0.5 tracking-wide">Harga asli</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-semibold tracking-wide uppercase
                            {{ $product->stock > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-500' }}">
                            {{ $product->stock > 0 ? 'Stok: ' . $product->stock : 'Habis' }}
                        </span>
                    </div>

                    {{-- Divider --}}
                    <div class="h-px bg-stone-100"></div>

                    {{-- Description --}}
                    <div>
                        <h3 class="text-xs uppercase tracking-widest text-stone-400 font-medium mb-2">Deskripsi</h3>
                        <p class="text-stone-600 text-sm leading-relaxed">
                            {{ $product->description }}
                        </p>
                    </div>

                    {{-- Divider --}}
                    <div class="h-px bg-stone-100"></div>

                    {{-- Add to Cart --}}
                    <form action="{{ route('buyer.cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full bg-stone-900 hover:bg-stone-700 text-white py-3 rounded-xl font-semibold text-sm tracking-wide transition duration-200 shadow hover:shadow-md">
                            Tambah ke Keranjang
                        </button>
                    </form>

                    {{-- NEGOTIATION --}}
                    <div class="bg-stone-50 border border-stone-200 rounded-2xl p-5">

                        <h3 class="text-xs uppercase tracking-widest text-stone-400 font-medium mb-4">Negosiasi Harga</h3>

                        @guest
                        <a href="{{ route('login') }}"
                            class="block w-full text-center bg-amber-400 hover:bg-amber-300 text-stone-900 py-3 rounded-xl font-semibold text-sm transition duration-200 shadow hover:shadow-md">
                            Login untuk Negosiasi
                        </a>
                        @else

                        @if ($myNegotiation)

                        @php
                        $statusColor = match($myNegotiation->status) {
                        'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                        'accepted' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'rejected' => 'bg-red-100 text-red-600 border-red-200',
                        default => 'bg-stone-100 text-stone-600 border-stone-200',
                        };
                        @endphp

                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full text-[11px] font-semibold border uppercase tracking-wide {{ $statusColor }}">
                                {{ ucfirst($myNegotiation->status) }}
                            </span>
                            <span class="text-xs text-stone-400">Tawaran kamu</span>
                        </div>

                        <p class="font-serif text-xl font-bold text-amber-700 mb-4">
                            Rp {{ number_format($myNegotiation->final_price, 0, ',', '.') }}
                        </p>

                        @if ($myNegotiation->status === 'pending')

                        <form action="{{ route('buyer.negotiations.update', $myNegotiation->id) }}" method="POST" class="flex flex-col gap-3">
                            @csrf
                            @method('PUT')
                            <input
                                type="number"
                                name="offer_price"
                                value="{{ $myNegotiation->offer_price }}"
                                required min="1"
                                class="px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 text-stone-800 placeholder:text-stone-400">
                            <button type="submit"
                                class="bg-stone-900 hover:bg-stone-700 text-white py-2.5 rounded-xl font-semibold text-sm transition shadow hover:shadow-md">
                                Ubah Tawaran
                            </button>
                        </form>

                        @elseif ($myNegotiation->status === 'accepted')

                        <a href="{{ route('buyer.checkout', $product->id) }}"
                            class="block w-full text-center bg-emerald-600 hover:bg-emerald-500 text-white py-3 rounded-xl font-semibold text-sm transition shadow hover:shadow-md">
                            Lanjutkan Checkout
                        </a>

                        @elseif ($myNegotiation->status === 'rejected')

                        <div class="px-4 py-3 rounded-xl bg-red-50 border border-red-100 text-red-500 text-xs text-center">
                            Tawaran ditolak. Coba ajukan harga baru.
                        </div>

                        @endif

                        @else

                        <form id="negoForm" action="{{ route('buyer.negotiations.store', $product->id) }}" method="POST" class="flex flex-col gap-3">
                            @csrf
                            <input
                                type="number"
                                name="offer_price"
                                placeholder="Masukkan harga tawaran"
                                required min="1"
                                class="px-4 py-2.5 rounded-xl border border-stone-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 text-stone-800 placeholder:text-stone-400">
                            <button type="submit"
                                class="bg-amber-400 hover:bg-amber-300 text-stone-900 py-2.5 rounded-xl font-semibold text-sm transition shadow hover:shadow-md">
                                Ajukan Tawaran
                            </button>
                        </form>

                        @endif

                        @endguest
                    </div>

                </div>

            </div>

        </div>

    </div>

    @include('modals.nego')

</x-app-layout>