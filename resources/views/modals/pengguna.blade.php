<!-- Modal -->
<div
    x-show="open"
    x-transition
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
    style="display: none;">

    <div
        @click.away="open = false"
        class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">

        <!-- Header -->
        <div class="flex items-end justify-between px-6 pt-6 pb-4 border-b border-stone-100">
            <div>
                <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Statistik</span>
                <h2 class="font-serif text-xl font-bold text-stone-900 mt-0.5">Statistik Buyer</h2>
                <p class="text-xs text-stone-400 mt-0.5">{{ $user->name }}</p>
            </div>
            <button
                @click="open = false"
                class="w-8 h-8 flex items-center justify-center rounded-xl border border-stone-200 text-stone-400 hover:border-stone-400 hover:text-stone-700 transition duration-200 text-sm">
                ✕
            </button>
        </div>

        <div class="px-6 py-5 space-y-5">

            <!-- Statistik Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">

                <!-- Total Negosiasi -->
                <div class="bg-stone-50 border border-stone-100 rounded-2xl p-4">
                    <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Total Negosiasi</span>
                    <p class="font-serif text-3xl font-bold text-stone-900 mt-1">
                        {{ $user->total_negotiations }}
                    </p>
                </div>

                <!-- Produk Diblok -->
                <div class="bg-red-50 border border-red-100 rounded-2xl p-4">
                    <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Produk Diblok</span>
                    <p class="font-serif text-3xl font-bold text-red-500 mt-1">
                        {{ $user->blocked_products }}
                    </p>
                </div>

                <!-- Avg Attempt -->
                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4">
                    <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Avg Attempt</span>
                    <p class="font-serif text-3xl font-bold text-amber-700 mt-1">
                        {{ number_format($user->avg_attempt_pending, 1) }}
                    </p>
                </div>

                <!-- Negosiasi Diterima -->
                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4">
                    <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Nego Diterima</span>
                    <p class="font-serif text-3xl font-bold text-emerald-600 mt-1">
                        {{ $user->accepted_negotiations }}
                    </p>
                </div>

                <!-- Negosiasi Pending -->
                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4">
                    <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Nego Pending</span>
                    <p class="font-serif text-3xl font-bold text-amber-600 mt-1">
                        {{ $user->pending_negotiations }}
                    </p>
                </div>

                <!-- Total Transaksi -->
                <div class="bg-stone-50 border border-stone-100 rounded-2xl p-4">
                    <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Total Transaksi</span>
                    <p class="font-serif text-3xl font-bold text-stone-900 mt-1">
                        {{ $user->total_transactions }}
                    </p>
                </div>

            </div>

            <!-- Progress Bar -->
            <div class="bg-stone-50 border border-stone-100 rounded-2xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Keberhasilan Transaksi</span>
                    <span class="font-serif font-bold text-emerald-600 text-lg">
                        {{ $user->transaction_percentage }}%
                    </span>
                </div>
                <div class="w-full bg-stone-200 rounded-full h-2">
                    <div
                        class="bg-emerald-500 h-2 rounded-full transition-all duration-500"
                        style="width: {{ min($user->transaction_percentage, 100) }}%">
                    </div>
                </div>
            </div>

            <!-- Total Belanja -->
            <div class="bg-stone-900 rounded-2xl p-5">
                <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Total Belanja</span>
                <p class="font-serif text-3xl sm:text-4xl font-bold text-amber-300 mt-1">
                    Rp {{ number_format($user->total_spent, 0, ',', '.') }}
                </p>
            </div>

        </div>

    </div>

</div>