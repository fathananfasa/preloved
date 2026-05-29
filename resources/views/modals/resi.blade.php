<!-- MODAL -->
<div id="resiModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

    <div class="bg-white rounded-xl p-6 w-full max-w-md">

        <h2 class="text-lg font-semibold mb-4">
            Edit Resi
        </h2>

        <form id="resiForm" method="POST">

            @csrf
            @method('PUT')

            <input
                type="text"
                name="resi"
                id="resiInput"
                placeholder="Masukkan nomor resi"
                class="w-full border rounded-lg px-4 py-2 mb-4">

            <div class="flex justify-end gap-2">

                <button
                    type="button"
                    onclick="closeModal()"
                    class="px-4 py-2 bg-gray-300 rounded-lg">
                    Batal
                </button>

                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>
<script>
    function resetBulanTahun() {
        document.querySelector('[name=bulan]').value = '';
        document.querySelector('[name=tahun]').value = '';
    }

    function openModal(id, resi) {

        document.getElementById('resiModal')
            .classList.remove('hidden');

        document.getElementById('resiInput')
            .value = resi ?? '';

        let url = "{{ route('admin.update.resi', ':id') }}";

        url = url.replace(':id', id);

        console.log(url);

        document.getElementById('resiForm').action = url;
    }

    function closeModal() {

        document.getElementById('resiModal')
            .classList.add('hidden');
    }

    function resetBulanTahun() {

        document.querySelector('[name=bulan]').value = '';

        document.querySelector('[name=tahun]').value = '';
    }
</script>