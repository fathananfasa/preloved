<!-- Address Modal -->
<div
    id="addressModal"
    onclick="outsideClick(event)"
    class="fixed inset-0 hidden items-center justify-center bg-black/60 z-50 px-4">

    <div class="bg-white w-full max-w-xl max-h-[90vh] overflow-y-auto rounded-2xl shadow-2xl relative">

        <!-- Header -->
        <div class="flex items-end justify-between px-6 pt-6 pb-4 border-b border-stone-100">
            <div>
                <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Pengiriman</span>
                <h2 class="font-serif text-xl font-bold text-stone-900 mt-0.5">Tambah Alamat</h2>
            </div>
            <button
                type="button"
                onclick="closeModal()"
                class="w-8 h-8 flex items-center justify-center rounded-xl border border-stone-200 text-stone-400 hover:border-stone-400 hover:text-stone-700 transition duration-200 text-sm">
                ✕
            </button>
        </div>

        <!-- Form -->
        <form
            id="addressForm"
            method="POST"
            action="{{ route('buyer.addresses.store') }}"
            class="px-6 py-5 space-y-4">

            @csrf

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Nama Penerima</label>
                <input
                    type="text"
                    name="receiver_name"
                    placeholder="Nama lengkap penerima"
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                    required>
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Nomor HP</label>
                <input
                    type="text"
                    name="phone"
                    placeholder="08xxxxxxxxxx"
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                    required>
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Provinsi</label>
                <select
                    id="province"
                    name="province"
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                    required>
                    <option value="">Pilih Provinsi</option>
                </select>
                <input type="hidden" name="p_name" id="p_name">
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Kota</label>
                <select
                    id="city"
                    name="city"
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                    required>
                    <option value="">Pilih Kota</option>
                </select>
                <input type="hidden" name="c_name" id="c_name">
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Kecamatan</label>
                <select
                    id="district"
                    name="kecamatan"
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled required>
                    <option value="">Pilih Kecamatan</option>
                </select>
                <input type="hidden" name="k_name" id="k_name">
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Alamat Lengkap</label>
                <textarea
                    name="address"
                    rows="3"
                    placeholder="Nama jalan, nomor rumah, RT/RW..."
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition resize-none"
                    required></textarea>
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Kode Pos</label>
                <input
                    type="text"
                    name="postal_code"
                    placeholder="12345"
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
            </div>

            <div class="flex gap-3 pt-1">
                <button
                    type="button"
                    onclick="closeModal()"
                    class="flex-1 py-2.5 rounded-xl border border-stone-200 text-stone-600 text-sm font-semibold hover:border-stone-400 hover:text-stone-900 transition duration-200">
                    Batal
                </button>
                <button
                    type="submit"
                    class="flex-1 py-2.5 rounded-xl bg-stone-900 hover:bg-stone-700 text-white text-sm font-semibold tracking-wide transition duration-200 shadow hover:shadow-md">
                    Simpan
                </button>
            </div>

        </form>

    </div>

</div>

<script>
    const provinceEl = document.getElementById('province');
    const cityEl = document.getElementById('city');
    const districtEl = document.getElementById('district');

    function openModal() {

        document
            .getElementById('addressModal')
            .classList.replace('hidden', 'flex');

    }

    function closeModal() {

        document
            .getElementById('addressModal')
            .classList.replace('flex', 'hidden');

    }

    function outsideClick(e) {

        if (e.target.id === 'addressModal') {
            closeModal();
        }

    }

    /* Load Provinsi */

    fetch("{{ route('buyer.rajaongkir.provinces') }}")

        .then(res => res.json())

        .then(data => {

            data.forEach(item => {

                provinceEl.innerHTML += `
            <option value="${item.id}">
                ${item.name}
            </option>
        `;

            });

        })

        .catch(error => {

            console.log(error);

        });


    /* Province Change */

    provinceEl.addEventListener('change', function() {

        const provinceId = this.value;

        document.getElementById('p_name').value =
            this.options[this.selectedIndex].text;

        cityEl.innerHTML =
            '<option value="">Pilih Kota</option>';

        districtEl.innerHTML =
            '<option value="">Pilih Kecamatan</option>';

        districtEl.disabled = true;

        if (!provinceId) return;

        fetch(`/buyer/rajaongkir/cities/${provinceId}`)

            .then(res => res.json())

            .then(data => {

                data.forEach(item => {

                    cityEl.innerHTML += `
                <option value="${item.id}">
                    ${item.name}
                </option>
            `;

                });

            });

    });


    /* City Change */

    cityEl.addEventListener('change', function() {

        const cityId = this.value;

        document.getElementById('c_name').value =
            this.options[this.selectedIndex].text;

        districtEl.innerHTML =
            '<option>Loading...</option>';

        districtEl.disabled = true;

        if (!cityId) return;

        fetch(`/buyer/rajaongkir/districts/${cityId}`)

            .then(res => res.json())

            .then(data => {

                districtEl.innerHTML =
                    '<option value="">Pilih Kecamatan</option>';

                data.forEach(item => {

                    districtEl.innerHTML += `
                <option value="${item.id}">
                    ${item.name}
                </option>
            `;

                });

                districtEl.disabled = false;

            });

    });


    /* Submit */

    document
        .getElementById('addressForm')
        .addEventListener('submit', function() {

            document.getElementById('k_name').value =
                districtEl.options[
                    districtEl.selectedIndex
                ].text;

        });
</script>