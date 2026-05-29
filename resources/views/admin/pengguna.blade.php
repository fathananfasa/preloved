<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-6">

        <!-- Header -->
        <div class="pb-5 border-b border-stone-200">
            <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Admin</span>
            <h1 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900 mt-1">Manajemen Pengguna</h1>
        </div>

        <!-- Table lebih narrow -->
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-2xl border border-stone-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">

                        <thead>
                            <tr class="bg-stone-50 border-b border-stone-100">
                                <th class="px-4 py-3.5 text-left text-[10px] uppercase tracking-widest text-stone-400 font-medium">Nama</th>
                                <th class="px-4 py-3.5 text-left text-[10px] uppercase tracking-widest text-stone-400 font-medium">Email</th>
                                <th class="px-4 py-3.5 text-left text-[10px] uppercase tracking-widest text-stone-400 font-medium">Tanggal Daftar</th>
                                <th class="px-4 py-3.5 text-left text-[10px] uppercase tracking-widest text-stone-400 font-medium">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-stone-50">
                            @forelse ($users as $user)

                            <tr class="hover:bg-stone-50 transition duration-150">

                                <td class="px-4 py-3.5 font-medium text-stone-900">
                                    {{ $user->name }}
                                </td>

                                <td class="px-4 py-3.5 text-stone-500 text-xs font-mono">
                                    {{ $user->email }}
                                </td>

                                <td class="px-4 py-3.5 text-stone-500 text-xs">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>

                                <td class="px-4 py-3.5">
                                    <div x-data="{ open: false }" class="flex items-center gap-2">

                                        <button
                                            @click="open = true"
                                            class="text-xs font-semibold text-stone-600 hover:text-amber-700 border border-stone-200 hover:border-amber-400 px-3 py-1.5 rounded-xl transition duration-200">
                                            Detail
                                        </button>

                                        <form
                                            action="{{ route('admin.pengguna.delete', $user->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin hapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="text-xs font-semibold text-red-400 hover:text-red-600 border border-stone-200 hover:border-red-300 px-3 py-1.5 rounded-xl transition duration-200">
                                                Hapus
                                            </button>
                                        </form>

                                        @include('modals.pengguna')
                                    </div>
                                </td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="4">
                                    <div class="flex flex-col items-center justify-center py-16 text-center">
                                        <div class="w-14 h-14 rounded-2xl bg-stone-100 flex items-center justify-center text-2xl mb-3">
                                            👤
                                        </div>
                                        <p class="font-serif text-base font-bold text-stone-700 mb-1">Tidak ada pengguna</p>
                                        <p class="text-xs text-stone-400">Belum ada pengguna yang terdaftar.</p>
                                    </div>
                                </td>
                            </tr>

                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>