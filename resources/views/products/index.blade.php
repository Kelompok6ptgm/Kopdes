<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Barang - KopDes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">

    @php
        $user = Auth::user();
    @endphp

    <!-- Top Navbar (Matching Dashboard Navbar Exactly) -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Left: Logo -->
                <div class="flex items-center space-x-3 cursor-pointer" onclick="window.location.href='{{ route('dashboard') }}'">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-8 w-auto">
                    <span class="text-[#c52228] font-bold text-lg tracking-tight">KoperasiDesa</span>
                </div>

                <!-- Center: Navigation Links Centered -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('dashboard') }}" class="font-semibold text-sm text-gray-600 hover:text-[#c52228] transition-all">Katalog Koperasi</a>
                    <a href="{{ route('products.index', ['kopdes_id' => $selectedKopdes->id_kopdes ?? '']) }}" class="font-semibold text-sm text-[#c52228] border-b-2 border-[#c52228] pb-1">Barang</a>
                    <a href="{{ route('dashboard') }}#user-history" class="font-semibold text-sm text-gray-600 hover:text-[#c52228] transition-all">Riwayat Belanja</a>
                </div>

                <!-- Right: Cart & Profile Icons & Logout Button -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}#user-carts" title="Keranjang Belanja" class="p-2 rounded-lg text-gray-600 hover:text-red-600 hover:bg-red-50 relative flex items-center transition-all cursor-pointer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        @if ($myCarts->count() > 0)
                            <span class="absolute top-0 right-0 bg-[#c52228] text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                                {{ $myCarts->count() }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('dashboard') }}#user-profile" title="Profil Saya" class="p-1 rounded-full text-gray-600 hover:text-[#c52228] transition-all flex items-center cursor-pointer">
                        @if ($user->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->foto))
                            <img src="{{ asset('storage/' . $user->foto) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-gray-200 shadow-sm">
                        @else
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-[#c52228] font-extrabold text-xs">
                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                            </div>
                        @endif
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-[#c52228] hover:bg-[#a51c21] text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header & KopDes Switcher -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="text-xs uppercase font-bold text-[#c52228] tracking-wider">Katalog Produk Lengkap</span>
                <h1 class="text-2xl font-bold text-gray-900 mt-1">{{ $selectedKopdes->nama_kopdes ?? 'Semua Barang' }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $selectedKopdes->alamat ?? '' }} (Kode Pos: {{ $selectedKopdes->kode_pos ?? '-' }})</p>
            </div>

            <!-- Search & Filter Form -->
            <form method="GET" action="{{ route('products.index') }}" class="flex flex-col sm:flex-row gap-3">
                <input type="hidden" name="kopdes_id" value="{{ $selectedKopdes->id_kopdes ?? '' }}">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama barang..." class="w-full sm:w-60 px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <select name="category_id" onchange="this.form.submit()" class="px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id_category }}" {{ $categoryId == $cat->id_category ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @forelse ($products as $p)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col justify-between p-4 space-y-3 hover:shadow-md transition-all">
                    <div class="w-full h-44 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden relative group">
                        @if ($p->gambar)
                            <img src="{{ asset($p->gambar) }}" alt="Gambar Barang" class="w-full h-full object-cover">
                        @else
                            <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">{{ $p->nama_produk }}</span>
                        @endif
                        <button onclick="openDetailModal('{{ $p->nama_produk }}', '{{ number_format($p->harga, 0, ',', '.') }}', '{{ $p->stok }}', '{{ $p->deskripsi }}', '{{ $p->category->nama_kategori ?? 'Umum' }}')" class="absolute inset-0 bg-black/40 text-white font-bold text-xs opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center backdrop-blur-xs">
                            Detail Produk
                        </button>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase font-bold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ $p->category->nama_kategori ?? 'Umum' }}</span>
                        <h3 class="font-bold text-gray-900 text-base mt-2 line-clamp-1">{{ $p->nama_produk }}</h3>
                        <p class="text-xs text-gray-500 line-clamp-2 mt-1">{{ $p->deskripsi }}</p>
                    </div>
                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] text-gray-400 block font-semibold">Harga</span>
                            <span class="font-bold text-[#c52228] text-base">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                        </div>
                        <button class="bg-[#c52228] hover:bg-[#a51c21] text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition-all cursor-pointer">
                            + Beli
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-16 bg-white rounded-xl border border-gray-200">
                    <p class="text-gray-400 font-medium">Tidak ada produk yang ditemukan untuk kriteria ini.</p>
                </div>
            @endforelse
        </div>
    </main>

    <!-- Detail Barang Modal -->
    <div id="detail-modal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-5 shadow-2xl relative animate-in fade-in zoom-in-95">
            <button onclick="closeDetailModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
            <div>
                <span id="modal-category" class="text-[10px] uppercase font-bold text-red-600 bg-red-50 px-2.5 py-1 rounded-full"></span>
                <h3 id="modal-title" class="text-xl font-bold text-gray-900 mt-2"></h3>
            </div>
            <div class="bg-gray-50 p-4 rounded-xl space-y-2 border border-gray-100">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Harga Per Unit:</span>
                    <span id="modal-price" class="font-bold text-[#c52228] text-base"></span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Stok Tersedia:</span>
                    <span id="modal-stock" class="font-bold text-gray-900"></span>
                </div>
            </div>
            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Deskripsi Produk</h4>
                <p id="modal-desc" class="text-sm text-gray-600 leading-relaxed"></p>
            </div>
            <div class="pt-4 border-t border-gray-100 flex space-x-3">
                <button onclick="closeDetailModal()" class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 rounded-xl text-sm transition-all">Tutup</button>
                <button class="w-1/2 bg-[#c52228] hover:bg-[#a51c21] text-white font-bold py-2.5 rounded-xl text-sm shadow-md transition-all">+ Tambah Keranjang</button>
            </div>
        </div>
    </div>

    <script>
        function openDetailModal(title, price, stock, desc, category) {
            document.getElementById('modal-title').innerText = title;
            document.getElementById('modal-price').innerText = 'Rp ' + price;
            document.getElementById('modal-stock').innerText = stock + ' pcs';
            document.getElementById('modal-desc').innerText = desc || 'Tidak ada deskripsi.';
            document.getElementById('modal-category').innerText = category;

            const modal = document.getElementById('detail-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDetailModal() {
            const modal = document.getElementById('detail-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</body>
</html>
