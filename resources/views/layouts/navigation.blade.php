<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white border-b border-stone-100 overflow-visible">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- BARIS ATAS -->
        <div class="flex items-center h-16">

            <!-- LOGO -->
            <a href="{{ auth()->check() ? route('dashboard') : route('home') }}">
                <span class="font-serif italic text-xl text-amber-700 tracking-tight">Preloved<span class="text-stone-900 not-italic font-bold">.id</span></span>
            </a>

            <!-- TENGAH -->
            <div class="hidden sm:flex flex-1 justify-center px-6">
                @guest
                @include('partials.search-bar')
                @endguest

                @auth
                @if(auth()->user()->role === 'buyer')
                @include('partials.search-bar')
                @endif

                @if(auth()->user()->role === 'admin')
                <div class="flex space-x-6">
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                        Products
                    </x-nav-link>
                    <x-nav-link :href="route('admin.negotiations.index')" :active="request()->routeIs('admin.negotiations.*')">
                        Negosiasi
                    </x-nav-link>
                    <x-nav-link :href="route('admin.order')" :active="request()->routeIs('admin.order')">
                        Orderan
                    </x-nav-link>
                    <x-nav-link :href="route('admin.pengguna')" :active="request()->routeIs('admin.pengguna')">
                        Pengguna
                    </x-nav-link>
                </div>
                @endif
                @endauth
            </div>

            <!-- KANAN -->
            <div class="hidden sm:flex items-center space-x-3 ml-auto">

                @guest
                <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
                    Login
                </x-nav-link>
                <a href="{{ route('register') }}"
                    class="px-4 py-2 bg-amber-400 text-stone-900 text-sm font-semibold rounded-xl hover:bg-amber-300 transition duration-200 shadow-sm">
                    Daftar
                </a>
                @endguest

                @auth

                @if(auth()->user()->role === 'buyer')

                {{-- CART --}}
                <div class="relative">
                    <a href="{{ route('buyer.cart.index') }}"
                        class="relative flex items-center justify-center w-9 h-9 rounded-xl border border-stone-200 hover:border-amber-400 hover:bg-amber-50 transition duration-200 text-base">
                        🛒
                        @php $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity'); @endphp
                        @if($cartCount > 0)
                        <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-0.5">
                            {{ $cartCount }}
                        </span>
                        @endif
                    </a>
                </div>

                {{-- NOTIFIKASI --}}
                <div class="relative">
                    <button id="notifButton"
                        class="relative flex items-center justify-center w-9 h-9 rounded-xl border border-stone-200 hover:border-amber-400 hover:bg-amber-50 transition duration-200 text-base">
                        🔔
                        @if(auth()->user()->unreadNotifications->count())
                        <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-0.5">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                        @endif
                    </button>

                    <div id="notifDropdown"
    class="hidden absolute right-0 mt-2 w-72 bg-white border border-stone-100 shadow-xl rounded-2xl z-50 overflow-y-auto max-h-96">
    @forelse(auth()->user()->notifications->take(5) as $notification)
                        <a href="{{ route('buyer.notification.redirect', $notification->id) }}"
                            class="block px-4 py-3 text-sm text-stone-700 hover:bg-amber-50 border-b border-stone-50 last:border-0 transition
                                {{ $notification->read_at ? '' : 'bg-stone-50 font-semibold' }}">
                            {{ $notification->data['message'] }}
                        </a>
                        @empty
                        <div class="px-4 py-4 text-sm text-stone-400 text-center">
                            Tidak ada notifikasi
                        </div>
                        @endforelse
                    </div>
                </div>

                @endif

                <!-- DROPDOWN PROFILE -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-stone-600 hover:text-stone-900 rounded-xl hover:bg-stone-50 transition duration-200">
                            {{ auth()->user()->name }}
                            <svg class="h-4 w-4 fill-current text-stone-400" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                        @if(auth()->user()->role === 'buyer')
                        <x-dropdown-link :href="route('buyer.addresses.index')">Alamat</x-dropdown-link>
                        <x-dropdown-link :href="route('buyer.tracking.index')">Riwayat Pesanan</x-dropdown-link>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>

                @endauth
            </div>

            <!-- HAMBURGER MOBILE -->
            <div class="flex items-center sm:hidden ml-auto space-x-2">

                @if(auth()->check() && auth()->user()->role === 'buyer')

                {{-- CART MOBILE --}}
                <div class="relative">
                    <a href="{{ route('buyer.cart.index') }}"
                        class="relative flex items-center justify-center w-9 h-9 rounded-xl border border-stone-200 text-base">
                        🛒
                        @php $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity'); @endphp
                        @if($cartCount > 0)
                        <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-0.5">
                            {{ $cartCount }}
                        </span>
                        @endif
                    </a>
                </div>

                {{-- NOTIFIKASI MOBILE --}}
                <div class="relative">
                    <button id="notifButtonMobile"
                        class="relative flex items-center justify-center w-9 h-9 rounded-xl border border-stone-200 text-base">
                        🔔
                        @if(auth()->user()->unreadNotifications->count())
                        <span class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-0.5">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                        @endif
                    </button>

                    <div id="notifDropdownMobile"
    class="hidden absolute right-0 mt-2 w-72 bg-white border border-stone-100 shadow-xl rounded-2xl z-50 overflow-y-auto max-h-96">
    @forelse(auth()->user()->notifications->take(5) as $notification)
                        <a href="{{ route('buyer.notification.redirect', $notification->id) }}"
                            class="block px-4 py-3 text-sm text-stone-700 hover:bg-amber-50 border-b border-stone-50 last:border-0
                                {{ $notification->read_at ? '' : 'bg-stone-50 font-semibold' }}">
                            {{ $notification->data['message'] }}
                        </a>
                        @empty
                        <div class="px-4 py-4 text-sm text-stone-400 text-center">
                            Tidak ada notifikasi
                        </div>
                        @endforelse
                    </div>
                </div>

                @endif

                {{-- HAMBURGER --}}
                <button @click="open = !open"
                    class="flex flex-col justify-center items-center w-10 h-10 rounded-xl border border-stone-200 hover:border-amber-400 hover:bg-amber-50 transition duration-200">
                    <span class="block w-5 h-0.5 bg-stone-700 transition-all duration-300"
                        :class="open ? 'rotate-45 translate-y-1.5' : ''"></span>
                    <span class="block w-5 h-0.5 bg-stone-700 my-1 transition-all duration-300"
                        :class="open ? 'opacity-0' : ''"></span>
                    <span class="block w-5 h-0.5 bg-stone-700 transition-all duration-300"
                        :class="open ? '-rotate-45 -translate-y-1.5' : ''"></span>
                </button>
            </div>

        </div>

        <!-- MOBILE MENU -->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-3"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-3"
            class="sm:hidden absolute top-16 left-0 w-full bg-white z-40 px-4 py-3 space-y-1 shadow-xl border-t border-stone-100">

            @guest
            <x-responsive-nav-link :href="route('login')">Login</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('register')">Daftar</x-responsive-nav-link>
            @endguest

            @auth
            @if(auth()->user()->role === 'admin')
