<x-app-layout>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">

        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">

            <div class="grid md:grid-cols-2 gap-8 p-6 sm:p-10">

                {{-- LEFT : IMAGE --}}
                <div>

                    <div class="relative bg-gradient-to-br from-gray-50 to-gray-100 rounded-3xl flex items-center justify-center h-80 sm:h-[420px] md:h-[520px] mb-5 overflow-hidden shadow-inner">
                        <img
                            id="mainImage"
                            src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                            class="max-h-full max-w-full object-contain transition duration-500 hover:scale-110">
                    </div>

                    <div class="flex gap-3 overflow-x-auto pb-2">
                        @foreach ($product->images as $image)
                        <img
                            src="{{ asset('storage/' . $image->image_path) }}"
                            class="w-20 h-20 object-cover rounded-2xl cursor-pointer border-2 border-transparent hover:border-blue-500 transition duration-200 shadow-sm"
                            onclick="document.getElementById('mainImage').src = this.src">
                        @endforeach
                    </div>

                </div>

                {{-- RIGHT : PRODUCT INFO --}}
                <div class="flex flex-col justify-between">

                    <div>

                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-snug mb-4">
                            {{ $product->name }}
                        </h1>

                        <div class="mb-6">
                            <p class="text-3xl sm:text-4xl font-extrabold text-green-600">
                                Rp {{ number_format($product->price_original, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                Harga asli
                            </p>
                        </div>

                        <div class="mb-6">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold 
                                {{ $product->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                {{ $product->stock > 0 ? 'Stok tersedia: ' . $product->stock : 'Stok habis' }}
                            </span>
                        </div>

                        <div class="bg-gray-50 p-5 rounded-2xl mb-8 border border-gray-100">
                            <h3 class="font-semibold text-gray-800 mb-2">
                                Deskripsi Produk
                            </h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                {{ $product->description }}
                            </p>
                        </div>

                    </div>

                    <div class="space-y-6">
                        <form action="{{ route('buyer.cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit">
                                Tambah ke Keranjang
                            </button>
                        </form>

                        {{-- NEGOTIATION --}}
                        <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-md">

                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                Negosiasi Harga
                            </h3>

                            @guest
                            <a href="{{ route('login') }}"
                                class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-semibold transition duration-200 shadow-md hover:shadow-lg">
                                Login untuk Negosiasi
                            </a>
                            @else

                            @if ($myNegotiation)

                            @php
                            $statusColor = match($myNegotiation->status) {
                            'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                            'accepted' => 'bg-green-100 text-green-700 border-green-300',
                            'rejected' => 'bg-red-100 text-red-700 border-red-300',
                            default => 'bg-gray-100 text-gray-700 border-gray-300',
                            };
                            @endphp

                            <div class="flex items-center justify-between mb-4">
                                <span class="px-4 py-1 rounded-full text-sm font-semibold border {{ $statusColor }}">
                                    {{ ucfirst($myNegotiation->status) }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    Tawaran kamu
                                </span>
                            </div>

                            <div class="mb-4">
                                <p class="text-2xl font-bold text-green-600">
                                    Rp {{ number_format($myNegotiation->offer_price, 0, ',', '.') }}
                                </p>
                            </div>

                            @if ($myNegotiation->status === 'pending')

                            <form
                                action="{{ route('buyer.negotiations.update', $myNegotiation->id) }}"
                                method="POST"
                                class="flex flex-col gap-3">
                                @csrf
                                @method('PUT')

                                <input
                                    type="number"
                                    name="offer_price"
                                    value="{{ $myNegotiation->offer_price }}"
                                    required
                                    min="1"
                                    class="px-4 py-3 rounded-2xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">

                                <button
                                    type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-2xl font-semibold transition shadow hover:shadow-lg">
                                    Ubah Tawaran
                                </button>
                            </form>

                            @elseif ($myNegotiation->status === 'accepted')

                            <a href="{{ route('buyer.checkout', $product->id) }}"
                                class="block w-full text-center bg-green-600 hover:bg-green-700 text-white py-3 rounded-2xl font-semibold transition shadow hover:shadow-lg">
                                Lanjutkan Checkout
                            </a>

                            @elseif ($myNegotiation->status === 'rejected')

                            <div class="px-4 py-3 rounded-2xl bg-red-100 text-red-600 text-sm">
                                Tawaran ditolak. Coba ajukan harga baru.
                            </div>

                            @endif

                            @else

                            <form
                                action="{{ route('buyer.negotiations.store', $product->id) }}"
                                method="POST"
                                class="flex flex-col gap-3">
                                @csrf

                                <input
                                    type="number"
                                    name="offer_price"
                                    placeholder="Masukkan harga tawaran"
                                    required
                                    min="1"
                                    class="px-4 py-3 rounded-2xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500">

                                <button
                                    type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white py-3 rounded-2xl font-semibold transition shadow hover:shadow-lg">
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

    </div>

</x-app-layout>