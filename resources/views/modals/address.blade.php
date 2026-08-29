<!-- Address Modal -->

<div
    id="addressModal"
    onclick="outsideClick(event)"
    class="fixed inset-0 hidden items-center justify-center bg-black/60 z-50 px-4">

```
<div class="bg-white w-full max-w-xl max-h-[90vh] overflow-y-auto rounded-2xl shadow-2xl relative">

    <!-- Header -->
    <div class="flex items-end justify-between px-6 pt-6 pb-4 border-b border-stone-100">
        <div>
            <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">
                Pengiriman
            </span>

            <h2
                id="addressModalTitle"
                class="font-serif text-xl font-bold text-stone-900 mt-0.5">
                Tambah Alamat
            </h2>
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

        <!-- Digunakan saat mode Edit -->
        <input
            type="hidden"
            name="_method"
            id="addressMethod"
            disabled>


        <!-- Nama Penerima -->
        <div>
            <label
                for="receiver_name"
                class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">
                Nama Penerima
            </label>

            <input
                id="receiver_name"
                type="text"
                name="receiver_name"
                placeholder="Nama lengkap penerima"
                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                required>
        </div>


        <!-- Nomor HP -->
        <div>
            <label
                for="phone"
                class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">
                Nomor HP
            </label>

            <input
                id="phone"
                type="text"
                name="phone"
                placeholder="08xxxxxxxxxx"
                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                required>
        </div>


        <!-- Provinsi -->
        <div>
            <label
                for="province"
                class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">
                Provinsi
            </label>

            <select
                id="province"
                name="province"
                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                required>

                <option value="">Pilih Provinsi</option>
            </select>

            <input
                type="hidden"
                name="p_name"
                id="p_name">
        </div>


        <!-- Kota -->
        <div>
            <label
                for="city"
                class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">
                Kota
            </label>

            <select
                id="city"
                name="city"
                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                required>

                <option value="">Pilih Kota</option>
            </select>

            <input
                type="hidden"
                name="c_name"
                id="c_name">
        </div>


        <!-- Kecamatan -->
        <div>
            <label
                for="district"
                class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">
                Kecamatan
            </label>

            <select
                id="district"
                name="kecamatan"
                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition disabled:opacity-50 disabled:cursor-not-allowed"
                disabled
                required>

                <option value="">Pilih Kecamatan</option>
            </select>

            <input
                type="hidden"
                name="k_name"
                id="k_name">
        </div>


        <!-- Alamat Lengkap -->
        <div>
            <label
                for="address"
                class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">
                Alamat Lengkap
            </label>

            <textarea
                id="address"
                name="address"
                rows="3"
                placeholder="Nama jalan, nomor rumah, RT/RW..."
                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition resize-none"
                required></textarea>
        </div>


        <!-- Kode Pos -->
        <div>
            <label
                for="postal_code"
                class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">
                Kode Pos
            </label>

            <input
                id="postal_code"
                type="text"
                name="postal_code"
                placeholder="12345"
                class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition"
                required>
        </div>


        <!-- Default Address -->
        <div class="flex items-center gap-2 pt-1">
            <input
                id="is_default"
                type="checkbox"
                name="is_default"
                value="1"
                class="rounded border-stone-300 text-stone-900 focus:ring-amber-400">

            <label
                for="is_default"
                class="text-sm text-stone-600 cursor-pointer">
                Jadikan alamat utama
            </label>
        </div>


        <!-- Buttons -->
        <div class="flex gap-3 pt-2">

            <button
                type="button"
                onclick="closeModal()"
                class="flex-1 py-2.5 rounded-xl border border-stone-200 text-stone-600 text-sm font-semibold hover:border-stone-400 hover:text-stone-900 transition duration-200">
                Batal
            </button>

            <button
                id="addressSubmitButton"
                type="submit"
                class="flex-1 py-2.5 rounded-xl bg-stone-900 hover:bg-stone-700 text-white text-sm font-semibold tracking-wide transition duration-200 shadow hover:shadow-md">
                Simpan
            </button>

        </div>

    </form>

</div>
```

</div>