<x-responsive-nav-link
    :href="route('admin.dashboard')"
    :active="request()->routeIs('admin.dashboard')">
    Dashboard
</x-responsive-nav-link>

<x-responsive-nav-link
    :href="route('admin.products.index')"
    :active="request()->routeIs('admin.products.*')">
    Products
</x-responsive-nav-link>

<x-responsive-nav-link
    :href="route('admin.negotiations.index')"
    :active="request()->routeIs('admin.negotiations.*')">
    Negosiasi
</x-responsive-nav-link>

<x-responsive-nav-link
    :href="route('admin.order')"
    :active="request()->routeIs('admin.order')">
    Orderan
</x-responsive-nav-link>

<x-responsive-nav-link
    :href="route('admin.pengguna')"
    :active="request()->routeIs('admin.pengguna')">
    Pengguna
</x-responsive-nav-link>
@endif

            <div class="border-t border-stone-100 pt-2 mt-2 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">Profile</x-responsive-nav-link>
                @if(auth()->user()->role === 'buyer')
                <x-responsive-nav-link :href="route('buyer.addresses.index')">Alamat</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('buyer.tracking.index')">Riwayat Pesanan</x-responsive-nav-link>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
            @endauth
        </div>

        <!-- SEARCH MOBILE -->
        <div class="sm:hidden pb-3">
            @guest
            @include('partials.search-bar')
            @endguest
            @auth
            @if(auth()->user()->role === 'buyer')
            @include('partials.search-bar')
            @endif
            @endauth
        </div>

    </div>
</nav>
@include('partials.nav')