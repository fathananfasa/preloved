<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md bg-white p-8 rounded-xl shadow">
            <h1 class="text-2xl font-semibold text-center text-gray-800">
                Buat Akun
            </h1>
            <p class="text-sm text-center text-gray-500 mt-2">
                Daftar untuk mulai menggunakan aplikasi
            </p>

            <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <x-input-label for="name" value="Nama Lengkap" />
                    <x-text-input
                        id="name"
                        class="block mt-1 w-full rounded-lg"
                        type="text"
                        name="name"
                        :value="old('name')"
                        required
                        autofocus
                    />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input
                        id="email"
                        class="block mt-1 w-full rounded-lg"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="password" value="Password" />
                    <x-text-input
                        id="password"
                        class="block mt-1 w-full rounded-lg"
                        type="password"
                        name="password"
                        required
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                    <x-text-input
                        id="password_confirmation"
                        class="block mt-1 w-full rounded-lg"
                        type="password"
                        name="password_confirmation"
                        required
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                <div class="pt-4">
                    <x-primary-button class="w-full justify-center">
                        Daftar
                    </x-primary-button>

                    <p class="text-sm text-center text-gray-600 mt-4">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">
                            Login
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
