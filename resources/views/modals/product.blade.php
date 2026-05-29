{{-- ========================= MODAL TAMBAH ========================= --}}
<div
    id="productModal"
    class="fixed inset-0 hidden items-center justify-center bg-black/60 z-50 px-4">

    <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl max-h-[90vh] overflow-y-auto">

        <!-- Header -->
        <div class="flex items-end justify-between px-6 pt-6 pb-4 border-b border-stone-100">
            <div>
                <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Admin</span>
                <h3 class="font-serif text-xl font-bold text-stone-900 mt-0.5">Tambah Produk</h3>
            </div>
            <button onclick="closeProductModal()"
                class="w-8 h-8 flex items-center justify-center rounded-xl border border-stone-200 text-stone-400 hover:border-stone-400 hover:text-stone-700 transition duration-200 text-sm">
                ✕
            </button>
        </div>

        <form
            id="productForm"
            action="{{ route('admin.products.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="px-6 py-5 space-y-4">

            @csrf

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Nama Produk</label>
                <input
                    name="name"
                    placeholder="Nama produk"
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Harga</label>
                    <input
                        type="number"
                        name="price_original"
                        placeholder="0"
                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Harga Min</label>
                    <input
                        type="number"
                        name="bottom_price"
                        placeholder="0"
                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Stok</label>
                    <input
                        type="number"
                        name="stock"
                        placeholder="0"
                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Berat (gram)</label>
                    <input
                        type="number"
                        name="weight"
                        placeholder="0"
                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                </div>
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Kategori</label>
                <select
                    name="category_id"
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Deskripsi</label>
                <textarea
                    name="description"
                    rows="3"
                    placeholder="Deskripsi produk..."
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition resize-none"></textarea>
            </div>

            <!-- Drop Area -->
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Gambar</label>
                <div
                    id="drop-area"
                    class="border-2 border-dashed border-stone-200 rounded-xl p-6 text-center cursor-pointer hover:border-amber-400 hover:bg-amber-50/50 transition duration-200">
                    <p class="text-sm text-stone-500 font-medium">Drag & Drop gambar di sini</p>
                    <p class="text-xs text-stone-400 mt-1">atau klik untuk upload</p>
                    <input id="imageInput" type="file" name="images[]" multiple class="hidden">
                </div>
                <div id="previewContainer" class="grid grid-cols-4 gap-2 mt-3"></div>
            </div>

            <div class="flex gap-3 pt-1">
                <button
                    type="button"
                    onclick="closeProductModal()"
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

