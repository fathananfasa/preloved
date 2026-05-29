<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">

        {{-- FILTER --}}
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

        {{-- WRAPPER (BIAR KAYAK TABEL) --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-4">

            <div class="flex flex-col gap-4">

                @foreach ($transactions as $trx)
                <div class="w-full flex gap-4 p-4 border border-gray-100 rounded-lg hover:bg-gray-50 transition">

                    {{-- GAMBAR --}}
                    <img src="{{ $trx->product->image }}"
                        class="w-24 h-24 object-cover rounded-lg">

                    {{-- INFO --}}
                    <div class="flex-1">

                        {{-- BARIS ATAS --}}
                        <div class="flex justify-between items-start">

                            <div>
                                <h2 class="font-semibold text-gray-800">
                                    {{ $trx->product->name }}
                                </h2>

                                <p class="text-sm text-gray-600">
                                    Total: Rp {{ number_format($trx->total, 0, ',', '.') }}
                                </p>
                            </div>

                            {{-- STATUS --}}
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                {{ $trx->shipping_status == 'dikemas' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $trx->shipping_status == 'dikirim' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $trx->shipping_status == 'selesai' ? 'bg-green-100 text-green-700' : '' }}">
                                {{ $trx->shipping_status }}
                            </span>
                        </div>

                        {{-- BARIS BAWAH --}}
                        <div class="mt-2 text-xs text-gray-500">
                            Resi: {{ $trx->resi ?? '-' }}
                        </div>

                        {{-- TRACKING --}}
                        {{-- TRACKING --}}
                        @if(isset($trx->tracking_data['last_history']))
                        <div class="mt-2 text-xs text-gray-600">
                            <strong>
                                {{ $trx->tracking_data['last_history']['date'] }}
                            </strong>
                            <br>

                            {{ $trx->tracking_data['last_history']['desc'] }}
                        </div>
                        @else
                        <p class="mt-2 text-xs text-gray-400">
                            Tidak ada data tracking
                        </p>
                        @endif

                    </div>

                </div>
                @endforeach

            </div>

        </div>

    </div>
</x-app-layout>