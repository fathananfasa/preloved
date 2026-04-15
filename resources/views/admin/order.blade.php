<x-app-layout>
    <div class="flex gap-4 mb-4">

        <a href="{{ route('buyer.tracking.index') }}"
           class="{{ !$status ? 'font-bold text-blue-600' : '' }}">
            Semua
        </a>

        <a href="{{ route('buyer.tracking.index', ['shipping_status' => 'dikemas']) }}"
           class="{{ $status == 'dikemas' ? 'font-bold text-blue-600' : '' }}">
            Dikemas
        </a>

        <a href="{{ route('buyer.tracking.index', ['shipping_status' => 'dikirim']) }}"
           class="{{ $status == 'dikirim' ? 'font-bold text-blue-600' : '' }}">
            Dikirim
        </a>

        <a href="{{ route('buyer.tracking.index', ['shipping_status' => 'selesai']) }}"
           class="{{ $status == 'selesai' ? 'font-bold text-blue-600' : '' }}">
            Selesai
        </a>

    </div>

    <div class="mt-4">
        @foreach ($transactions as $trx)
            <div class="border p-3 mb-2">
                <p>ID: {{ $trx->id }}</p>
                <p>Status: {{ $trx->shipping_status }}</p>
                <p>Total: {{ $trx->total }}</p>
            </div>
        @endforeach
    </div>
</x-app-layout>