<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">

        <div class="flex gap-3 mb-6">
            <a href="{{ route('buyer.tracking.index', ['status' => 'dikemas']) }}"
                class="px-4 py-2 rounded-full text-sm font-medium bg-gray-100 hover:bg-green-100 hover:text-green-600 transition">
                Dikemas
            </a>

            <a href="{{ route('buyer.tracking.index', ['status' => 'dikirim']) }}"
                class="px-4 py-2 rounded-full text-sm font-medium bg-gray-100 hover:bg-blue-100 hover:text-blue-600 transition">
                Dikirim
            </a>

            <a href="{{ route('buyer.tracking.index', ['status' => 'selesai']) }}"
                class="px-4 py-2 rounded-full text-sm font-medium bg-gray-100 hover:bg-gray-900 hover:text-white transition">
                Selesai
            </a>
        </div>

        <div class="overflow-x-auto bg-white rounded-xl shadow-md border border-gray-100">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Resi</th>
                        <th class="px-6 py-4">Detail</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @foreach ($transactions as $trx)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $trx->id }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                    {{ $trx->shipping_status == 'dikemas' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $trx->shipping_status == 'dikirim' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $trx->shipping_status == 'selesai' ? 'bg-green-100 text-green-700' : '' }}">
                                {{ $trx->shipping_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $trx->resi ?? '-' }}
                        </td>

                        <td>
    @if($trx->last_history)
        <div>
            <strong>{{ $trx->last_history['date'] }}</strong><br>
            {{ $trx->last_history['desc'] }}
        </div>
    @else
        <p>Tidak ada data tracking</p>
    @endif
</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>