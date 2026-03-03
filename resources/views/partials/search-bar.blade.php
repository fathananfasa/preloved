<form 
    action="
    @guest
        {{ route('home') }}
    @else
        @if(auth()->user()->role === 'buyer')
            {{ route('buyer.search') }}
        @else
            {{ route('admin.products.index') }}
        @endif
    @endguest
    " 
    method="GET" 
    class="w-full"
>
    <div class="py-4">
        <div class="flex justify-center">
            <input
                type="text"
                name="search"
                placeholder="Cari produk favoritmu..."
                class="w-full max-w-2xl px-5 py-3 rounded-full border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm">
        </div>
    </div>
</form>