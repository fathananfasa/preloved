<x-app-layout>

    <div class="p-4 md:p-6">

        {{-- =========================
FILTER & SEARCH
========================= --}}

        <div class="mb-4 flex flex-col md:flex-row md:items-center md:gap-4 gap-2">

            <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-2">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama produk..."
                    class="border rounded px-3 py-2 text-sm w-full sm:w-60 focus:ring-1 focus:ring-blue-400">

                <select
                    name="category_id"
                    class="border rounded px-3 py-2 text-sm w-full sm:w-48 focus:ring-1 focus:ring-blue-400">

                    <option value="">Semua Kategori</option>

                    @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach

                </select>

                <button
                    class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                    Cari
                </button>

                <a
                    href="{{ route('admin.products.index') }}"
                    class="px-4 py-2 bg-red-500 text-white rounded text-sm hover:bg-red-600 text-center">
                    Reset
                </a>

            </form>

            <button
                onclick="openProductModal()"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Tambah Produk
            </button>

        </div>

        {{-- =========================
TABLE
========================= --}}

        <div class="overflow-x-auto border rounded-lg shadow-sm">

            <table class="min-w-full divide-y divide-gray-200 text-sm">

                <thead class="bg-gray-50 text-gray-700 uppercase text-xs">

                    <tr>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Harga</th>
                        <th class="px-4 py-3 text-left">Stok</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                        <th class="px-4 py-3 text-left">Gambar</th>
                    </tr>

                </thead>

                <tbody class="bg-white divide-y">

                    @foreach ($products as $product)

                    <tr class="hover:bg-gray-50">

                        <td class="px-4 py-3 font-medium">
                            {{ $product->name }}
                        </td>

                        <td class="px-4 py-3">
                            Rp {{ number_format($product->price_original) }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $product->stock }}
                        </td>

                        <td class="px-4 py-3">

                            <span class="px-2 py-1 rounded-full text-xs font-semibold
{{ $product->status === 'active'
? 'bg-green-100 text-green-800'
: 'bg-gray-200 text-gray-700' }}">

                                {{ ucfirst($product->status) }}

                            </span>

                        </td>

                        <td class="px-4 py-3">
                            {{ $product->category->name ?? '-' }}
                        </td>

                        <td class="px-4 py-3 flex gap-2">

                            <a
                                href="{{ route('admin.products.edit',$product) }}"
                                class="text-blue-600 hover:text-blue-800">
                                Edit
                            </a>

                            <form
                                action="{{ route('admin.products.destroy',$product) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin hapus produk?')">

                                @csrf
                                @method('DELETE')

                                <button class="text-red-600 hover:text-red-800">
                                    Hapus
                                </button>

                            </form>

                        </td>

                        <td class="px-4 py-3">

                            <div class="flex gap-2 flex-wrap">

                                @forelse ($product->images as $image)

                                <img
                                    src="{{ asset('storage/'.$image->image_path) }}"
                                    class="w-12 h-12 object-cover rounded border">

                                @empty

                                <span class="text-gray-400 text-xs">
                                    Tidak ada
                                </span>

                                @endforelse

                            </div>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>

    </div>


    {{-- =========================
PRODUCT MODAL
========================= --}}

    <div
        id="productModal"
        class="fixed inset-0 hidden items-center justify-center bg-black/40 z-50">

        <div class="bg-white w-full max-w-lg rounded-xl shadow-xl p-6">

            <div class="flex justify-between mb-4">

                <h3 class="font-semibold text-lg">
                    Tambah Produk
                </h3>

                <button onclick="closeProductModal()">
                    ✕
                </button>

            </div>

            <form
                id="productForm"
                action="{{ route('admin.products.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-4">

                @csrf

                <input
                    name="name"
                    placeholder="Nama produk"
                    class="w-full border rounded px-3 py-2">

                <div class="grid grid-cols-2 gap-3">

                    <input
                        type="number"
                        name="price_original"
                        placeholder="Harga"
                        class="border rounded px-3 py-2">

                    <input
                        type="number"
                        name="stock"
                        placeholder="Stok"
                        class="border rounded px-3 py-2">

                </div>

                <input
                    type="number"
                    name="weight"
                    placeholder="Berat (gram)"
                    class="w-full border rounded px-3 py-2">

                <select
                    name="category_id"
                    class="w-full border rounded px-3 py-2">

                    <option value="">
                        Pilih kategori
                    </option>

                    @foreach ($categories as $category)

                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>

                    @endforeach

                </select>

                <textarea
                    name="description"
                    rows="3"
                    placeholder="Deskripsi"
                    class="w-full border rounded px-3 py-2"></textarea>


                {{-- DROP AREA --}}

                <div
                    id="drop-area"
                    class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer">

                    <p class="text-sm text-gray-500">
                        Drag & Drop gambar
                    </p>

                    <p class="text-xs text-gray-400">
                        atau klik untuk upload
                    </p>

                    <input
                        id="imageInput"
                        type="file"
                        name="images[]"
                        multiple
                        class="hidden">

                </div>

                <div
                    id="previewContainer"
                    class="grid grid-cols-4 gap-2"></div>


                <div class="flex justify-end gap-3 pt-3">

                    <button
                        type="button"
                        onclick="closeProductModal()"
                        class="px-4 py-2 border rounded">
                        Batal
                    </button>

                    <button
                        class="px-4 py-2 bg-blue-600 text-white rounded">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>

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
        dropArea.classList.add("border-blue-400")

    }

    dropArea.ondragleave = () => {

        dropArea.classList.remove("border-blue-400")

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

        dropArea.classList.remove("border-blue-400")

    }

    function renderPreview() {

        preview.innerHTML = ""

        selectedFiles.forEach((file, index) => {

            const reader = new FileReader()

            reader.onload = e => {

                preview.innerHTML += `
<div class="relative">

<img src="${e.target.result}"
class="w-full h-20 object-cover rounded border">

<button
type="button"
onclick="removeImage(${index})"
class="absolute top-1 right-1 bg-red-500 text-white text-xs px-1 rounded">
✕
</button>

</div>
`

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

        document.getElementById("productModal").classList.add("hidden")
        document.getElementById("productModal").classList.remove("flex")

        document.getElementById("productForm").reset()

        selectedFiles = []
        preview.innerHTML = ""

    }
</script>