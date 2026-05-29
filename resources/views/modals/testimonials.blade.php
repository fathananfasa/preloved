<div
    id="testimonialModal"
    class="hidden fixed inset-0 bg-black/60 z-50 items-center justify-center px-4">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">

        @if(!$hasPurchased)

        <!-- Belum transaksi -->
        <div class="flex items-end justify-between px-6 pt-6 pb-4 border-b border-stone-100">
            <div>
                <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Ulasan</span>
                <h2 class="font-serif text-xl font-bold text-stone-900 mt-0.5">Belum Bisa Ulasan</h2>
            </div>
            <button onclick="closeModal()"
                class="w-8 h-8 flex items-center justify-center rounded-xl border border-stone-200 text-stone-400 hover:border-stone-400 hover:text-stone-700 transition duration-200 text-sm">
                ✕
            </button>
        </div>
        <div class="px-6 py-6 text-center">
            <div class="w-14 h-14 rounded-2xl bg-stone-100 flex items-center justify-center text-2xl mx-auto mb-3">
                🛒
            </div>
            <p class="text-sm text-stone-500 leading-relaxed">
                Kamu perlu melakukan transaksi terlebih dahulu sebelum bisa memberikan ulasan.
            </p>
            <button onclick="closeModal()"
                class="mt-5 w-full py-2.5 border border-stone-200 hover:border-stone-400 text-stone-600 hover:text-stone-900 rounded-xl text-sm font-semibold transition duration-200">
                Tutup
            </button>
        </div>

        @elseif($alreadyCommented)

        <!-- Sudah komentar -->
        <div class="flex items-end justify-between px-6 pt-6 pb-4 border-b border-stone-100">
            <div>
                <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Ulasan</span>
                <h2 class="font-serif text-xl font-bold text-stone-900 mt-0.5">Terima Kasih!</h2>
            </div>
            <button onclick="closeModal()"
                class="w-8 h-8 flex items-center justify-center rounded-xl border border-stone-200 text-stone-400 hover:border-stone-400 hover:text-stone-700 transition duration-200 text-sm">
                ✕
            </button>
        </div>
        <div class="px-6 py-6 text-center">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-2xl mx-auto mb-3">
                ✅
            </div>
            <p class="text-sm text-stone-500 leading-relaxed">
                Kamu sudah memberikan testimonial. Terima kasih atas ulasannya!
            </p>
            <button onclick="closeModal()"
                class="mt-5 w-full py-2.5 border border-stone-200 hover:border-stone-400 text-stone-600 hover:text-stone-900 rounded-xl text-sm font-semibold transition duration-200">
                Tutup
            </button>
        </div>

        @else

        <!-- Form ulasan -->
        <div class="flex items-end justify-between px-6 pt-6 pb-4 border-b border-stone-100">
            <div>
                <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Ulasan</span>
                <h2 class="font-serif text-xl font-bold text-stone-900 mt-0.5">Bagaimana Pengalamanmu?</h2>
            </div>
            <button onclick="closeModal()"
                class="w-8 h-8 flex items-center justify-center rounded-xl border border-stone-200 text-stone-400 hover:border-stone-400 hover:text-stone-700 transition duration-200 text-sm">
                ✕
            </button>
        </div>

        <form action="{{ route('buyer.testimonial.store') }}" method="POST" class="px-6 py-5 space-y-4">
            @csrf

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Rating</label>
                <select name="rating" required
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                    <option value="">Pilih Rating</option>
                    @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">
                        {{ $i }} {{ str_repeat('★', $i) }}{{ str_repeat('☆', 5 - $i) }}
                    </option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Pesan</label>
                <textarea
                    name="message"
                    rows="4"
                    required
                    placeholder="Tulis pengalamanmu berbelanja di sini..."
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition resize-none"></textarea>
            </div>

            <div class="flex gap-3 pt-1">
                <button type="button" onclick="closeModal()"
                    class="flex-1 py-2.5 rounded-xl border border-stone-200 text-stone-600 text-sm font-semibold hover:border-stone-400 hover:text-stone-900 transition duration-200">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl bg-stone-900 hover:bg-stone-700 text-white text-sm font-semibold tracking-wide transition duration-200 shadow hover:shadow-md">
                    Kirim Ulasan
                </button>
            </div>

        </form>

        @endif

    </div>

</div>

<script>
    function openModal() {
        const modal = document.getElementById('testimonialModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('testimonialModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>