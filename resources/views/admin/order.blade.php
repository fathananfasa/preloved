<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-6">

        <!-- Header -->
        <div class="flex items-end justify-between pb-5 border-b border-stone-200">
            <div>
                <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Admin</span>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900 mt-1">Orderan</h1>
            </div>
            <a href="{{ route('admin.export', request()->query()) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 border border-stone-200 hover:border-amber-400 text-stone-600 hover:text-amber-700 rounded-xl text-sm font-semibold transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                </svg>
                Export CSV
            </a>
        </div>

        <!-- Filter -->
        @include('partials.filter')

        <!-- Table -->
        <div class="bg-white rounded-2xl border border-stone-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">

                    <thead>
                        <tr class="bg-stone-50 border-b border-stone-100">
                            <th class="px-4 py-3.5 text-[10px] uppercase tracking-widest text-stone-400 font-medium">Nama</th>
                            <th class="px-4 py-3.5 text-[10px] uppercase tracking-widest text-stone-400 font-medium">Status</th>
                            <th class="px-4 py-3.5 text-[10px] uppercase tracking-widest text-stone-400 font-medium">Resi</th>
                            <th class="px-4 py-3.5 text-[10px] uppercase tracking-widest text-stone-400 font-medium">Total</th>
                            <th class="px-4 py-3.5 text-[10px] uppercase tracking-widest text-stone-400 font-medium">Penerima</th>
                            <th class="px-4 py-3.5 text-[10px] uppercase tracking-widest text-stone-400 font-medium">Phone</th>
                            <th class="px-4 py-3.5 text-[10px] uppercase tracking-widest text-stone-400 font-medium">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-stone-50">
                        @foreach ($transactions as $trx)
                        <tr class="hover:bg-stone-50 transition duration-150">

                            <td class="px-4 py-3.5 font-medium text-stone-900">
                                {{ $trx->user->name }}
                            </td>

                            <td class="px-4 py-3.5">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wide
                                    {{ $trx->status == 'waiting_payment' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $trx->status == 'paid' ? 'bg-emerald-100 text-emerald-700' : '' }}">
                                    {{ $trx->status }}
                                </span>
                            </td>

                            <td class="px-4 py-3.5 text-stone-500 text-xs font-mono">
                                {{ $trx->resi ?? '—' }}
                            </td>

                            <td class="px-4 py-3.5">
                                <span class="font-serif font-bold text-amber-700">
                                    Rp {{ number_format($trx->total, 0, ',', '.') }}
                                </span>
                            </td>

                            <td class="px-4 py-3.5 text-stone-600 text-xs">
                                {{ $trx->receiver_name }}
                            </td>

                            <td class="px-4 py-3.5 text-stone-500 text-xs font-mono">
                                {{ $trx->phone }}
                            </td>

                            <td class="px-4 py-3.5">
                                <button
                                    type="button"
                                    onclick='openModal(@json($trx->id), @json($trx->resi))'
                                    class="text-xs font-semibold text-stone-600 hover:text-amber-700 border border-stone-200 hover:border-amber-400 px-3 py-1.5 rounded-xl transition duration-200">
                                    Edit Resi
                                </button>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

    </div>

    @include('modals.resi')
</x-app-layout>