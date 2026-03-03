<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- BARIS ATAS -->
        <div class="flex items-center h-16">

            <!-- LOGO -->
            <div class="flex items-center">
                <a href="{{ auth()->check() ? route('dashboard') : route('home') }}">
                    <h1 class="text-xl font-bold text-blue-600">
                        Preloved Market
                    </h1>
                </a>
            </div>

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
                <div class="flex space-x-8">
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        Dashboard
                    </x-nav-link>

                    <x-nav-link :href="route('admin.products.index')" :active="request()->routeIs('admin.products.*')">
                        Products
                    </x-nav-link>

                    <x-nav-link :href="route('admin.products.create')" :active="request()->routeIs('admin.products.create')">
                        Tambah Produk
                    </x-nav-link>

                    <x-nav-link :href="route('admin.negotiations.index')" :active="request()->routeIs('admin.negotiations.*')">
                        Negosiasi
                    </x-nav-link>
                </div>
                @endif
                @endauth
            </div>

            <!-- KANAN -->
            <div class="hidden sm:flex items-center space-x-4 ml-auto">

                @guest
                <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
                    Login
                </x-nav-link>

                <a href="{{ route('register') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Daftar
                </a>
                @endguest

                @auth

                {{-- NOTIFIKASI HANYA BUYER --}}
                @if(auth()->user()->role === 'buyer')
                {{-- CART --}}
                <div class="relative">
                    <a href="{{ route('buyer.cart.index') }}" class="relative text-xl">
                        🛒
                        @php
                        $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                        @endphp

                        @if($cartCount > 0)
                        <span class="absolute -top-1 -right-2 bg-red-600 text-white text-xs rounded-full px-1">
                            {{ $cartCount }}
                        </span>
                        @endif
                    </a>
                </div>
                <div class="relative">
                    <button id="notifButton" class="relative text-xl">
                        🔔

                        @if(auth()->user()->unreadNotifications->count())
                        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-1">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                        @endif
                    </button>

                    <div id="notifDropdown"
                        class="hidden absolute right-0 mt-2 w-72 bg-white shadow-lg rounded-lg z-50">

                        @forelse(auth()->user()->notifications->take(5) as $notification)
                        <a href="{{ route('buyer.notification.redirect', $notification->id) }}"
                            class="block px-4 py-3 text-sm hover:bg-gray-100
                                       {{ $notification->read_at ? '' : 'bg-gray-50 font-semibold' }}">
                            {{ $notification->data['message'] }}
                        </a>
                        @empty
                        <div class="px-4 py-3 text-sm text-gray-500">
                            Tidak ada notifikasi
                        </div>
                        @endforelse

                    </div>
                </div>
                @endif

                <!-- DROPDOWN PROFILE -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">
                            {{ auth()->user()->name }}
                            <svg class="ml-2 h-4 w-4 fill-current" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        @if(auth()->user()->role === 'buyer')
                        <x-dropdown-link :href="route('buyer.addresses.index')">
                            Alamat
                        </x-dropdown-link>
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
                {{-- NOTIFIKASI MOBILE --}}
                @if(auth()->check() && auth()->user()->role === 'buyer')
                {{-- CART MOBILE --}}
                <div class="relative">
                    <a href="{{ route('buyer.cart.index') }}" class="relative text-xl p-2 border rounded-lg">
                        🛒
                        @php
                        $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                        @endphp

                        @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-1">
                            {{ $cartCount }}
                        </span>
                        @endif
                    </a>
                </div>
                <div class="relative">
                    <button id="notifButtonMobile" class="relative text-xl p-2 border rounded-lg">
                        🔔
                        @if(auth()->user()->unreadNotifications->count())
                        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs rounded-full px-1">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                        @endif
                    </button>

                    <div id="notifDropdownMobile"
                        class="hidden absolute right-0 mt-2 w-72 bg-white shadow-lg rounded-lg z-50">
                        @forelse(auth()->user()->notifications->take(5) as $notification)
                        <a href="{{ route('buyer.notification.redirect', $notification->id) }}"
                            class="block px-4 py-3 text-sm hover:bg-gray-100 {{ $notification->read_at ? '' : 'bg-gray-50 font-semibold' }}">
                            {{ $notification->data['message'] }}
                        </a>
                        @empty
                        <div class="px-4 py-3 text-sm text-gray-500">
                            Tidak ada notifikasi
                        </div>
                        @endforelse
                    </div>
                </div>
                @endif

                {{-- HAMBURGER --}}
                <button @click="open = !open"
                    class="sm:hidden flex flex-col justify-center items-center w-10 h-10">

                    <span class="block w-6 h-0.5 bg-gray-700 transition-all duration-300"
                        :class="open ? 'rotate-45 translate-y-1.5' : ''"></span>

                    <span class="block w-6 h-0.5 bg-gray-700 my-1 transition-all duration-300"
                        :class="open ? 'opacity-0' : ''"></span>

                    <span class="block w-6 h-0.5 bg-gray-700 transition-all duration-300"
                        :class="open ? '-rotate-45 -translate-y-1.5' : ''"></span>
                </button>
            </div>

        </div>


        <!-- MOBILE MENU -->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-5"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-5"
            class="sm:hidden absolute top-16 left-0 w-full bg-white z-40 px-4 py-4 space-y-2 shadow-lg">

            @guest
            <x-responsive-nav-link :href="route('login')">Login</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('register')">Daftar</x-responsive-nav-link>
            @endguest

            @auth
            @if(auth()->user()->role === 'admin')
            <x-responsive-nav-link :href="route('admin.dashboard')">Dashboard</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.products.index')">Products</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.products.create')">Tambah Produk</x-responsive-nav-link>
            @endif



            {{-- PROFILE MOBILE --}}
            <div class="mt-2 border-t pt-2">
                <x-responsive-nav-link :href="route('profile.edit')">Profile</x-responsive-nav-link>
                @if(auth()->user()->role === 'buyer')
                <x-responsive-nav-link :href="route('buyer.addresses.index')">Alamat</x-responsive-nav-link>
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

<script>
    // Desktop notif toggle
    document.getElementById('notifButton')?.addEventListener('click', function() {
        document.getElementById('notifDropdown').classList.toggle('hidden');
    });

    // Mobile notif toggle
    document.getElementById('notifButtonMobile')?.addEventListener('click', function() {
        document.getElementById('notifDropdownMobile').classList.toggle('hidden');
    });
</script>