<section>
    <header class="mb-6">
        <h2 class="font-serif text-xl font-bold text-stone-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-stone-500">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4 space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" class="text-xs uppercase tracking-widest text-stone-400 font-medium mb-1" />
            <x-text-input id="name" name="name" type="text"
                class="mt-1 block w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-xs uppercase tracking-widest text-stone-400 font-medium mb-1" />
            <x-text-input id="email" name="email" type="email"
                class="mt-1 block w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-1.5" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 rounded-xl bg-amber-50 border border-amber-100">
                    <p class="text-sm text-stone-700">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification"
                            class="underline text-amber-700 hover:text-amber-600 font-medium focus:outline-none focus:ring-2 focus:ring-amber-400 rounded">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-emerald-600 font-medium">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-1">
            <button type="submit"
                class="bg-stone-900 hover:bg-stone-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold tracking-wide transition duration-200 shadow hover:shadow-md">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-600 font-medium"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>