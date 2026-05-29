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
                checkoutBtn.classList.remove("bg-stone-200", "text-stone-400", "cursor-not-allowed");
                checkoutBtn.classList.add("bg-stone-900", "hover:bg-stone-700", "text-white", "hover:shadow-md");
            } else {
                checkoutBtn.disabled = true;
                checkoutBtn.classList.add("bg-stone-200", "text-stone-400", "cursor-not-allowed");
                checkoutBtn.classList.remove("bg-stone-900", "hover:bg-stone-700", "text-white", "hover:shadow-md");
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

                subtotalElement.innerText = "Rp " + formatRupiah(price * quantity);

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