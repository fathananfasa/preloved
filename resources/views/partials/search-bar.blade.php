<form
    action="{{ route('search') }}"
    method="GET"
    class="w-full"
>
    <div class="py-3">
        <div class="flex justify-center">
            <div class="relative w-full max-w-2xl">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-stone-400 pointer-events-none"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input
                    type="text"
                    name="search"
                    placeholder="Cari produk favoritmu..."
                    class="w-full pl-11 pr-5 py-2.5 rounded-xl border border-stone-200 bg-white text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent shadow-sm transition">
            </div>
        </div>
    </div>
</form>