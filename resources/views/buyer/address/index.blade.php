<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-12">

        <div class="flex items-end justify-between mb-6 pb-5 border-b border-stone-200">
            <div>
                <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Akun</span>
                <h2 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900 mt-1">Alamat Saya</h2>
            </div>
            <button
                onclick="openModal()"
                class="bg-stone-900 hover:bg-stone-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold tracking-wide transition duration-200 shadow hover:shadow-md">
                + Tambah Alamat
            </button>
        </div>

        <div class="space-y-3">
            @foreach ($addresses as $address)
            <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-4 sm:p-5">
                <div class="flex items-start justify-between gap-4">

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <p class="font-semibold text-stone-900 text-sm">
                                {{ $address->receiver_name }}
                            </p>
                            @if($address->is_default)
                            <span class="text-[10px] uppercase tracking-wide font-semibold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">
                                Default
                            </span>
                            @endif
                        </div>
                        <p class="text-xs text-stone-500 mb-1">{{ $address->phone }}</p>
                        <p class="text-xs text-stone-500 leading-relaxed">
                            {{ $address->address }},
                            {{ $address->k_name }},
                            {{ $address->c_name }},
                            {{ $address->p_name }},
                            {{ $address->postal_code }}
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row items-end sm:items-center gap-2 shrink-0">
                        <button
                            type="button"
                            onclick='openEditModal(@json($address))'
                            class="text-xs font-semibold text-stone-600 hover:text-amber-700 border border-stone-200 hover:border-amber-400 px-3 py-1.5 rounded-xl transition duration-200">
                            Edit
                        </button>
                        <form method="POST" action="{{ route('buyer.addresses.destroy', $address) }}">
                            @csrf
                            @method('DELETE')
                            <button
                                class="text-xs font-semibold text-red-400 hover:text-red-600 border border-stone-200 hover:border-red-300 px-3 py-1.5 rounded-xl transition duration-200">
                                Hapus
                            </button>
                        </form>
                    </div>

                </div>
            </div>
            @endforeach
        </div>

    </div>

    @include('modals.address')
</x-app-layout>