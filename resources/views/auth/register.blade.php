<x-guest-layout>
    <div class="min-h-screen bg-[#f9f7f4] grid grid-cols-1 lg:grid-cols-2">

        <!-- LEFT PANEL -->
        <div class="hidden lg:flex items-center justify-center bg-stone-900 text-white px-16">
            <div class="max-w-md">

                <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">
                    Preloved.id
                </span>

                <h1 class="font-serif text-4xl font-bold leading-tight mt-3">
                    Bergabung<br>
                    <span class="italic text-amber-300">Sekarang</span>
                </h1>

                <p class="mt-4 text-stone-400 text-sm font-light leading-relaxed">
                    Temukan barang preloved berkualitas dan mulai pengalaman belanja yang lebih mudah.
                </p>

                <div class="mt-8 space-y-3">

                    <div class="flex items-center gap-3 text-sm text-stone-300">
                        <span class="w-6 h-6 rounded-lg bg-amber-400/20 text-amber-400 flex items-center justify-center text-xs">
                            ✔
                        </span>
                        Ribuan produk pilihan
                    </div>

                    <div class="flex items-center gap-3 text-sm text-stone-300">
                        <span class="w-6 h-6 rounded-lg bg-amber-400/20 text-amber-400 flex items-center justify-center text-xs">
                            ✔
                        </span>
                        Sistem negosiasi aman
                    </div>

                    <div class="flex items-center gap-3 text-sm text-stone-300">
                        <span class="w-6 h-6 rounded-lg bg-amber-400/20 text-amber-400 flex items-center justify-center text-xs">
                            ✔
                        </span>
                        Pembayaran terpercaya
                    </div>

                </div>

                <div class="mt-12 pt-8 border-t border-stone-800">
                    <p class="font-serif italic text-amber-300 text-lg">
                        Preloved.id
                    </p>
                </div>

            </div>
        </div>


        <!-- RIGHT PANEL -->
        <div class="flex items-center justify-center px-4 py-12">

            <div class="w-full max-w-md">

                <!-- Logo Mobile -->
                <div class="lg:hidden text-center mb-8">
                    <p class="font-serif italic text-amber-700 text-2xl font-bold">
                        Preloved<span class="text-stone-900 not-italic">.id</span>
                    </p>
                </div>


                <div class="bg-white rounded-2xl border border-stone-100 shadow-sm px-6 sm:px-8 pt-7 pb-8">

                    <!-- Header -->
                    <div class="mb-6">
                        <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">
                            Akun
                        </span>

                        <h2 class="font-serif text-2xl font-bold text-stone-900 mt-1">
                            Daftar
                        </h2>

                        <p class="text-sm text-stone-500 mt-1 font-light">
                            Buat akun untuk mulai berbelanja.
                        </p>
                    </div>


                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <!-- Nama -->
                        <div>
                            <x-input-label
                                for="name"
                                value="Nama Lengkap"
                                class="text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5"
                            />

                            <x-text-input
                                id="name"
                                class="mt-1 block w-full rounded-xl border border-stone-200 px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                                type="text"
                                name="name"
                                :value="old('name')"
                                required
                                autofocus
                            />

                            <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs"/>
                        </div>

                        <!-- Email -->
                        <div>
                            <x-input-label
                                for="email"
                                value="Email"
                                class="text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5"
                            />

                            <x-text-input
                                id="email"
                                class="mt-1 block w-full rounded-xl border border-stone-200 px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                                type="email"
                                name="email"
                                :value="old('email')"
                                required
                            />

                            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs"/>
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label
                                for="password"
                                value="Password"
                                class="text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5"
                            />

                            <x-text-input
                                id="password"
                                class="mt-1 block w-full rounded-xl border border-stone-200 px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                                type="password"
                                name="password"
                                required
                            />

                            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs"/>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                            <x-input-label
                                for="password_confirmation"
                                value="Konfirmasi Password"
                                class="text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5"
                            />

                            <x-text-input
                                id="password_confirmation"
                                class="mt-1 block w-full rounded-xl border border-stone-200 px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                                type="password"
                                name="password_confirmation"
                                required
                            />

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs"/>
                        </div>

                        <!-- Button -->
                        <div class="pt-1">
                            <button
                                type="submit"
                                class="w-full bg-stone-900 hover:bg-stone-700 text-white py-3 rounded-xl text-sm font-semibold tracking-wide transition duration-200 shadow hover:shadow-md"
                            >
                                Daftar
                            </button>
                        </div>

                        <!-- Divider -->
                        <div class="flex items-center gap-3 py-1">
                            <div class="flex-1 h-px bg-stone-100"></div>
                            <span class="text-[10px] uppercase tracking-widest text-stone-400">
                                atau
                            </span>
                            <div class="flex-1 h-px bg-stone-100"></div>
                        </div>

                        <!-- Footer -->
                        <p class="text-center text-xs text-stone-500">
                            Sudah punya akun?
                            <a href="{{ route('login') }}"
                               class="text-amber-700 font-semibold hover:text-amber-600 transition">
                                Masuk
                            </a>
                        </p>

                    </form>

                </div>

            </div>

        </div>

    </div>
</x-guest-layout>