<script>
    const provinceEl = document.getElementById('province');
    const cityEl = document.getElementById('city');
    const districtEl = document.getElementById('district');

    const addressModal = document.getElementById('addressModal');
    const addressForm = document.getElementById('addressForm');
    const addressMethod = document.getElementById('addressMethod');


    /*
    |--------------------------------------------------------------------------
    | BUKA MODAL TAMBAH
    |--------------------------------------------------------------------------
    */

    function openModal() {

        addressForm.reset();

        addressForm.action =
            "{{ route('buyer.addresses.store') }}";

        addressMethod.disabled = true;
        addressMethod.value = '';

        document.getElementById('addressModalTitle').textContent =
            'Tambah Alamat';

        document.getElementById('addressSubmitButton').textContent =
            'Simpan';


        cityEl.innerHTML =
            '<option value="">Pilih Kota</option>';


        districtEl.innerHTML =
            '<option value="">Pilih Kecamatan</option>';

        districtEl.disabled = true;


        document.getElementById('p_name').value = '';
        document.getElementById('c_name').value = '';
        document.getElementById('k_name').value = '';


        addressModal.classList.replace('hidden', 'flex');

        document.body.classList.add('overflow-hidden');
    }


    /*
    |--------------------------------------------------------------------------
    | TUTUP MODAL
    |--------------------------------------------------------------------------
    */

    function closeModal() {

        addressModal.classList.replace('flex', 'hidden');

        document.body.classList.remove('overflow-hidden');
    }


    /*
    |--------------------------------------------------------------------------
    | KLIK AREA LUAR MODAL
    |--------------------------------------------------------------------------
    */

    function outsideClick(event) {

        if (event.target.id === 'addressModal') {
            closeModal();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD PROVINSI
    |--------------------------------------------------------------------------
    */

    fetch("{{ route('buyer.rajaongkir.provinces') }}")

        .then(response => {

            if (!response.ok) {
                throw new Error('Gagal mengambil data provinsi');
            }

            return response.json();
        })

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
            console.error('Province error:', error);
        });


    /*
    |--------------------------------------------------------------------------
    | PROVINSI BERUBAH
    |--------------------------------------------------------------------------
    */

    provinceEl.addEventListener('change', async function() {

        const provinceId = this.value;


        document.getElementById('p_name').value =
            this.options[this.selectedIndex]?.text ?? '';


        cityEl.innerHTML =
            '<option value="">Pilih Kota</option>';


        districtEl.innerHTML =
            '<option value="">Pilih Kecamatan</option>';


        districtEl.disabled = true;


        document.getElementById('c_name').value = '';
        document.getElementById('k_name').value = '';


        if (!provinceId) {
            return;
        }


        cityEl.innerHTML =
            '<option value="">Loading...</option>';


        try {

            const response = await fetch(
                `/buyer/rajaongkir/cities/${provinceId}`
            );


            if (!response.ok) {
                throw new Error('Gagal mengambil data kota');
            }


            const data = await response.json();


            cityEl.innerHTML =
                '<option value="">Pilih Kota</option>';


            data.forEach(item => {

                cityEl.innerHTML += `
                    <option value="${item.id}">
                        ${item.name}
                    </option>
                `;
            });

        } catch (error) {

            console.error('City error:', error);

            cityEl.innerHTML =
                '<option value="">Gagal memuat kota</option>';
        }
    });


    /*
    |--------------------------------------------------------------------------
    | KOTA BERUBAH
    |--------------------------------------------------------------------------
    */

    cityEl.addEventListener('change', async function() {

        const cityId = this.value;


        document.getElementById('c_name').value =
            this.options[this.selectedIndex]?.text ?? '';


        districtEl.innerHTML =
            '<option value="">Pilih Kecamatan</option>';


        districtEl.disabled = true;


        document.getElementById('k_name').value = '';


        if (!cityId) {
            return;
        }


        districtEl.innerHTML =
            '<option value="">Loading...</option>';


        try {

            const response = await fetch(
                `/buyer/rajaongkir/districts/${cityId}`
            );


            if (!response.ok) {
                throw new Error('Gagal mengambil data kecamatan');
            }


            const data = await response.json();


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

        } catch (error) {

            console.error('District error:', error);

            districtEl.innerHTML =
                '<option value="">Gagal memuat kecamatan</option>';
        }
    });


    /*
    |--------------------------------------------------------------------------
    | KECAMATAN BERUBAH
    |--------------------------------------------------------------------------
    */

    districtEl.addEventListener('change', function() {

        document.getElementById('k_name').value =
            this.options[this.selectedIndex]?.text ?? '';
    });


    /*
    |--------------------------------------------------------------------------
    | BUKA MODAL EDIT
    |--------------------------------------------------------------------------
    */

    async function openEditModal(address) {

        addressForm.reset();


        addressForm.action =
            "{{ url('/buyer/addresses') }}/" + address.id;


        addressMethod.disabled = false;
        addressMethod.value = 'PUT';


        document.getElementById('addressModalTitle').textContent =
            'Edit Alamat';


        document.getElementById('addressSubmitButton').textContent =
            'Simpan Perubahan';


        /*
        | Isi data dasar
        */

        document.getElementById('receiver_name').value =
            address.receiver_name ?? '';


        document.getElementById('phone').value =
            address.phone ?? '';


        document.getElementById('address').value =
            address.address ?? '';


        document.getElementById('postal_code').value =
            address.postal_code ?? '';


        document.getElementById('is_default').checked =
            Number(address.is_default) === 1;


        /*
        | Isi provinsi
        */

        provinceEl.value =
            String(address.province);


        document.getElementById('p_name').value =
            address.p_name ?? '';


        /*
        | Reset kota dan kecamatan
        */

        cityEl.innerHTML =
            '<option value="">Loading...</option>';


        districtEl.innerHTML =
            '<option value="">Loading...</option>';


        districtEl.disabled = true;


        /*
        | Buka modal lebih dulu
        */

        addressModal.classList.replace('hidden', 'flex');

        document.body.classList.add('overflow-hidden');


        try {

            /*
            | Load kota berdasarkan provinsi lama
            */

            const cityResponse = await fetch(
                `/buyer/rajaongkir/cities/${address.province}`
            );


            if (!cityResponse.ok) {
                throw new Error('Gagal mengambil data kota');
            }


            const cities = await cityResponse.json();


            cityEl.innerHTML =
                '<option value="">Pilih Kota</option>';


            cities.forEach(item => {

                cityEl.innerHTML += `
                    <option value="${item.id}">
                        ${item.name}
                    </option>
                `;
            });


            cityEl.value =
                String(address.city);


            document.getElementById('c_name').value =
                address.c_name ?? '';


            /*
            | Load kecamatan berdasarkan kota lama
            */

            const districtResponse = await fetch(
                `/buyer/rajaongkir/districts/${address.city}`
            );


            if (!districtResponse.ok) {
                throw new Error('Gagal mengambil data kecamatan');
            }


            const districts =
                await districtResponse.json();


            districtEl.innerHTML =
                '<option value="">Pilih Kecamatan</option>';


            districts.forEach(item => {

                districtEl.innerHTML += `
                    <option value="${item.id}">
                        ${item.name}
                    </option>
                `;
            });


            districtEl.disabled = false;


            districtEl.value =
                String(address.kecamatan);


            document.getElementById('k_name').value =
                address.k_name ?? '';


        } catch (error) {

            console.error(
                'Gagal memuat data wilayah:',
                error
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDASI SEBELUM SUBMIT
    |--------------------------------------------------------------------------
    */

    addressForm.addEventListener('submit', function(event) {

        const selectedProvince =
            provinceEl.options[provinceEl.selectedIndex];


        const selectedCity =
            cityEl.options[cityEl.selectedIndex];


        const selectedDistrict =
            districtEl.options[districtEl.selectedIndex];


        if (selectedProvince && provinceEl.value) {

            document.getElementById('p_name').value =
                selectedProvince.text;
        }


        if (selectedCity && cityEl.value) {

            document.getElementById('c_name').value =
                selectedCity.text;
        }


        if (selectedDistrict && districtEl.value) {

            document.getElementById('k_name').value =
                selectedDistrict.text;
        }
    });


    /*
    |--------------------------------------------------------------------------
    | ESC UNTUK TUTUP MODAL
    |--------------------------------------------------------------------------
    */

    document.addEventListener('keydown', function(event) {

        if (
            event.key === 'Escape' &&
            addressModal.classList.contains('flex')
        ) {
            closeModal();
        }
    });

</script>
