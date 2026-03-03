<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">

        <h1 class="text-2xl font-bold mb-6">Daftar Negosiasi</h1>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-4 text-left">Produk</th>
                        <th class="p-4 text-left">Buyer</th>
                        <th class="p-4">Harga Nego</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($negotiations as $nego)
                    <tr class="border-t">
                        <td class="p-4">{{ $nego->product->name }}</td>
                        <td class="p-4">{{ $nego->buyer->name }}</td>
                        <td class="p-4 text-center font-semibold">
                            Rp {{ number_format($nego->offer_price, 0, ',', '.') }}
                        </td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 rounded-full text-xs
                                {{ $nego->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $nego->status === 'accepted' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $nego->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                            ">
                                {{ ucfirst($nego->status) }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            @if ($nego->status === 'pending')
                                <form action="{{ route('admin.negotiations.accept', $nego->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-green-600 font-semibold">Terima</button>
                                </form>

                                <form action="{{ route('admin.negotiations.reject', $nego->id) }}" method="POST" class="inline ml-3">
                                    @csrf
                                    <button class="text-red-600 font-semibold">Tolak</button>
                                </form>
                            @else
                                <span class="text-gray-400">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
