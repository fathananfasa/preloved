<div id="negoModal"
    class="fixed inset-0 hidden bg-black/50 items-center justify-center z-50">

    <div class="bg-white p-6 rounded-2xl w-full max-w-md">

        <h2 class="text-xl font-bold mb-4">
            Hasil Negosiasi
        </h2>

        <p id="aiMessage"></p>

        <div class="mt-4 flex gap-3">

            <button id="acceptBtn"
                class="bg-green-600 text-white px-4 py-2 rounded-xl">
                Oke
            </button>

            <button id="rejectBtn"
                class="bg-red-600 text-white px-4 py-2 rounded-xl">
                Tolak
            </button>

        </div>

    </div>
</div>

<script>
    let latestResult = null;

    const negoForm = document.getElementById('negoForm');
    if (negoForm) {
        negoForm.addEventListener('submit', async function(e) {

            e.preventDefault();

            let formData = new FormData(this);

            let response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            let result = await response.json();

            console.log(result);

            if (result.blocked) {

                alert(result.message);
                return;
            }

            if (result.accepted) {

                alert(result.data.message);

                location.reload();

                return;
            }

            latestResult = result;

            document
                .getElementById('negoModal')
                .classList.remove('hidden');

            document
                .getElementById('negoModal')
                .classList.add('flex');

            document
                .getElementById('aiMessage')
                .innerHTML =
                result.data.message +
                '<br><br><b>Counter Price:</b> Rp ' +
                Number(result.data.counter_price || 0)
                .toLocaleString('id-ID');
        });
    }


    // =========================
    // BUTTON OKE
    // =========================

    // =========================
    // BUTTON TOLAK
    // =========================
    document
        .getElementById('rejectBtn')
        .addEventListener('click', function() {

            document
                .getElementById('negoModal')
                .classList.add('hidden');

            document
                .getElementById('negoModal')
                .classList.remove('flex');

        });

    const updateForm = document.getElementById('updateNegoForm');

    if (updateForm) {

        updateForm.addEventListener('submit', async function(e) {

            e.preventDefault();

            const formData = new FormData(this);
            formData.append('_method', 'PUT');
            const response = await fetch(this.action, {

                method: 'POST',

                body: formData,

                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }

            });

            const result = await response.json();

            if (result.blocked) {

                alert(result.message);

                return;

            }

            if (result.accepted) {

                alert(result.data.message);

                location.reload();

                return;

            }
            latestResult = result;
            document
                .getElementById('negoModal')
                .classList.remove('hidden');

            document
                .getElementById('negoModal')
                .classList.add('flex');

            document
                .getElementById('aiMessage')
                .innerHTML =
                result.data.message +
                '<br><br><b>Counter Price:</b> Rp ' +
                Number(result.data.counter_price)
                .toLocaleString('id-ID');

        });

    }

    document
    .getElementById('acceptBtn')
    .addEventListener('click', async function () {

        const response = await fetch(
            '/buyer/negotiations/' +
            latestResult.negotiation_id +
            '/accept-ai',
            {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }
        );

        const result = await response.json();

        if (result.success) {

            document
                .getElementById('negoModal')
                .classList.add('hidden');

            document
                .getElementById('negoModal')
                .classList.remove('flex');

            alert("Counter AI berhasil diterima.");

            location.reload();
        }

    });
</script>