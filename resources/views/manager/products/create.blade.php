<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - KopDes</title>
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
        <div class="bg-white shadow rounded-lg p-6 border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Tambah Produk Baru</h2>
            
            <form action="{{ route('manager.products.store') }}" method="POST">
                @csrf <!-- Wajib ada di Laravel biar aman -->

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Produk</label>
                    <input type="text" name="nama_produk" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                    <select name="id_category" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id_category }}">{{ $cat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-4 mb-4">
                    <div class="w-1/2">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Harga (Rp)</label>
                        <input type="number" name="harga" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Stok Barang</label>
                        <input type="number" name="stok" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Produk</label>
                    <textarea name="deskripsi" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"></textarea>
                </div>

                <div class="flex items-center justify-end">
                    <a href="{{ route('manager.products.index') }}" class="text-gray-500 hover:text-gray-700 font-bold py-2 px-4 mr-4">Batal</a>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded shadow">
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>