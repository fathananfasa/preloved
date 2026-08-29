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
                @error('name', 'store')
                <p class="text-xs text-red-500 font-medium mb-1">{{ $message }}</p>
                @enderror
                <input
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Nama produk"
                    class="w-full rounded-xl border {{ $errors->store->has('name') ? 'border-red-400 ring-1 ring-red-300' : 'border-stone-200' }} bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Harga</label>
                    @error('price_original', 'store')
                    <p class="text-xs text-red-500 font-medium mb-1">{{ $message }}</p>
                    @enderror
                    <input
                        type="number"
                        name="price_original"
                        value="{{ old('price_original') }}"
                        placeholder="0"
                        class="w-full rounded-xl border {{ $errors->store->has('price_original') ? 'border-red-400 ring-1 ring-red-300' : 'border-stone-200' }} bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Harga Min</label>
                    @error('bottom_price', 'store')
                    <p class="text-xs text-red-500 font-medium mb-1">{{ $message }}</p>
                    @enderror
                    <input
                        type="number"
                        name="bottom_price"
                        value="{{ old('bottom_price') }}"
                        placeholder="0"
                        class="w-full rounded-xl border {{ $errors->store->has('bottom_price') ? 'border-red-400 ring-1 ring-red-300' : 'border-stone-200' }} bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Stok</label>
                    @error('stock', 'store')
                    <p class="text-xs text-red-500 font-medium mb-1">{{ $message }}</p>
                    @enderror
                    <input
                        type="number"
                        name="stock"
                        value="{{ old('stock') }}"
                        placeholder="0"
                        class="w-full rounded-xl border {{ $errors->store->has('stock') ? 'border-red-400 ring-1 ring-red-300' : 'border-stone-200' }} bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Berat (gram)</label>
                    @error('weight', 'store')
                    <p class="text-xs text-red-500 font-medium mb-1">{{ $message }}</p>
                    @enderror
                    <input
                        type="number"
                        name="weight"
                        value="{{ old('weight') }}"
                        placeholder="0"
                        class="w-full rounded-xl border {{ $errors->store->has('weight') ? 'border-red-400 ring-1 ring-red-300' : 'border-stone-200' }} bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                </div>
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Kategori</label>
                @error('category_id', 'store')
                <p class="text-xs text-red-500 font-medium mb-1">{{ $message }}</p>
                @enderror
                <select
                    name="category_id"
                    class="w-full rounded-xl border {{ $errors->store->has('category_id') ? 'border-red-400 ring-1 ring-red-300' : 'border-stone-200' }} bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Deskripsi</label>
                @error('description', 'store')
                <p class="text-xs text-red-500 font-medium mb-1">{{ $message }}</p>
                @enderror
                <textarea
                    name="description"
                    rows="3"
                    placeholder="Deskripsi produk..."
                    class="w-full rounded-xl border {{ $errors->store->has('description') ? 'border-red-400 ring-1 ring-red-300' : 'border-stone-200' }} bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition resize-none">{{ old('description') }}</textarea>
            </div>

            <!-- Drop Area -->
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Gambar</label>
                @error('images.*', 'store')
                <p class="text-xs text-red-500 font-medium mb-1">{{ $message }}</p>
                @enderror
                <div
                    id="drop-area"
                    class="border-2 border-dashed {{ $errors->store->has('images.*') ? 'border-red-300' : 'border-stone-200' }} rounded-xl p-6 text-center cursor-pointer hover:border-amber-400 hover:bg-amber-50/50 transition duration-200">
                    <p class="text-sm text-stone-500 font-medium">Drag & Drop gambar di sini</p>
                    <p class="text-xs text-stone-400 mt-1">atau klik untuk upload (jpg, jpeg, png, webp — maks 2MB)</p>
                    <input id="imageInput" type="file" name="images[]" multiple class="hidden">
                </div>
                <p id="imageError" class="text-xs text-red-500 font-medium mt-2 hidden"></p>
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

            {{-- penting: dipakai buat inget produk mana yang lagi diedit kalau validasi gagal & halaman reload --}}
            <input type="hidden" id="edit_product_id" name="product_id" value="{{ old('product_id') }}">

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Nama Produk</label>
                @error('name', 'update')
                <p class="text-xs text-red-500 font-medium mb-1">{{ $message }}</p>
                @enderror
                <input id="edit_name" name="name" value="{{ old('name') }}" placeholder="Nama produk"
                    class="w-full rounded-xl border {{ $errors->update->has('name') ? 'border-red-400 ring-1 ring-red-300' : 'border-stone-200' }} bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Deskripsi</label>
                @error('description', 'update')
                <p class="text-xs text-red-500 font-medium mb-1">{{ $message }}</p>
                @enderror
                <textarea id="edit_description" name="description" rows="3" placeholder="Deskripsi produk..."
                    class="w-full rounded-xl border {{ $errors->update->has('description') ? 'border-red-400 ring-1 ring-red-300' : 'border-stone-200' }} bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition resize-none">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Harga</label>
                    @error('price_original', 'update')
                    <p class="text-xs text-red-500 font-medium mb-1">{{ $message }}</p>
                    @enderror
                    <input id="edit_price" type="number" name="price_original" value="{{ old('price_original') }}" placeholder="0"
                        class="w-full rounded-xl border {{ $errors->update->has('price_original') ? 'border-red-400 ring-1 ring-red-300' : 'border-stone-200' }} bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Harga Min</label>
                    @error('bottom_price', 'update')
                    <p class="text-xs text-red-500 font-medium mb-1">{{ $message }}</p>
                    @enderror
                    <input id="edit_bp" type="number" name="bottom_price" value="{{ old('bottom_price') }}" placeholder="0"
                        class="w-full rounded-xl border {{ $errors->update->has('bottom_price') ? 'border-red-400 ring-1 ring-red-300' : 'border-stone-200' }} bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Stok</label>
                    @error('stock', 'update')
                    <p class="text-xs text-red-500 font-medium mb-1">{{ $message }}</p>
                    @enderror
                    <input id="edit_stock" type="number" name="stock" value="{{ old('stock') }}" placeholder="0"
                        class="w-full rounded-xl border {{ $errors->update->has('stock') ? 'border-red-400 ring-1 ring-red-300' : 'border-stone-200' }} bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Berat (gram)</label>
                    @error('weight', 'update')
                    <p class="text-xs text-red-500 font-medium mb-1">{{ $message }}</p>
                    @enderror
                    <input id="edit_weight" type="number" name="weight" value="{{ old('weight') }}" placeholder="0"
                        class="w-full rounded-xl border {{ $errors->update->has('weight') ? 'border-red-400 ring-1 ring-red-300' : 'border-stone-200' }} bg-white px-4 py-2.5 text-sm text-stone-800 placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                </div>
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Kategori</label>
                @error('category_id', 'update')
                <p class="text-xs text-red-500 font-medium mb-1">{{ $message }}</p>
                @enderror
                <select id="edit_category" name="category_id"
                    class="w-full rounded-xl border {{ $errors->update->has('category_id') ? 'border-red-400 ring-1 ring-red-300' : 'border-stone-200' }} bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] uppercase tracking-widest text-stone-400 font-medium mb-1.5">Status</label>
                @error('status', 'update')
                <p class="text-xs text-red-500 font-medium mb-1">{{ $message }}</p>
                @enderror
                <select id="edit_status" name="status"
                    class="w-full rounded-xl border {{ $errors->update->has('status') ? 'border-red-400 ring-1 ring-red-300' : 'border-stone-200' }} bg-white px-4 py-2.5 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="waiting_payment" {{ old('status') == 'waiting_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Terjual</option>
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
                @error('images.*', 'update')
                <p class="text-xs text-red-500 font-medium mb-1">{{ $message }}</p>
                @enderror
                <input type="file" name="images[]" multiple
                    onchange="validateEditImages(this)"
                    class="w-full rounded-xl border {{ $errors->update->has('images.*') ? 'border-red-400 ring-1 ring-red-300' : 'border-stone-200' }} bg-white px-4 py-2.5 text-sm text-stone-500 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent transition file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-stone-100 file:text-stone-700 hover:file:bg-stone-200">
                <p id="editImageError" class="text-xs text-red-500 font-medium mt-2 hidden"></p>
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
    // ===================================================================
    // VALIDASI TIPE & UKURAN FILE (harus sama persis dengan aturan di server)
    // ===================================================================
    const ALLOWED_MIME = ["image/jpeg", "image/png", "image/webp"]
    const ALLOWED_EXT = ["jpg", "jpeg", "png", "webp"]
    const MAX_SIZE_BYTES = 2 * 1024 * 1024 // 2MB, samain sama max:2048 di server

    function isAllowedType(file) {
        const ext = file.name.split('.').pop().toLowerCase()
        return ALLOWED_MIME.includes(file.type) && ALLOWED_EXT.includes(ext)
    }

    function isAllowedSize(file) {
        return file.size <= MAX_SIZE_BYTES
    }

    // ===================================================================
    // MODAL TAMBAH — drop area & preview
    // ===================================================================
    let selectedFiles = []

    const dropArea = document.getElementById("drop-area")
    const input = document.getElementById("imageInput")
    const preview = document.getElementById("previewContainer")
    const imageError = document.getElementById("imageError")

    dropArea.onclick = () => input.click()

    function handleIncomingFiles(fileList) {
        let hasInvalidType = false
        let hasInvalidSize = false

        ;
        [...fileList].forEach(file => {
            if (!isAllowedType(file)) {
                hasInvalidType = true
                return
            }
            if (!isAllowedSize(file)) {
                hasInvalidSize = true
                return
            }
            selectedFiles.push(file)
        })

        const messages = []
        if (hasInvalidType) messages.push('Format tidak didukung. Hanya jpg, jpeg, png, webp yang diperbolehkan.')
        if (hasInvalidSize) messages.push('Ukuran gambar maksimal 2MB.')

        if (messages.length > 0) {
            imageError.textContent = messages.join(' ')
            imageError.classList.remove("hidden")
        } else {
            imageError.textContent = ""
            imageError.classList.add("hidden")
        }

        updateInputFiles()
        renderPreview()
    }

    input.onchange = function() {
        handleIncomingFiles(this.files)
        this.value = ""
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
        handleIncomingFiles(e.dataTransfer.files)
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

    function closeProductModal() {
        const modal = document.getElementById("productModal")
        modal.classList.add("hidden")
        modal.classList.remove("flex")

        const form = document.getElementById("productForm")

        // clear semua text-ish input, kecuali file & hidden
        form.querySelectorAll('input:not([type="file"]):not([type="hidden"]), textarea').forEach(el => el.value = "")
        form.querySelector('select[name="category_id"]').selectedIndex = 0

        // bersihin sisa tampilan error dari validasi sebelumnya
        form.querySelectorAll('p.text-red-500').forEach(el => el.remove())
        form.querySelectorAll('.border-red-400').forEach(el => {
            el.classList.remove('border-red-400', 'ring-1', 'ring-red-300')
            el.classList.add('border-stone-200')
        })

        selectedFiles = []
        preview.innerHTML = ""
        imageError.textContent = ""
        imageError.classList.add("hidden")
    }

    // ===================================================================
    // MODAL EDIT
    // ===================================================================
    function openEditModal(product) {
        const modal = document.getElementById("editModal")
        modal.classList.remove("hidden")
        modal.classList.add("flex")

        document.getElementById("editForm").action = `/admin/products/${product.id}`
        document.getElementById("edit_product_id").value = product.id
        document.getElementById("edit_name").value = product.name ?? ''
        document.getElementById("edit_description").value = product.description ?? ''
        document.getElementById("edit_price").value = product.price_original ?? ''
        document.getElementById("edit_bp").value = product.bottom_price ?? ''
        document.getElementById("edit_stock").value = product.stock ?? ''
        document.getElementById("edit_weight").value = product.weight ?? ''
        document.getElementById("edit_category").value = product.category_id ?? ''
        document.getElementById("edit_status").value = product.status ?? ''

        renderEditImages(product.images ?? [])
    }

    function validateEditImages(inputEl) {
        const errorEl = document.getElementById("editImageError")
        const files = [...inputEl.files]

        const hasInvalidType = files.some(f => !isAllowedType(f))
        const hasInvalidSize = files.some(f => isAllowedType(f) && !isAllowedSize(f))

        const messages = []
        if (hasInvalidType) messages.push('Format tidak didukung. Hanya jpg, jpeg, png, webp yang diperbolehkan.')
        if (hasInvalidSize) messages.push('Ukuran gambar maksimal 2MB.')

        if (messages.length > 0) {
            errorEl.textContent = messages.join(' ')
            errorEl.classList.remove("hidden")
            inputEl.value = ""
        } else {
            errorEl.textContent = ""
            errorEl.classList.add("hidden")
        }
    }

    function renderEditImages(images) {
        const container = document.getElementById("editImageContainer")
        container.innerHTML = ""

        if (images && images.length > 0) {
            images.forEach(img => {
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
        document.getElementById("editImageError").classList.add("hidden")
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

    // ===================================================================
    // AUTO RE-OPEN MODAL SETELAH VALIDASI GAGAL (biar error kelihatan)
    // ===================================================================
    @if($errors -> store -> any())
    document.addEventListener('DOMContentLoaded', openProductModal)
    @elseif($errors -> update -> any())
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById("editModal").classList.remove("hidden")
        document.getElementById("editModal").classList.add("flex")
        const pid = document.getElementById("edit_product_id").value
        if (pid) {
            document.getElementById("editForm").action = `/admin/products/${pid}`
            @if($oldEditProduct)
            renderEditImages(@json($oldEditProduct -> images))
            @endif
        }
    })
    @endif

    @if(session('notify'))
    setTimeout(() => {
        const toast = document.getElementById('successToast')
        if (toast) {
            toast.style.transition = 'opacity 0.4s ease'
            toast.style.opacity = '0'
            setTimeout(() => toast.remove(), 400)
        }
    }, 5000)

    @endif
</script>