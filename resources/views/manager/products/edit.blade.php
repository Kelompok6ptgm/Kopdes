<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - KopDes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar Simple Tema Merah -->
    <nav class="bg-white shadow border-b-2 border-red-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="font-bold text-2xl text-red-600">KOPDES</span>
                    <span class="ml-2 text-sm text-gray-500">| Manager Panel</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Konten Form -->
    <div class="max-w-3xl mx-auto px-4 py-8">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            @foreach($errors->all() as $err)
            <p>{{ $err }}</p>
            @endforeach
        </div>
        @endif

        <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Edit Produk</h2>

            <form action="{{ route('manager.products.update', $product->id_product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if($product->gambar)
                <div class="mb-4">
                    <img src="{{ asset($product->gambar) }}" alt="Gambar Produk" class="w-32 h-32 object-cover rounded-lg border">
                </div>
                @endif

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Produk</label>
                    <input type="text" name="nama_produk" value="{{ old('nama_produk', $product->nama_produk) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-red-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                    <select name="id_category" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-red-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id_category }}" {{ $product->id_category == $cat->id_category ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-4 mb-4">
                    <div class="w-1/2">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Harga (Rp)</label>
                        <input type="number" name="harga" value="{{ old('harga', $product->harga) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-red-500">
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Stok Barang</label>
                        <input type="number" name="stok" value="{{ old('stok', $product->stok) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-red-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Produk</label>
                    <textarea name="deskripsi" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-red-500">{{ old('deskripsi', $product->deskripsi) }}</textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ganti Foto Produk</label>
                    <input type="file" name="foto" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-red-500">
                </div>

                <div class="flex items-center justify-end">
                    <a href="{{ route('manager.products.index') }}" class="text-gray-500 hover:text-gray-700 font-bold py-2 px-4 mr-4">Batal</a>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded shadow">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
