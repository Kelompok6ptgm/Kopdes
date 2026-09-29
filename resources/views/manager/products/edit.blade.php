<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - KopDes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar / Header Utama (Seragam) -->
    <nav class="bg-white shadow border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <div class="bg-red-600 p-2 rounded-lg text-white">
                        <i class="fa-solid fa-store text-lg"></i>
                    </div>
                    <div>
                        <span class="font-bold text-xl text-red-600 tracking-wide">KopDes</span>
                        <span class="text-xs text-gray-500 block font-medium">Manager Panel</span>
                    </div>
                </div>

                <!-- Tombol Navigasi Kembali -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('manager.products.index') }}" class="text-sm font-semibold text-gray-600 hover:text-red-600 transition">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Konten Form Edit Produk -->
    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white shadow-sm rounded-xl p-8 border border-gray-200">
            <div class="flex items-center justify-between mb-6 border-b pb-4">
                <h2 class="text-2xl font-bold text-gray-800">Edit Produk</h2>
                <span class="text-xs bg-red-50 text-red-600 font-semibold px-3 py-1 rounded-full border border-red-100">Form Pembaruan Produk</span>
            </div>

            <form action="{{ route('manager.products.update', $product->id_product ?? $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6 text-sm" role="alert">
                    <p class="font-bold mb-1">Terjadi Kesalahan:</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Nama Produk -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Produk</label>
                    <input type="text" name="nama_produk" value="{{ old('nama_produk', $product->nama_produk ?? $product->name) }}" required
                        class="shadow-sm border rounded-lg w-full py-2.5 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>

                <!-- Kategori -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                    <select name="id_category" required
                        class="shadow-sm border rounded-lg w-full py-2.5 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id_category ?? $cat->id }}" {{ (isset($product) && ($product->id_category == $cat->id_category || $product->category_id == $cat->id)) ? 'selected' : '' }}>
                                {{ $cat->nama_kategori ?? $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Harga & Stok (Grid 2 Kolom) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Harga (Rp)</label>
                        <input type="number" name="harga" value="{{ old('harga', $product->harga ?? $product->price) }}" required
                            class="shadow-sm border rounded-lg w-full py-2.5 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Stok Barang</label>
                        <input type="number" name="stok" value="{{ old('stok', $product->stok ?? $product->stock) }}" required
                            class="shadow-sm border rounded-lg w-full py-2.5 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>
                </div>

                <!-- Deskripsi Produk -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Produk</label>
                    <textarea name="deskripsi" rows="3"
                        class="shadow-sm border rounded-lg w-full py-2.5 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">{{ old('deskripsi', $product->deskripsi ?? $product->description) }}</textarea>
                </div>

                <!-- Ganti Foto Produk -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ganti Foto Produk (Opsional)</label>
                    <input type="file" name="foto" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition">
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                    <a href="{{ route('manager.products.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg shadow transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>