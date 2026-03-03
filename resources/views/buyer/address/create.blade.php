<x-app-layout>
    <div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">
        <h2 class="text-xl font-bold mb-4">Tambah Alamat</h2>

        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('buyer.addresses.store') }}" id="addressForm" class="space-y-4">
            @csrf

            <div>
                <input name="receiver_name" placeholder="Nama Penerima" class="w-full border rounded p-2" required>
            </div>

            <div>
                <input name="phone" placeholder="Nomor HP" class="w-full border rounded p-2" required>
            </div>

            <div>
                <select id="province" name="province" class="w-full border rounded p-2" required>
                    <option value="">Pilih Provinsi</option>
                </select>
                <input type="hidden" name="p_name" id="p_name">
            </div>

            <div>
                <select id="city" name="city" class="w-full border rounded p-2" required>
                    <option value="">Pilih Kota</option>
                </select>
                <input type="hidden" name="c_name" id="c_name">
            </div>

            <!-- Kecamatan -->
            <div>
                <select id="district" name="kecamatan" class="w-full border rounded p-2" required disabled>
                    <option value="">Pilih Kecamatan</option>
                </select>
                <input type="hidden" name="k_name" id="k_name">
            </div>


            <div>
                <textarea name="address" placeholder="Alamat Lengkap" class="w-full border rounded p-2" required></textarea>
            </div>

            <div>
                <input type="text" name="postal_code" placeholder="Kode Pos" class="w-full border rounded p-2">
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan Alamat</button>
        </form>
    </div>

    <script>
        const districtEl = document.getElementById('district');
        document.getElementById('city').addEventListener('change', function() {
                const selectedText = this.options[this.selectedIndex].text;
    document.getElementById('c_name').value = selectedText;
            const cityId = this.value;
            districtEl.innerHTML = '<option value="">Loading...</option>';
            districtEl.disabled = true;

            if (!cityId) return;

            fetch(`/buyer/rajaongkir/districts/${cityId}`)
                .then(res => res.json())
                .then(data => {
                    districtEl.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    data.forEach(d => {
                        let option = document.createElement('option');
                        option.value = d.id; // atau d.id jika mau simpan ID
                        option.textContent = d.name;
                        districtEl.appendChild(option);
                    });
                    districtEl.disabled = false;
                })
                .catch(err => console.error("Gagal load kecamatan:", err));
        });

        // Load provinsi
        fetch("{{ route('buyer.rajaongkir.provinces') }}")
            .then(res => res.json())
            .then(data => {
                console.log("Provinsi:", data);
                const provinceSelect = document.getElementById('province');
                data.forEach(p => {
                    let option = document.createElement('option');
                    option.value = p.id;
                    option.textContent = p.name;
                    provinceSelect.appendChild(option);
                });
            })
            .catch(err => console.error("Gagal load provinsi:", err));

        // Load kota saat provinsi dipilih
        document.getElementById('province').addEventListener('change', function() {
    const provinceId = this.value;
    const selectedText = this.options[this.selectedIndex].text;
    const citySelect = document.getElementById('city');

    document.getElementById('p_name').value = selectedText;

    citySelect.innerHTML = '<option value="">Pilih Kota</option>';

    if (provinceId) {
        fetch(`/buyer/rajaongkir/cities/${provinceId}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(c => {
                    let option = document.createElement('option');
                    option.value = c.id;
                    option.textContent = c.name;
                    citySelect.appendChild(option);
                });
            });
    }
});
        document.getElementById('addressForm').addEventListener('submit', function() {
    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const districtSelect = document.getElementById('district');

    if (provinceSelect.value) {
        document.getElementById('p_name').value =
            provinceSelect.options[provinceSelect.selectedIndex].text;
    }

    if (citySelect.value) {
        document.getElementById('c_name').value =
            citySelect.options[citySelect.selectedIndex].text;
    }

    if (districtSelect.value) {
        document.getElementById('k_name').value =
            districtSelect.options[districtSelect.selectedIndex].text;
    }
});

    </script>
</x-app-layout>