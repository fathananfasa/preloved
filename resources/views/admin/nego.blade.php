<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-6">

        <!-- Header -->
        <div class="pb-5 border-b border-stone-200">
            <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Admin</span>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900 mt-1">Negosiasi</h1>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl border border-stone-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">

                    <thead>
                        <tr class="bg-stone-50 border-b border-stone-100">
                            <th class="px-4 py-3.5 text-left text-[10px] uppercase tracking-widest text-stone-400 font-medium">Produk</th>
                            <th class="px-4 py-3.5 text-left text-[10px] uppercase tracking-widest text-stone-400 font-medium">Buyer</th>
                            <th class="px-4 py-3.5 text-center text-[10px] uppercase tracking-widest text-stone-400 font-medium">Harga Nego</th>
                            <th class="px-4 py-3.5 text-center text-[10px] uppercase tracking-widest text-stone-400 font-medium">Status</th>
                            <th class="px-4 py-3.5 text-center text-[10px] uppercase tracking-widest text-stone-400 font-medium">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-stone-50">
                        @foreach ($negotiations as $nego)
                        <tr class="hover:bg-stone-50 transition duration-150">

                            <td class="px-4 py-3.5 font-medium text-stone-900">
                                {{ $nego->product->name }}
                            </td>

                            <td class="px-4 py-3.5 text-stone-500 text-xs">
                                {{ $nego->user->name }}
                            </td>

                            <td class="px-4 py-3.5 text-center">
                                <span class="font-serif font-bold text-amber-700">
                                    Rp {{ number_format($nego->final_price, 0, ',', '.') }}
                                </span>
                            </td>

                            <td class="px-4 py-3.5 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wide
                                    {{ $nego->status === 'pending'  ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $nego->status === 'accepted' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ $nego->status === 'rejected' ? 'bg-red-100 text-red-500' : '' }}">
                                    {{ ucfirst($nego->status) }}
                                </span>
                            </td>

                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-center gap-2">
                                    @if ($nego->status === 'pending')
                                    <form action="{{ route('admin.negotiations.accept', $nego->id) }}" method="POST">
                                        @csrf
                                        <button class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 border border-emerald-200 hover:border-emerald-400 px-3 py-1.5 rounded-xl transition duration-200">
                                            Terima
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.negotiations.reject', $nego->id) }}" method="POST">
                                        @csrf
                                        <button class="text-xs font-semibold text-red-400 hover:text-red-600 border border-stone-200 hover:border-red-300 px-3 py-1.5 rounded-xl transition duration-200">
                                            Tolak
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-xs text-stone-300 font-medium">Selesai</span>
                                    @endif
                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
        <div class="mt-8">
            {{ $negotiations->links() }}
        </div>
    </div>
</x-app-layout>