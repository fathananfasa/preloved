<x-app-layout>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-8">

        <!-- Header -->
        <div class="pb-5 border-b border-stone-200">
            <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Admin</span>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900 mt-1">Dashboard</h1>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">

            <!-- Total Produk -->
            <a href="{{ route('admin.products.index') }}"
                class="group bg-white rounded-2xl border border-stone-100 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Produk</span>
                        <p class="font-serif text-3xl font-bold text-stone-900 mt-1">{{ $productCount ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center group-hover:bg-amber-100 transition">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-stone-400 mt-3 group-hover:text-amber-600 transition font-medium">Lihat semua →</p>
            </a>

            <!-- Total Negosiasi -->
            <a href="{{ route('admin.products.index') }}"
                class="group bg-white rounded-2xl border border-stone-100 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Negosiasi</span>
                        <p class="font-serif text-3xl font-bold text-stone-900 mt-1">{{ $negotiationCount ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center group-hover:bg-amber-100 transition">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-stone-400 mt-3 group-hover:text-amber-600 transition font-medium">Lihat semua →</p>
            </a>

            <!-- Total Users -->
            <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Users</span>
                        <p class="font-serif text-3xl font-bold text-stone-900 mt-1">{{ $userCount ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A12.003 12.003 0 0112 15c3.866 0 7.223 1.5 9.879 3.804M12 3v6M12 9a3 3 0 100 6 3 3 0 000-6z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-stone-300 mt-3">—</p>
            </div>

            <!-- Total Orders -->
            <a href="{{ route('admin.order') }}"
                class="group bg-white rounded-2xl border border-stone-100 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Transaksi</span>
                        <p class="font-serif text-3xl font-bold text-stone-900 mt-1">{{ $transactionCount ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center group-hover:bg-amber-100 transition">
                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-stone-400 mt-3 group-hover:text-amber-600 transition font-medium">Lihat semua →</p>
            </a>
        </div>

        <!-- Charts -->
        <div>
            <div class="pb-4 mb-2 border-b border-stone-200">
                <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Analitik</span>
                <h2 class="font-serif text-xl font-bold text-stone-900 mt-1">Dashboard Penjualan</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5">

                <!-- Revenue Chart -->
                <div class="bg-white rounded-2xl shadow-sm p-5">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-lg font-semibold text-stone-700">Statistik Pendapatan</h2>
                        <select id="revenueFilter"
                            class="border border-stone-200 rounded-lg px-3 py-2 text-sm">
                            <option value="daily" selected>Harian</option>
                            <option value="weekly">Mingguan</option>
                            <option value="monthly">Bulanan</option>
                        </select>
                    </div>
                    <div class="relative h-[350px]">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Transaksi Chart -->
                <div class="bg-white rounded-2xl shadow-sm p-5">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-lg font-semibold text-stone-700">Statistik Transaksi</h2>
                        <select id="transaksiFilter"
                            class="border border-stone-200 rounded-lg px-3 py-2 text-sm">
                            <option value="daily" selected>Harian</option>
                            <option value="weekly">Mingguan</option>
                            <option value="monthly">Bulanan</option>
                        </select>
                    </div>
                    <div class="relative h-[350px]">
                        <canvas id="transaksiChart"></canvas>
                    </div>
                </div>

                <!-- Visitor Chart -->
                <div class="bg-white rounded-2xl shadow-sm p-5">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-lg font-semibold text-stone-700">Statistik Pengunjung</h2>
                        <select id="visitorFilter"
                            class="border border-stone-200 rounded-lg px-3 py-2 text-sm">
                            <option value="daily" selected>Harian</option>
                            <option value="weekly">Mingguan</option>
                            <option value="monthly">Bulanan</option>
                        </select>
                    </div>
                    <div class="relative h-[350px]">
                        <canvas id="visitorChart"></canvas>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @include('partials.charts')

</x-app-layout>