<x-app-layout>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 sm:py-8">

        <h1 class="text-xl sm:text-2xl font-bold mb-6 sm:mb-8">
            Keranjang Belanja
        </h1>

        @if($carts->count() > 0)

        <form action="{{ route('buyer.checkout.cart') }}" method="GET" id="checkoutForm">

            <div class="bg-white rounded-2xl shadow border divide-y">

                @foreach($carts as $item)
                <div class="p-4 sm:p-6 cart-row border-b">

                    <div class="flex gap-4">

                        {{-- CHECKBOX --}}
                        <div class="pt-2">
                            <input type="checkbox"
                                name="selected_items[]"
                                value="{{ $item->id }}"
                                class="cart-checkbox w-5 h-5"
                                data-price="{{ $item->product->price_original }}">
                        </div>

                        {{-- KONTEN --}}
                        <div class="flex-1">

                            <div class="flex gap-4">

                                {{-- GAMBAR --}}
                                <div class="w-28 h-28 sm:w-24 sm:h-24 bg-gray-50 rounded-xl flex items-center justify-center overflow-hidden">
                                    <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}"
                                        class="object-contain max-h-full">
                                </div>

                                {{-- DETAIL --}}
                                <div class="flex-1 flex flex-col">

                                    {{-- NAMA --}}
                                    <h2 class="font-semibold text-base sm:text-lg leading-tight">
                                        {{ $item->product->name }}
                                    </h2>

                                    {{-- HARGA + QTY --}}
                                    <div class="flex justify-between items-center mt-2">

                                        <p class="text-green-600 font-bold text-sm sm:text-base">
                                            Rp {{ number_format($item->product->price_original, 0, ',', '.') }}
                                        </p>

                                        <input type="number"
                                            value="{{ $item->quantity }}"
                                            min="1"
                                            class="quantity-input w-16 border rounded-lg px-2 py-1 text-center"
                                            data-id="{{ $item->id }}"
                                            data-price="{{ $item->product->price_original }}">
                                    </div>

                                    {{-- HAPUS --}}
                                    <div class="mt-2">
                                        <button type="button"
                                            onclick="deleteCart({{ $item->id }})"
                                            class="text-red-500 text-sm">
                                            Hapus
                                        </button>
                                    </div>

                                    {{-- SUBTOTAL tetap ada untuk JS --}}
                                    <div class="hidden subtotal">
                                        Rp {{ number_format($item->product->price_original * $item->quantity, 0, ',', '.') }}
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
                @endforeach

            </div>

            {{-- TOTAL --}}
            <div class="bg-white rounded-2xl shadow border p-4 sm:p-6 mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                <div class="text-base sm:text-lg font-bold">
                    Total: Rp <span id="totalPrice">0</span>
                </div>

                <button type="submit"
                    id="checkoutBtn"
                    disabled
                    class="w-full sm:w-auto bg-gray-400 text-white px-6 py-3 rounded-xl font-semibold cursor-not-allowed">
                    Checkout
                </button>

            </div>

        </form>

        {{-- FORM DELETE TERPISAH --}}
        <form id="deleteForm" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>

        @else

        <div class="bg-white rounded-2xl shadow border p-8 sm:p-12 text-center">
            <p class="text-gray-500">
                Keranjang kamu masih kosong.
            </p>

            <a href="{{ route('buyer.home') }}"
                class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-xl">
                Belanja Sekarang
            </a>
        </div>

        @endif

    </div>

    {{-- SCRIPT TIDAK DIUBAH --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const totalElement = document.getElementById("totalPrice");
            const checkoutBtn = document.getElementById("checkoutBtn");

            function formatRupiah(number) {
                return number.toLocaleString("id-ID");
            }

            function calculateTotal() {
                let total = 0;

                document.querySelectorAll(".cart-checkbox:checked").forEach(cb => {
                    const row = cb.closest(".cart-row");
                    const qtyInput = row.querySelector(".quantity-input");

                    const price = parseInt(cb.dataset.price);
                    const qty = parseInt(qtyInput.value);

                    total += price * qty;
                });

                totalElement.innerText = formatRupiah(total);
            }

            function toggleCheckoutButton() {
                const checked = document.querySelectorAll(".cart-checkbox:checked").length;

                if (checked > 0) {
                    checkoutBtn.disabled = false;
                    checkoutBtn.classList.remove("bg-gray-400", "cursor-not-allowed");
                    checkoutBtn.classList.add("bg-green-600", "hover:bg-green-700");
                } else {
                    checkoutBtn.disabled = true;
                    checkoutBtn.classList.add("bg-gray-400", "cursor-not-allowed");
                    checkoutBtn.classList.remove("bg-green-600", "hover:bg-green-700");
                }
            }

            document.querySelectorAll(".cart-checkbox").forEach(cb => {
                cb.addEventListener("change", function() {
                    calculateTotal();
                    toggleCheckoutButton();
                });
            });

            document.querySelectorAll(".quantity-input").forEach(input => {

                input.addEventListener("change", function() {

                    const cartId = this.dataset.id;
                    const quantity = this.value;
                    const price = parseInt(this.dataset.price);
                    const row = this.closest(".cart-row");
                    const subtotalElement = row.querySelector(".subtotal");

                    subtotalElement.innerText =
                        "Rp " + formatRupiah(price * quantity);

                    fetch(`/buyer/cart/${cartId}`, {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            quantity: quantity
                        })
                    });

                    calculateTotal();
                });

            });

            calculateTotal();
            toggleCheckoutButton();
        });

        function deleteCart(id) {
            if (!confirm("Hapus produk ini?")) return;

            const form = document.getElementById('deleteForm');
            form.action = `/buyer/cart/delete/${id}`;
            form.submit();
        }
    </script>

</x-app-layout>