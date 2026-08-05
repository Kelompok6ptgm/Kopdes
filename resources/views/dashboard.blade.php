<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Manager - KopDes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

    <!-- Top Navbar -->
    <nav class="bg-white border-b-2 border-red-600 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-3">
                    <span class="bg-red-600 text-white font-black px-3 py-1 rounded text-xl">KOPDES</span>
                    <span class="font-bold text-gray-700 hidden sm:inline">Panel Manager KopDes</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-semibold text-gray-600">Halo, <strong class="text-red-600">{{ Auth::user()->nama ?? 'Manager' }}</strong></span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm font-bold px-4 py-2 rounded-lg shadow transition">
                            <i class="fa-solid fa-right-from-bracket mr-1"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        
        <!-- Notifikasi Sukses -->
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
            <p class="font-bold">Berhasil!</p>
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <!-- Banner Selamat Datang -->
        <div class="bg-gradient-to-r from-red-600 to-red-800 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-3xl font-extrabold mb-1">Pusat Kontrol Manager KopDes</h1>
                <p class="text-red-100 text-sm">Kelola produk, kategori, dan pesanan pelanggan langsung dari satu halaman dashboard.</p>
            </div>
            <div class="mt-4 md:mt-0 bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl border border-white/20 text-center">
                <span class="block text-xs uppercase tracking-wider text-red-200">Role Akses</span>
                <span class="font-bold text-yellow-300"><i class="fa-solid fa-user-shield mr-1"></i> MANAGER</span>
            </div>
        </div>

        <!-- GRID UTAMA: KONTEN CRUD & TRANSAKSI -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- KOLOM KIRI: FORM TAMBAH KATEGORI & PRODUK -->
            <div class="space-y-8">
                
                <!-- Form Tambah Kategori -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b flex items-center">
                        <i class="fa-solid fa-tags text-red-600 mr-2"></i> Tambah Kategori Baru
                    </h2>
                    <form action="{{ route('manager.categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-xs font-bold mb-1 uppercase">Nama Kategori</label>
                            <input type="text" name="nama_kategori" required placeholder="Contoh: Sembako, Pupuk" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-red-500">
                        </div>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow transition">
                            Simpan Kategori
                        </button>
                    </form>
                </div>

                <!-- Form Tambah Produk -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b flex items-center">
                        <i class="fa-solid fa-box-open text-red-600 mr-2"></i> Tambah Produk Baru
                    </h2>
                    <form action="{{ route('manager.products.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1 uppercase">Nama Produk</label>
                            <input type="text" name="nama_produk" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-red-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1 uppercase">Kategori</label>
                            <select name="id_category" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-red-500">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id_category }}">{{ $cat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-gray-700 text-xs font-bold mb-1 uppercase">Harga (Rp)</label>
                                <input type="number" name="harga" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-red-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-xs font-bold mb-1 uppercase">Stok</label>
                                <input type="number" name="stok" required class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-red-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-xs font-bold mb-1 uppercase">Deskripsi</label>
                            <textarea name="deskripsi" rows="2" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-red-500"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow transition">
                            Simpan Produk
                        </button>
                    </form>
                </div>

            </div>

            <!-- KOLOM KANAN: TABEL KATEGORI, PRODUK, & PESANAN -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Tabel Kategori -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b flex items-center">
                        <i class="fa-solid fa-list text-red-600 mr-2"></i> Daftar Kategori KopDes
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-red-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-bold text-red-700">Nama Kategori</th>
                                    <th class="px-4 py-2 text-center font-bold text-red-700 w-32">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($categories as $cat)
                                    <tr>
                                        <td class="px-4 py-3 font-medium">{{ $cat->nama_kategori }}</td>
                                        <td class="px-4 py-3 text-center space-x-2">
                                            <a href="{{ route('manager.categories.edit', $cat->id_category) }}" class="text-blue-600 hover:bg-blue-50 px-2 py-1 rounded">Edit</a>
                                            <form action="{{ route('manager.categories.destroy', $cat->id_category) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:bg-red-50 px-2 py-1 rounded">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="2" class="px-4 py-4 text-center text-gray-400 italic">Belum ada kategori.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tabel Produk -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b flex items-center">
                        <i class="fa-solid fa-boxes-stacked text-red-600 mr-2"></i> Daftar Produk KopDes
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-red-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-bold text-red-700">Nama Produk</th>
                                    <th class="px-4 py-2 text-left font-bold text-red-700">Kategori</th>
                                    <th class="px-4 py-2 text-left font-bold text-red-700">Harga</th>
                                    <th class="px-4 py-2 text-left font-bold text-red-700">Stok</th>
                                    <th class="px-4 py-2 text-center font-bold text-red-700 w-32">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($products as $item)
                                    <tr>
                                        <td class="px-4 py-3 font-medium">{{ $item->nama_produk }}</td>
                                        <td class="px-4 py-3 text-gray-500">{{ $item->category->nama_kategori ?? '-' }}</td>
                                        <td class="px-4 py-3 text-gray-500">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-gray-500">{{ $item->stok }}</td>
                                        <td class="px-4 py-3 text-center space-x-2">
                                            <a href="{{ route('manager.products.edit', $item->id) }}" class="text-blue-600 hover:bg-blue-50 px-2 py-1 rounded">Edit</a>
                                            <form action="{{ route('manager.products.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:bg-red-50 px-2 py-1 rounded">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-4 py-4 text-center text-gray-400 italic">Belum ada produk.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tabel Pesanan & Verifikasi Pembayaran -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b flex items-center">
                        <i class="fa-solid fa-receipt text-red-600 mr-2"></i> Kelola Pesanan & Pembayaran
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-red-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-bold text-red-700">ID / Pelanggan</th>
                                    <th class="px-4 py-2 text-left font-bold text-red-700">Total</th>
                                    <th class="px-4 py-2 text-left font-bold text-red-700">Status Pembayaran</th>
                                    <th class="px-4 py-2 text-left font-bold text-red-700">Status Pesanan</th>
                                    <th class="px-4 py-2 text-center font-bold text-red-700 w-48">Aksi / Ubah Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($transactions ?? [] as $trx)
                                    <tr>
                                        <td class="px-4 py-3 font-medium">
                                            #{{ $trx->id }} <br>
                                            <span class="text-xs text-gray-400">{{ $trx->nama_pelanggan ?? 'Pelanggan' }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-gray-500">Rp {{ number_format($trx->total_harga ?? 0, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs font-bold rounded {{ ($trx->status_pembayaran ?? '') == 'Verified' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                {{ $trx->status_pembayaran ?? 'Menunggu' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs font-bold rounded bg-blue-100 text-blue-700">
                                                {{ $trx->status ?? 'Diproses' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <form action="{{ route('manager.transactions.updateStatus', $trx->id) }}" method="POST" class="flex items-center space-x-1">
                                                @csrf @method('PUT')
                                                <select name="status" class="border rounded text-xs px-2 py-1 focus:outline-none">
                                                    <option value="Diproses" {{ ($trx->status ?? '') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                                    <option value="Dikirim" {{ ($trx->status ?? '') == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                                                    <option value="Selesai" {{ ($trx->status ?? '') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                                </select>
                                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700">Update</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-4 py-4 text-center text-gray-400 italic">Belum ada pesanan masuk.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

    </div>

</body>
</html>