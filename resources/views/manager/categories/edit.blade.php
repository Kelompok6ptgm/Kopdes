<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori - KopDes</title>
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
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Edit Kategori</h2>

            <form action="{{ route('manager.categories.update', $category->id_category) }}" method="POST">
                @csrf
                @method('PUT')

                @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    @foreach($errors->all() as $err)
                    <p>{{ $err }}</p>
                    @endforeach
                </div>
                @endif

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kategori</label>
                    <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $category->nama_kategori) }}" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:border-red-500">
                </div>

                <div class="flex items-center justify-end">
                    <a href="{{ route('manager.categories.index') }}" class="text-gray-500 hover:text-gray-700 font-bold py-2 px-4 mr-4">Batal</a>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded shadow">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
