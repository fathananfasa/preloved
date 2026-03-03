<x-app-layout>
  

    <div class="p-4 md:p-6 space-y-6">

        <!-- Statistik Ringkas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Produk -->
            <div class="bg-white rounded-lg shadow p-5 flex items-center justify-between hover:shadow-lg transition">
                <div>
                    <p class="text-gray-500 text-sm">Total Produk</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $productCount ?? 0 }}</p>
                </div>
                <div class="text-blue-600">
                    <a href="{{ route('admin.products.index') }}">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 7h18M3 12h18M3 17h18"></path>
                    </svg>
                    </a>
                    
                </div>
            </div>

            <!-- Total Kategori -->
            <div class="bg-white rounded-lg shadow p-5 flex items-center justify-between hover:shadow-lg transition">
                <div>
                    <p class="text-gray-500 text-sm">Total Kategori</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $categoryCount ?? 0 }}</p>
                </div>
                <div class="text-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow p-5 flex items-center justify-between hover:shadow-lg transition">
                <div>
                    <p class="text-gray-500 text-sm">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $userCount ?? 0 }}</p>
                </div>
                <div class="text-yellow-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.121 17.804A12.003 12.003 0 0112 15c3.866 0 7.223 1.5 9.879 3.804M12 3v6M12 9a3 3 0 100 6 3 3 0 000-6z"></path>
                    </svg>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-white rounded-lg shadow p-5 flex items-center justify-between hover:shadow-lg transition">
                <div>
                    <p class="text-gray-500 text-sm">Total Orders</p>
                    <p class="text-2xl font-semibold text-gray-800">{{ $orderCount ?? 0 }}</p>
                </div>
                <div class="text-red-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 3h18v18H3V3z"></path>
                    </svg>
                </div>
            </div>
        </div>  

    </div>
</x-app-layout>