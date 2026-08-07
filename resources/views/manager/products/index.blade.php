<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - KopDes</title>
    <!-- Pastikan Tailwind udah jalan dari setup awal temen lo -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- Navbar Simple Tema Merah (Sesuai Gambar Lo) -->
    <nav class="bg-white shadow border-b-2 border-red-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="font-bold text-2xl text-red-600">KOPDES</span>
                    <span class="ml-2 text-sm text-gray-500">| Manager Panel</span>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="#" class="text-gray-600 hover:text-red-600 font-medium">Home</a>
                    <a href="#" class="text-red-600 border-b-2 border-red-600 font-medium">Products</a>
                    <a href="#" class="text-gray-600 hover:text-red-600 font-medium">Transaction</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Pesan Sukses -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Daftar Produk Anda</h1>
            <!-- Tombol Tambah Produk -->
            <a href="{{ route('manager.products.create') }}"
                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow">
                + Tambah Produk
            </a>
        </div>

        <!-- Tabel Produk -->
        <div class="bg-white shadow rounded-lg overflow-hidden border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-red-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-red-700 uppercase tracking-wider">Nama
                            Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-red-700 uppercase tracking-wider">Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-red-700 uppercase tracking-wider">Harga
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-red-700 uppercase tracking-wider">Stok
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Nanti data dari database di-loop di sini -->
                    @forelse ($products as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $item->nama_produk ?? $item->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->category->nama_kategori ?? 'Tanpa Kategori' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Rp {{ number_format($item->harga ?? $item->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->stok ?? $item->stock }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium flex justify-center space-x-3">
                                <!-- Tombol Edit -->
                                <a href="{{ route('manager.products.edit', $item->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded">Edit</a>
                                
                                <!-- Tombol Hapus -->
                                <form action="{{ route('manager.products.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 italic">
                                Belum ada produk di KopDes Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>