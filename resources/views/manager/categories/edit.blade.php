<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori - KopDes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar / Header Utama (Seragam dengan Dashboard) -->
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

                <!-- Info User & Tombol Kembali -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('manager.categories.index') }}" class="text-sm font-semibold text-gray-600 hover:text-red-600 transition">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Konten Form -->
    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white shadow-sm rounded-xl p-8 border border-gray-200">
            <div class="flex items-center justify-between mb-6 border-b pb-4">
                <h2 class="text-2xl font-bold text-gray-800">Edit Kategori</h2>
                <span class="text-xs bg-red-50 text-red-600 font-semibold px-3 py-1 rounded-full border border-red-100">Form Pembaruan</span>
            </div>

            <form action="{{ route('manager.categories.update', $category->id_category) }}" method="POST">
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

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kategori</label>
                    <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $category->nama_kategori) }}" required
                        class="shadow-sm appearance-none border rounded-lg w-full py-2.5 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                    <a href="{{ route('manager.categories.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg transition">
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