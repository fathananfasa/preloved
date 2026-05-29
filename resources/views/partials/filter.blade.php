<form method="GET"
    action="{{ route('admin.order') }}"
    class="flex flex-wrap items-end gap-3">

    <div class="flex flex-col">
        <label class="text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Tanggal</label>
        <input
            type="date"
            name="tanggal"
            value="{{ request('tanggal') }}"
            class="rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
    </div>

    <div class="flex flex-col">
        <label class="text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Bulan</label>
        <select name="bulan"
            class="rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
            <option value="">Pilih Bulan</option>
            @foreach ([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'] as $angka => $nama)
            <option value="{{ $angka }}" {{ request('bulan') == $angka ? 'selected' : '' }}>
                {{ $nama }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="flex flex-col">
        <label class="text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Tahun</label>
        <select name="tahun"
            class="rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
            <option value="">Pilih Tahun</option>
            @for($i = date('Y'); $i >= date('Y') - 5; $i--)
            <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>
                {{ $i }}
            </option>
            @endfor
        </select>
    </div>

    <div class="flex gap-2">
        <button type="submit"
            class="px-5 py-2.5 bg-stone-900 hover:bg-stone-700 text-white rounded-xl text-sm font-semibold tracking-wide transition duration-200 shadow hover:shadow-md">
            Filter
        </button>
        <a href="{{ route('admin.order') }}"
            class="px-5 py-2.5 border border-stone-200 bg-rose-500 hover:border-stone-400 text-black hover:text-stone-900 rounded-xl text-sm font-semibold transition duration-200">
            Reset
        </a>
    </div>

</form>