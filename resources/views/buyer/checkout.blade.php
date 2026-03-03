    <x-app-layout>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-8">

            <h1 class="text-2xl font-bold mb-8">Checkout</h1>

            @php
            $basePrice = $type === 'single' ? $price : $total;
            @endphp

            <div class="grid md:grid-cols-3 gap-8">

                {{-- LEFT --}}
                <div class="md:col-span-2 space-y-6">

                    {{-- PRODUCT / CART --}}
                    <div class="bg-white rounded-2xl shadow border p-6 space-y-4">

                        @if($type === 'single')

                        <div class="flex gap-4">
                            <div class="w-24 h-24 bg-gray-50 rounded-xl overflow-hidden">
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                    class="object-contain max-h-full">
                            </div>

                            <div>
                                <h2 class="font-semibold text-lg">
                                    {{ $product->name }}
                                </h2>

                                <p class="text-xl font-bold text-green-600 mt-2">
                                    Rp {{ number_format($price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        @elseif($type === 'cart')

                        @foreach($carts as $item)
                        <div class="flex justify-between items-center">
                            <div class="flex gap-3">
                                <div class="w-20 h-20 bg-gray-50 rounded-xl overflow-hidden">
                                    <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}"
                                        class="object-contain max-h-full">
                                </div>

                                <div>
                                    <h2 class="font-semibold">
                                        {{ $item->product->name }}
                                    </h2>
                                    <p class="text-sm text-gray-500">
                                        Qty: {{ $item->quantity }}
                                    </p>
                                </div>
                            </div>

                            <div class="font-semibold">
                                Rp {{ number_format($item->product->price_original * $item->quantity, 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach

                        @endif

                    </div>

                    {{-- ADDRESS --}}
                    <div class="bg-white rounded-2xl shadow border p-6">
                        <h3 class="font-semibold mb-4">Pilih Alamat Pengiriman</h3>

                        @foreach($addresses as $address)
                        <label class="block border rounded-xl p-4 mb-3 cursor-pointer hover:border-blue-500">
                            <input type="radio"
                                name="address_radio"
                                value="{{ $address->id }}"
                                class="mr-2 address-radio"
                                data-district="{{ $address->kecamatan }}">

                            <span class="font-semibold">
                                {{ $address->receiver_name }}
                            </span>

                            <div class="text-sm text-gray-600 mt-1">
                                {{ $address->address }},
                                {{ $address->k_name }},
                                {{ $address->c_name }},
                                {{ $address->p_name }},
                                {{ $address->postal_code }}
                            </div>

                            <div class="text-sm text-gray-500">
                                {{ $address->phone }}
                            </div>
                        </label>
                        @endforeach
                    </div>

                    {{-- COURIER --}}
                    <div class="bg-white rounded-2xl shadow border p-6">
                        <h3 class="font-semibold mb-4">Pilih Kurir</h3>

                        <select id="courier" class="w-full border rounded-xl px-4 py-3">
                            <option value="">Pilih kurir</option>
                            <option value="jne">JNE</option>
                            <option value="tiki">TIKI</option>
                            <option value="pos">POS</option>
                        </select>

                        <div id="service_wrapper" class="mt-4 hidden">
                            <select id="service" class="w-full border rounded-xl px-4 py-3"></select>
                        </div>
                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="bg-white rounded-2xl shadow border p-6 h-fit">

                    <h3 class="font-semibold mb-6">Ringkasan Pembayaran</h3>

                    <div class="flex justify-between text-sm mb-3">
                        <span>Harga Barang</span>
                        <span id="product_price"
                            data-price="{{ $basePrice }}">
                            Rp {{ number_format($basePrice, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between text-sm mb-3">
                        <span>Ongkir</span>
                        <span id="shipping_cost">Rp 0</span>
                    </div>

                    <hr class="my-4">

                    <div class="flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span id="total_price">
                            Rp {{ number_format($basePrice, 0, ',', '.') }}
                        </span>
                    </div>

                    <form action="{{ $type === 'single'
                            ? route('buyer.order.store', $product->id)
                            : route('buyer.order.store.cart') }}"
                        method="POST"
                        class="mt-6">
                        @csrf

                        <input type="hidden" name="address_id" id="input_address">
                        <input type="hidden" name="courier" id="input_courier">
                        <input type="hidden" name="service" id="input_service">
                        <input type="hidden" name="shipping_cost" id="input_shipping_cost">

                        @if($type === 'single')
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        @endif

                        @if($type === 'cart')
                        @foreach($carts as $item)
                        <input type="hidden" name="cart_ids[]" value="{{ $item->id }}">
                        @endforeach
                        @endif

                        <button type="button" id="pay-button"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold">
                            Bayar Sekarang
                        </button>
                    </form>

                </div>
            </div>
        </div>


        <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{env('MIDTRANS_CLIENT_KEY')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const courierSelect = document.getElementById('courier');
                const serviceSelect = document.getElementById('service');
                const serviceWrapper = document.getElementById('service_wrapper');

                const productPrice = parseInt(document.getElementById('product_price').dataset.price);
                const shippingCostText = document.getElementById('shipping_cost');
                const totalText = document.getElementById('total_price');

                courierSelect.addEventListener('change', function() {

                    const selectedRadio = document.querySelector('input[name="address_radio"]:checked');

                    if (!selectedRadio) {
                        alert("Pilih alamat dulu.");
                        this.value = "";
                        return;
                    }

                    const districtId = selectedRadio.dataset.district;

                    if (!this.value) return;

                    document.getElementById('input_address').value = selectedRadio.value;
                    document.getElementById('input_courier').value = this.value;

                    fetch("{{ route('buyer.rajaongkir.cost') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                destination: districtId,
                                weight: 1000,
                                courier: this.value
                            })
                        })
                        .then(res => res.json())
                        .then(data => {

                            serviceSelect.innerHTML = '';
                            serviceWrapper.classList.add('hidden');

                            if (!Array.isArray(data) || data.length === 0) {
                                alert("Ongkir tidak tersedia.");
                                return;
                            }

                            serviceWrapper.classList.remove('hidden');

                            data.forEach(item => {
                                const option = document.createElement('option');
                                option.value = item.service;
                                option.dataset.cost = item.cost;

                                option.textContent =
                                    item.service +
                                    " - Rp " + Number(item.cost).toLocaleString('id-ID') +
                                    " (" + item.etd + ")";

                                serviceSelect.appendChild(option);
                            });

                        });
                });

                serviceSelect.addEventListener('change', function() {

                    const shippingCost = parseInt(
                        this.options[this.selectedIndex].dataset.cost
                    );

                    shippingCostText.innerText =
                        "Rp " + shippingCost.toLocaleString('id-ID');

                    totalText.innerText =
                        "Rp " + (productPrice + shippingCost).toLocaleString('id-ID');

                    document.getElementById('input_shipping_cost').value = shippingCost;
                    document.getElementById('input_service').value = this.value;
                });

            });

            document.getElementById('pay-button').addEventListener('click', function() {

                const form = this.closest('form');
                const formData = new FormData(form);

                fetch(form.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(response => {

                        if (!response.snap_token) {
                            alert("Gagal mendapatkan Snap Token");
                            return;
                        }

                        snap.pay(response.snap_token, {

                            onSuccess: function(result) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Pembayaran Berhasil!',
                                    text: 'Pesanan kamu sedang diproses.',
                                    showCancelButton: true,
                                    confirmButtonText: 'Lihat Pesanan',
                                    cancelButtonText: 'Kembali ke Beranda',
                                    confirmButtonColor: '#2563eb',
                                    cancelButtonColor: '#6b7280'
                                }).then((response) => {

                                    if (response.isConfirmed) {
                                        window.location.href = "{{ route('buyer.home') }}";
                                    } else {
                                        window.location.href = "{{ route('buyer.home') }}";
                                    }

                                });
                            },

                            onPending: function(result) {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Menunggu Pembayaran',
                                    text: 'Silakan selesaikan pembayaran kamu.',
                                    confirmButtonColor: '#2563eb'
                                }).then(() => {
                                    window.location.href = "{{ route('buyer.home') }}";
                                });
                            },

                            onError: function(result) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Pembayaran Gagal',
                                    text: 'Terjadi kesalahan saat pembayaran.',
                                    confirmButtonColor: '#dc2626'
                                });
                            }

                        });

                    })
                    .catch(error => {
                        console.log(error);
                        alert("Terjadi kesalahan.");
                    });

            });
        </script>

    </x-app-layout>