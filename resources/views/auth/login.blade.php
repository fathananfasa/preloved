<x-guest-layout>
    <div class="min-h-screen bg-gray-50 grid grid-cols-1 lg:grid-cols-2">

        <!-- LEFT PANEL -->
        <div class="hidden lg:flex items-center justify-center bg-indigo-600 text-white px-16">
            <div class="max-w-md">
                <h1 class="text-3xl font-bold leading-tight">
                    Selamat Datang di Marketplace Kamu
                </h1>

                <p class="mt-4 text-indigo-100">
                    Jual beli lebih mudah. Aman. Cepat. Terpercaya.
                </p>

                <ul class="mt-6 space-y-3 text-indigo-100 text-sm">
                    <li>✔ Ribuan produk pilihan</li>
                    <li>✔ Sistem negosiasi aman</li>
                    <li>✔ Pembayaran terpercaya</li>
                </ul>
            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="flex items-center justify-center px-4">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-gray-100">

                <!-- Header -->
                <div class="px-6 pt-6 pb-4 text-center">
                    <h2 class="text-xl font-bold text-gray-800">
                        Masuk ke Akun Kamu
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Belanja dan kelola akun dengan mudah
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status
                    class="mx-6 mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm"
                    :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="px-6 pb-6 space-y-4">
                    @csrf

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-sm text-gray-600" />
                        <x-text-input
                            id="email"
                            class="mt-1 block w-full rounded-xl border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-sm text-gray-600" />
                        <x-text-input
                            id="password"
                            class="mt-1 block w-full rounded-xl border-gray-300 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm" />
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between text-sm">
                        <label for="remember_me" class="inline-flex items-center gap-2">
                            <input id="remember_me" type="checkbox"
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                   name="remember">
                            <span class="text-gray-600">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-indigo-600 hover:text-indigo-800">
                                Lupa password?
                            </a>
                        @endif
                    </div>

                    <!-- Button -->
                    <x-primary-button
                        class="w-full justify-center py-3 rounded-xl text-sm font-semibold shadow-md hover:shadow-lg transition">
                        Log in
                    </x-primary-button>

                    <!-- Footer -->
                    <p class="text-center text-sm text-gray-500 pt-2">
                        Belum punya akun?
                        <a href="#" class="text-indigo-600 font-medium hover:underline">
                            Daftar sekarang
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