{{-- ========================= MODAL EDIT ========================= --}}
<div
    id="editModal"
    class="fixed inset-0 hidden items-center justify-center bg-black/60 z-50 px-4 overflow-y-auto">

    <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl my-8">

        <!-- Header -->
        <div class="flex items-end justify-between px-6 pt-6 pb-4 border-b border-stone-100">
            <div>
                <span class="text-[10px] uppercase tracking-widest text-stone-400 font-medium">Admin</span>
                <h3 class="font-serif text-xl font-bold text-stone-900 mt-0.5">Edit Produk</h3>
            </div>
            <button onclick="closeEditModal()"
                class="w-8 h-8 flex items-center justify-center rounded-xl border border-stone-200 text-stone-400 hover:border-stone-400 hover:text-stone-700 transition duration-200 text-sm">
                ✕
            </button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="px-6 py-5 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Nama Produk</label>
                <input id="edit_name" name="name" placeholder="Nama produk"
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Deskripsi</label>
                <textarea id="edit_description" name="description" rows="3" placeholder="Deskripsi produk..."
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition resize-none"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Harga</label>
                    <input id="edit_price" type="number" name="price_original" placeholder="0"
                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Harga Min</label>
                    <input id="edit_bp" type="number" name="bottom_price" placeholder="0"
                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Stok</label>
                    <input id="edit_stock" type="number" name="stock" placeholder="0"
                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Berat (gram)</label>
                    <input id="edit_weight" type="number" name="weight" placeholder="0"
                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                </div>
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Kategori</label>
                <select id="edit_category" name="category_id"
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Status</label>
                <select id="edit_status" name="status"
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                    <option value="available">Tersedia</option>
                    <option value="waiting_payment">Menunggu Pembayaran</option>
                    <option value="sold">Terjual</option>
                </select>
            </div>

            <!-- Gambar lama -->
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Gambar Produk</label>
                <div id="editImageContainer" class="grid grid-cols-3 gap-3 mt-1"></div>
            </div>

            <!-- Upload baru -->
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Tambah Gambar Baru</label>
                <input type="file" name="images[]" multiple
                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm text-stone-500 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200">
            </div>

            <div class="flex gap-3 pt-1">
                <button
                    type="button"
                    onclick="closeEditModal()"
                    class="flex-1 py-2.5 rounded-xl border border-stone-200 text-stone-600 text-sm font-semibold hover:border-stone-400 hover:text-stone-900 transition duration-200">
                    Batal
                </button>
                <button
                    type="submit"
                    class="flex-1 py-2.5 rounded-xl bg-stone-900 hover:bg-stone-700 text-white text-sm font-semibold tracking-wide transition duration-200 shadow hover:shadow-md">
                    Update
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    let selectedFiles = []

    const dropArea = document.getElementById("drop-area")
    const input = document.getElementById("imageInput")
    const preview = document.getElementById("previewContainer")

    dropArea.onclick = () => input.click()

    input.onchange = function() {
        const files = [...this.files]
        files.forEach(file => {
            if (!file.type.startsWith("image/")) return
            selectedFiles.push(file)
        })
        updateInputFiles()
        renderPreview()
    }

    dropArea.ondragover = e => {
        e.preventDefault()
        dropArea.classList.add("border-amber-400", "bg-amber-50/50")
    }

    dropArea.ondragleave = () => {
        dropArea.classList.remove("border-amber-400", "bg-amber-50/50")
    }

    dropArea.ondrop = e => {
        e.preventDefault()
        const files = [...e.dataTransfer.files]
        files.forEach(file => {
            if (!file.type.startsWith("image/")) return
            selectedFiles.push(file)
        })
        updateInputFiles()
        renderPreview()
        dropArea.classList.remove("border-amber-400", "bg-amber-50/50")
    }

    function renderPreview() {
        preview.innerHTML = ""
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader()
            reader.onload = e => {
                preview.innerHTML += `
                <div class="relative rounded-xl overflow-hidden border border-stone-100">
                    <img src="${e.target.result}" class="w-full h-20 object-cover">
                    <button type="button" onclick="removeImage(${index})"
                        class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white text-[10px] rounded-lg flex items-center justify-center">
                        ✕
                    </button>
                </div>`
            }
            reader.readAsDataURL(file)
        })
    }

    function removeImage(index) {
        selectedFiles.splice(index, 1)
        updateInputFiles()
        renderPreview()
    }

    function updateInputFiles() {
        const dt = new DataTransfer()
        selectedFiles.forEach(f => dt.items.add(f))
        input.files = dt.files
    }

    function openProductModal() {
        document.getElementById("productModal").classList.remove("hidden")
        document.getElementById("productModal").classList.add("flex")
    }

    function openEditModal(product) {
        const modal = document.getElementById("editModal")
        modal.classList.remove("hidden")
        modal.classList.add("flex")

        document.getElementById("editForm").action = `/admin/products/${product.id}`
        document.getElementById("edit_name").value = product.name ?? ''
        document.getElementById("edit_description").value = product.description ?? ''
        document.getElementById("edit_price").value = product.price_original ?? ''
        document.getElementById("edit_bp").value = product.bottom_price ?? ''
        document.getElementById("edit_stock").value = product.stock ?? ''
        document.getElementById("edit_weight").value = product.weight ?? ''
        document.getElementById("edit_category").value = product.category_id ?? ''
        document.getElementById("edit_status").value = product.status ?? ''

        const container = document.getElementById("editImageContainer")
        container.innerHTML = ""

        if (product.images && product.images.length > 0) {
            product.images.forEach(img => {
                container.innerHTML += `
                <div class="relative rounded-xl overflow-hidden border border-stone-100">
                    <img src="/storage/${img.image_path}" class="w-full h-24 object-cover">
                    <button type="button" onclick="deleteImage(${img.id})"
                        class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white text-[10px] rounded-lg flex items-center justify-center">
                        ✕
                    </button>
                </div>`
            })
        } else {
            container.innerHTML = `<p class="text-xs text-stone-400">Tidak ada gambar</p>`
        }
    }

    function closeEditModal() {
        const modal = document.getElementById("editModal")
        modal.classList.add("hidden")
        modal.classList.remove("flex")
        document.getElementById("editForm").reset()
        document.getElementById("editImageContainer").innerHTML = ""
    }

    function deleteImage(id) {
        if (!confirm('Hapus gambar ini?')) return;
        fetch(`/admin/product-images/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Gagal hapus')
            return res.json()
        })
        .then(() => {
            event.target.closest('div').remove()
        })
        .catch(err => alert(err.message))
    }

    function closeProductModal() {
        document.getElementById("productModal").classList.add("hidden")
        document.getElementById("productModal").classList.remove("flex")
        document.getElementById("productForm").reset()
        selectedFiles = []
        preview.innerHTML = ""
    }
</script>