<x-app-layout>
<div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow">
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-bold">Alamat Saya</h2>
        <a href="{{ route('buyer.addresses.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Tambah Alamat
        </a>
    </div>

    @foreach ($addresses as $address)
    <div class="border p-4 rounded-lg mb-3">
        <div class="flex justify-between">
            <div>
                <p class="font-semibold">
                    {{ $address->receiver_name }}
                    @if($address->is_default)
                        <span class="ml-2 text-xs bg-green-100 text-green-700 px-2 py-1 rounded">
                            Default
                        </span>
                    @endif
                </p>
                <p class="text-sm">{{ $address->phone }}</p>
                <p class="text-sm text-gray-600">
                    {{ $address->address }},
                    {{ $address->k_name }},
                    {{ $address->c_name }},
                    {{ $address->p_name }},
                    {{ $address->postal_code }}
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('buyer.addresses.edit', $address) }}"
                   class="text-blue-600">Edit</a>

                <form method="POST"
                      action="{{ route('buyer.addresses.destroy', $address) }}">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600">Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
</x-app-layout>
