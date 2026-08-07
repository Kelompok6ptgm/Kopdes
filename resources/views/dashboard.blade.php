<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
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
                <p class="text-red-100 text-sm">Kelola produk, kategori, pesanan, laporan, dan ulasan pelanggan langsung dari satu halaman dashboard.</p>
            </div>
            <div class="mt-4 md:mt-0 bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl border border-white/20 text-center">
                <span class="block text-xs uppercase tracking-wider text-red-200">Role Akses</span>
                <span class="font-bold text-yellow-300"><i class="fa-solid fa-user-shield mr-1"></i> MANAGER</span>
=======
    <title>Dashboard Kopdes</title>
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
        $roleName = $user ? ($user->role->nama_role ?? 'user') : 'guest';
    @endphp

    <!-- Backdrop Overlay for Mobile Sidebars -->
    <div id="mobile-sidebar-backdrop" onclick="closeMobileSidebar()" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden transition-opacity"></div>

    <!-- ============================================== -->
    <!-- 1. ADMIN DASHBOARD (RESPONSIVE WHITE SIDEBAR) -->
    <!-- ============================================== -->
    @if ($roleName === 'admin')
        @php
            $totalKopdes = \App\Models\Kopdes::count();
            $totalManager = \App\Models\User::where('id_role', 2)->count();
            $totalTransactions = \App\Models\Transaction::count();
            $totalEarnings = \App\Models\Transaction::where('status_transaksi', 'selesai')->sum('total_harga');

            $allKopdes = \App\Models\Kopdes::latest()->get();
            $allTransactions = \App\Models\Transaction::with(['user', 'kopdes'])->latest()->get();
            $allPayments = \App\Models\Payment::with(['transaction.user', 'transaction.kopdes'])->latest()->get();
            $allManagers = \App\Models\User::where('id_role', 2)->with('kopdes')->latest()->get();
        @endphp

        <!-- Mobile Top Navbar for Admin -->
        <div class="md:hidden bg-white border-b border-gray-200 p-4 fixed top-0 inset-x-0 z-20 flex justify-between items-center shadow-xs">
            <div class="flex items-center space-x-3">
                <button onclick="toggleMobileSidebar()" class="p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <span class="font-bold text-base text-[#c52228]">Admin Platform</span>
            </div>
            <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full uppercase">{{ $user->nama }}</span>
        </div>

        <div class="flex min-h-screen">
            <!-- Sidebar (Responsive Mobile Drawer) -->
            <aside id="sidebar-menu" class="w-64 bg-white text-gray-800 border-r border-gray-200 flex flex-col fixed inset-y-0 left-0 z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">
                <div class="p-6 flex items-center justify-between border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-8 w-auto">
                        <span class="font-bold text-lg text-[#c52228]">Admin Platform</span>
                    </div>
                    <button onclick="closeMobileSidebar()" class="md:hidden text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
                    <button onclick="switchTabAdmin('admin-overview', this)" class="tab-btn-admin w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all bg-red-50 text-[#c52228] flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                        Ringkasan
                    </button>
                    <button onclick="switchTabAdmin('admin-kopdes', this)" class="tab-btn-admin w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Kelola KopDes
                    </button>
                    <button onclick="switchTabAdmin('admin-managers', this)" class="tab-btn-admin w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Kelola Manager
                    </button>
                    <button onclick="switchTabAdmin('admin-transactions', this)" class="tab-btn-admin w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 00-2 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Semua Transaksi
                    </button>
                    <button onclick="switchTabAdmin('admin-payments', this)" class="tab-btn-admin w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Semua Pembayaran
                    </button>
                </nav>
                <div class="p-4 border-t border-gray-100">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-[#c52228] hover:bg-[#a51c21] text-white py-2 px-4 rounded-lg text-sm font-semibold transition-all cursor-pointer">
                            Keluar
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Panel -->
            <main class="flex-1 ml-0 md:ml-64 p-4 md:p-8 pt-20 md:pt-8 w-full overflow-x-hidden">
                <!-- Header -->
                <header class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-gray-200 mb-8 gap-4">
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-gray-900">Platform Administrator</h1>
                        <p class="text-xs md:text-sm text-gray-500">Mengelola Koperasi Desa dan Pengawas Toko</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="text-xs font-semibold text-gray-700 bg-gray-100 px-3 py-1 rounded-full uppercase">Admin: {{ $user->nama }}</span>
                    </div>
                </header>

                <!-- SECTION: RINGKASAN -->
                <section id="admin-overview" class="tab-content space-y-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-xs">
                            <span class="text-xs md:text-sm font-medium text-gray-400">Total KopDes</span>
                            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mt-2">{{ $totalKopdes }}</h3>
                        </div>
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-xs">
                            <span class="text-xs md:text-sm font-medium text-gray-400">Total Manager</span>
                            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mt-2">{{ $totalManager }}</h3>
                        </div>
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-xs">
                            <span class="text-xs md:text-sm font-medium text-gray-400">Total Transaksi</span>
                            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mt-2">{{ $totalTransactions }}</h3>
                        </div>
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-xs">
                            <span class="text-xs md:text-sm font-medium text-gray-400">Total Omset Platform</span>
                            <h3 class="text-2xl md:text-3xl font-bold text-red-600 mt-2">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 shadow-xs p-6">
                        <h3 class="font-bold text-base md:text-lg text-gray-900 mb-2">Laporan Keseluruhan Platform</h3>
                        <p class="text-xs md:text-sm text-gray-600">Dashboard Admin menyajikan metrik operasional seluruh Koperasi Desa. Gunakan menu sidebar untuk navigasi.</p>
                    </div>
                </section>

                <!-- SECTION: KELOLA KOPDES -->
                <section id="admin-kopdes" class="tab-content hidden space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <h3 class="font-bold text-lg md:text-xl text-gray-900">Daftar Koperasi Desa (KopDes)</h3>
                        <button class="bg-[#c52228] hover:bg-[#a51c21] text-white px-4 py-2 rounded-lg text-xs md:text-sm font-semibold shadow-xs cursor-pointer">+ Tambah KopDes</button>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[600px]">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                    <th class="p-4">Nama KopDes</th>
                                    <th class="p-4">Alamat</th>
                                    <th class="p-4">Kode Pos</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-150 text-sm">
                                @forelse ($allKopdes as $kd)
                                    <tr>
                                        <td class="p-4 font-semibold text-gray-900">{{ $kd->nama_kopdes }}</td>
                                        <td class="p-4 text-gray-600">{{ $kd->alamat }}</td>
                                        <td class="p-4 text-gray-600">{{ $kd->kode_pos ?? '-' }}</td>
                                        <td class="p-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">{{ $kd->status }}</span>
                                        </td>
                                        <td class="p-4 flex space-x-2">
                                            <button class="text-blue-600 font-semibold hover:underline">Edit</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="p-8 text-center text-gray-400">Belum ada data KopDes.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- SECTION: KELOLA MANAGERS -->
                <section id="admin-managers" class="tab-content hidden space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <h3 class="font-bold text-lg md:text-xl text-gray-900">Kelola Akun Manager</h3>
                        <button class="bg-[#c52228] hover:bg-[#a51c21] text-white px-4 py-2 rounded-lg text-xs md:text-sm font-semibold shadow-xs cursor-pointer">+ Tambah Manager</button>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[600px]">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                    <th class="p-4">Nama</th>
                                    <th class="p-4">Email</th>
                                    <th class="p-4">KopDes</th>
                                    <th class="p-4">No. HP</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-150 text-sm">
                                @forelse ($allManagers as $m)
                                    <tr>
                                        <td class="p-4 font-semibold text-gray-900">{{ $m->nama }}</td>
                                        <td class="p-4 text-gray-600">{{ $m->email }}</td>
                                        <td class="p-4 font-semibold text-[#c52228]">{{ $m->kopdes->nama_kopdes ?? 'Belum Ditugaskan' }}</td>
                                        <td class="p-4 text-gray-600">{{ $m->no_hp }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="p-8 text-center text-gray-400">Belum ada Manager.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- SECTION: SEMUA TRANSAKSI -->
                <section id="admin-transactions" class="tab-content hidden space-y-6">
                    <h3 class="font-bold text-lg md:text-xl text-gray-900">Semua Transaksi Koperasi</h3>
                    <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[600px]">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                    <th class="p-4">Kode Transaksi</th>
                                    <th class="p-4">KopDes</th>
                                    <th class="p-4">Pelanggan</th>
                                    <th class="p-4">Total</th>
                                    <th class="p-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-150 text-sm">
                                @forelse ($allTransactions as $t)
                                    <tr>
                                        <td class="p-4 font-semibold text-gray-900">{{ $t->kode_transaksi }}</td>
                                        <td class="p-4 text-gray-600">{{ $t->kopdes->nama_kopdes ?? 'N/A' }}</td>
                                        <td class="p-4 text-gray-600">{{ $t->user->nama ?? 'N/A' }}</td>
                                        <td class="p-4 font-bold">Rp {{ number_format($t->total_harga, 0, ',', '.') }}</td>
                                        <td class="p-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">{{ $t->status_transaksi }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="p-8 text-center text-gray-400">Belum ada transaksi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- SECTION: SEMUA PEMBAYARAN -->
                <section id="admin-payments" class="tab-content hidden space-y-6">
                    <h3 class="font-bold text-lg md:text-xl text-gray-900">Semua Pembayaran Masuk</h3>
                    <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[600px]">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                    <th class="p-4">Kode Transaksi</th>
                                    <th class="p-4">Jumlah</th>
                                    <th class="p-4">Metode</th>
                                    <th class="p-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-150 text-sm">
                                @forelse ($allPayments as $p)
                                    <tr>
                                        <td class="p-4 font-semibold text-gray-900">{{ $p->transaction->kode_transaksi ?? 'N/A' }}</td>
                                        <td class="p-4 font-bold">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                                        <td class="p-4 text-gray-600 uppercase">{{ $p->metode_pembayaran }}</td>
                                        <td class="p-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">{{ $p->status_pembayaran }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="p-8 text-center text-gray-400">Belum ada pembayaran.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>
    @endif


    <!-- ============================================== -->
    <!-- 2. MANAGER DASHBOARD (RESPONSIVE WHITE SIDEBAR)-->
    <!-- ============================================== -->
    @if ($roleName === 'manager')
        @php
            $kopdes = $user->kopdes;
            $hasKopdes = (bool)$kopdes;

            $totalProducts = $hasKopdes ? $kopdes->products()->count() : 0;
            $kopdesEarnings = $hasKopdes ? $kopdes->transactions()->where('status_transaksi', 'selesai')->sum('total_harga') : 0;
            $processingOrders = $hasKopdes ? $kopdes->transactions()->where('status_transaksi', 'diproses')->count() : 0;

            $myProducts = $hasKopdes ? $kopdes->products()->with('category')->get() : collect();
            $myCategories = $hasKopdes ? $kopdes->categories()->get() : collect();
            $myTransactions = $hasKopdes ? $kopdes->transactions()->with('user')->latest()->get() : collect();
            $myPayments = $hasKopdes ? \App\Models\Payment::whereHas('transaction', fn($q) => $q->where('id_kopdes', $kopdes->id_kopdes))->with('transaction.user')->latest()->get() : collect();
            $myReviews = $hasKopdes ? \App\Models\Review::whereHas('product', fn($q) => $q->where('id_kopdes', $kopdes->id_kopdes))->with(['user', 'product'])->latest()->get() : collect();
            $myMembers = $hasKopdes ? \App\Models\User::where('id_kopdes', $kopdes->id_kopdes)->where('id_role', 3)->latest()->get() : collect();
        @endphp

        <!-- Mobile Top Navbar for Manager -->
        <div class="md:hidden bg-white border-b border-gray-200 p-4 fixed top-0 inset-x-0 z-20 flex justify-between items-center shadow-xs">
            <div class="flex items-center space-x-3">
                <button onclick="toggleMobileSidebar()" class="p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <span class="font-bold text-base text-gray-900 truncate">{{ $hasKopdes ? $kopdes->nama_kopdes : 'Manager' }}</span>
            </div>
            <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full uppercase">Manager</span>
        </div>

        <div class="flex min-h-screen">
            <!-- Sidebar (Responsive Mobile Drawer) -->
            <aside id="sidebar-menu" class="w-64 bg-white text-gray-800 border-r border-gray-200 flex flex-col fixed inset-y-0 left-0 z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">
                <div class="p-6 flex items-center justify-between border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-8 w-auto">
                        <span class="font-bold text-base text-gray-900 truncate">{{ $hasKopdes ? $kopdes->nama_kopdes : 'KopDes Manager' }}</span>
                    </div>
                    <button onclick="closeMobileSidebar()" class="md:hidden text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
                    <button onclick="switchTabAdmin('mgr-overview', this)" class="tab-btn-admin w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all bg-red-50 text-[#c52228] flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Ringkasan
                    </button>
                    <button onclick="switchTabAdmin('mgr-products', this)" class="tab-btn-admin w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Produk & Stok
                    </button>
                    <button onclick="switchTabAdmin('mgr-categories', this)" class="tab-btn-admin w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Kategori Produk
                    </button>
                    <button onclick="switchTabAdmin('mgr-orders', this)" class="tab-btn-admin w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Pesanan Pelanggan
                    </button>
                    <button onclick="switchTabAdmin('mgr-payments', this)" class="tab-btn-admin w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Verifikasi Bayar
                    </button>
                    <button onclick="switchTabAdmin('mgr-reviews', this)" class="tab-btn-admin w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        Ulasan Produk
                    </button>
                    <button onclick="switchTabAdmin('mgr-members', this)" class="tab-btn-admin w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>Daftar Anggota</span>
                        @php
                            $pendingResetsCount = $myMembers->where('reset_requested', true)->count();
                        @endphp
                        @if ($pendingResetsCount > 0)
                            <span class="ml-auto bg-red-600 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full animate-pulse">{{ $pendingResetsCount }}</span>
                        @endif
                    </button>
                </nav>
                <div class="p-4 border-t border-gray-100">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-[#c52228] hover:bg-[#a51c21] text-white py-2 px-4 rounded-lg text-sm font-semibold transition-all cursor-pointer">
                            Keluar
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Panel -->
            <main class="flex-1 ml-0 md:ml-64 p-4 md:p-8 pt-20 md:pt-8 w-full overflow-x-hidden">
                @if (!$hasKopdes)
                    <div class="bg-yellow-50 text-yellow-800 p-6 rounded-xl border border-yellow-100 shadow-xs">
                        <h2 class="font-bold text-lg">Penempatan KopDes Belum Ditugaskan</h2>
                        <p class="text-xs md:text-sm mt-1">Anda belum ditugaskan untuk mengelola Koperasi Desa manapun oleh Administrator.</p>
                    </div>
                @else
                    <!-- Header -->
                    <header class="flex flex-col md:flex-row md:items-center justify-between pb-6 border-b border-gray-200 mb-8 gap-4">
                        <div>
                            <h1 class="text-xl md:text-2xl font-bold text-gray-900">{{ $kopdes->nama_kopdes }}</h1>
                            <p class="text-xs md:text-sm text-gray-500">Alamat: {{ $kopdes->alamat }} (Kode Pos: {{ $kopdes->kode_pos ?? '-' }})</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="text-xs font-semibold text-gray-700 bg-gray-100 px-3 py-1 rounded-full">Manager: {{ $user->nama }}</span>
                        </div>
                    </header>

                    <!-- SECTION: OVERVIEW -->
                    <section id="mgr-overview" class="tab-content space-y-8">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
                            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-xs">
                                <span class="text-xs md:text-sm font-medium text-gray-400">Total Produk Aktif</span>
                                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mt-2">{{ $totalProducts }}</h3>
                            </div>
                            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-xs">
                                <span class="text-xs md:text-sm font-medium text-gray-400">Pendapatan Koperasi</span>
                                <h3 class="text-2xl md:text-3xl font-bold text-green-600 mt-2">Rp {{ number_format($kopdesEarnings, 0, ',', '.') }}</h3>
                            </div>
                            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-xs">
                                <span class="text-xs md:text-sm font-medium text-gray-400">Pesanan Diproses</span>
                                <h3 class="text-2xl md:text-3xl font-bold text-red-600 mt-2">{{ $processingOrders }}</h3>
                            </div>
                        </div>
                    </section>

                    <!-- SECTION: PRODUK & STOK -->
                    <section id="mgr-products" class="tab-content hidden space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <h3 class="font-bold text-lg md:text-xl text-gray-900">Katalog Produk & Stok</h3>
                            <button class="bg-[#c52228] hover:bg-[#a51c21] text-white px-4 py-2 rounded-lg text-xs md:text-sm font-semibold shadow-xs cursor-pointer">+ Tambah Produk</button>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[600px]">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                        <th class="p-4">Produk</th>
                                        <th class="p-4">Kategori</th>
                                        <th class="p-4">Harga</th>
                                        <th class="p-4">Stok</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-150 text-sm">
                                    @forelse ($myProducts as $prod)
                                        <tr>
                                            <td class="p-4 font-semibold text-gray-900">{{ $prod->nama_produk }}</td>
                                            <td class="p-4 text-gray-600">{{ $prod->category->nama_kategori ?? 'Umum' }}</td>
                                            <td class="p-4 font-semibold">Rp {{ number_format($prod->harga, 0, ',', '.') }}</td>
                                            <td class="p-4 font-bold text-blue-600">{{ $prod->stok }} pcs</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="p-8 text-center text-gray-400">Belum ada produk.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- SECTION: KATEGORI PRODUK -->
                    <section id="mgr-categories" class="tab-content hidden space-y-6">
                        <h3 class="font-bold text-lg md:text-xl text-gray-900">Kelola Kategori Produk</h3>
                        <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-x-auto max-w-md">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                        <th class="p-4">Kategori</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-150 text-sm">
                                    @forelse ($myCategories as $cat)
                                        <tr><td class="p-4 font-semibold text-gray-900">{{ $cat->nama_kategori }}</td></tr>
                                    @empty
                                        <tr><td class="p-8 text-center text-gray-400">Belum ada kategori.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- SECTION: PESANAN PELANGGAN -->
                    <section id="mgr-orders" class="tab-content hidden space-y-6">
                        <h3 class="font-bold text-lg md:text-xl text-gray-900">Pesanan Pelanggan</h3>
                        
                        @if (session('success') && Auth::user()->id_role == 2)
                            <div class="bg-green-50 text-green-700 p-4 rounded-xl text-sm border border-green-100 font-semibold shadow-xs">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if ($errors->any() && Auth::user()->id_role == 2)
                            <div class="bg-red-50 text-red-700 p-4 rounded-xl text-sm border border-red-100 font-semibold shadow-xs">
                                @foreach ($errors->all() as $err)
                                    <p>{{ $err }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[600px]">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                        <th class="p-4">Kode Transaksi</th>
                                        <th class="p-4">Pelanggan</th>
                                        <th class="p-4">Total</th>
                                        <th class="p-4">Status</th>
                                        <th class="p-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-150 text-sm">
                                    @forelse ($myTransactions as $trx)
                                        <tr>
                                            <td class="p-4 font-semibold text-gray-900">{{ $trx->kode_transaksi }}</td>
                                            <td class="p-4 text-gray-600">{{ $trx->user->nama ?? 'N/A' }}</td>
                                            <td class="p-4 font-bold text-gray-900">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                                            <td class="p-4">
                                                @if ($trx->status_transaksi === 'menunggu_pembayaran')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 uppercase">Belum Bayar</span>
                                                @elseif ($trx->status_transaksi === 'menunggu_verifikasi')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 uppercase">Menunggu Verifikasi</span>
                                                @elseif ($trx->status_transaksi === 'diproses')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase">Diproses</span>
                                                @elseif ($trx->status_transaksi === 'dikirim')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200 uppercase">Dikirim</span>
                                                @elseif ($trx->status_transaksi === 'selesai')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200 uppercase">Selesai</span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200 uppercase">Dibatalkan</span>
                                                @endif
                                            </td>
                                            <td class="p-4 text-right">
                                                <form action="{{ route('manager.transactions.status', $trx->id_transaction) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <select name="status" onchange="this.form.submit()" class="text-xs bg-gray-50 border border-gray-300 rounded-lg px-2.5 py-1.5 outline-none font-bold text-gray-700 cursor-pointer focus:ring-2 focus:ring-red-500">
                                                        <option value="menunggu_pembayaran" disabled {{ $trx->status_transaksi === 'menunggu_pembayaran' ? 'selected' : '' }}>Belum Bayar</option>
                                                        <option value="menunggu_verifikasi" disabled {{ $trx->status_transaksi === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                                        <option value="diproses" {{ $trx->status_transaksi === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                                        <option value="dikirim" {{ $trx->status_transaksi === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                                        <option value="selesai" {{ $trx->status_transaksi === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                        <option value="dibatalkan" {{ $trx->status_transaksi === 'dibatalkan' ? 'selected' : '' }}>Batalkan</option>
                                                    </select>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="p-8 text-center text-gray-400">Belum ada pesanan masuk.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                    <!-- SECTION: VERIFIKASI BAYAR -->
                    <section id="mgr-payments" class="tab-content hidden space-y-6">
                        <h3 class="font-bold text-lg md:text-xl text-gray-900">Verifikasi Pembayaran</h3>
                        
                        @if (session('success') && Auth::user()->id_role == 2)
                            <div class="bg-green-50 text-green-700 p-4 rounded-xl text-sm border border-green-100 font-semibold shadow-xs">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if ($errors->any() && Auth::user()->id_role == 2)
                            <div class="bg-red-50 text-red-700 p-4 rounded-xl text-sm border border-red-100 font-semibold shadow-xs">
                                @foreach ($errors->all() as $err)
                                    <p>{{ $err }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[600px]">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                        <th class="p-4">Transaksi</th>
                                        <th class="p-4">Anggota</th>
                                        <th class="p-4">Jumlah Transfer</th>
                                        <th class="p-4">Metode</th>
                                        <th class="p-4">Bukti Transfer</th>
                                        <th class="p-4">Status</th>
                                        <th class="p-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-150 text-sm">
                                    @forelse ($myPayments as $pay)
                                        <tr>
                                            <td class="p-4 font-semibold text-gray-900">{{ $pay->transaction->kode_transaksi ?? 'N/A' }}</td>
                                            <td class="p-4 text-gray-600">{{ $pay->transaction->user->nama ?? 'N/A' }}</td>
                                            <td class="p-4 font-bold text-gray-900">Rp {{ number_format($pay->jumlah_bayar, 0, ',', '.') }}</td>
                                            <td class="p-4 text-gray-500 uppercase text-xs font-semibold">{{ $pay->metode_pembayaran }}</td>
                                            <td class="p-4">
                                                @if ($pay->bukti_pembayaran)
                                                    <a href="{{ asset('storage/' . $pay->bukti_pembayaran) }}" target="_blank" class="text-blue-600 hover:underline text-xs font-semibold flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                        Lihat Bukti
                                                    </a>
                                                @else
                                                    <span class="text-gray-400 text-xs">-</span>
                                                @endif
                                            </td>
                                            <td class="p-4">
                                                @if ($pay->status_pembayaran === 'diverifikasi')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">DIVERIFIKASI</span>
                                                @elseif ($pay->status_pembayaran === 'ditolak')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">DITOLAK</span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200 animate-pulse">MENUNGGU VERIFIKASI</span>
                                                @endif
                                            </td>
                                            <td class="p-4 text-right">
                                                @if ($pay->status_pembayaran === 'menunggu_verifikasi')
                                                    <div class="flex items-center justify-end space-x-2">
                                                        <form action="{{ route('manager.payments.verify', $pay->id_payment) }}" method="POST" class="inline">
                                                            @csrf
                                                            <input type="hidden" name="action" value="approve">
                                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-2.5 py-1 rounded-lg text-xs font-bold transition-all cursor-pointer">
                                                                Setujui
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('manager.payments.verify', $pay->id_payment) }}" method="POST" class="inline">
                                                            @csrf
                                                            <input type="hidden" name="action" value="reject">
                                                            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-700 px-2.5 py-1 rounded-lg text-xs font-bold transition-all cursor-pointer">
                                                                Tolak
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 text-xs">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="7" class="p-8 text-center text-gray-400">Belum ada pembayaran masuk.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- SECTION: ULASAN PRODUK -->
                    <section id="mgr-reviews" class="tab-content hidden space-y-6">
                        <h3 class="font-bold text-lg md:text-xl text-gray-900">Ulasan & Rating Produk</h3>
                        <div class="bg-white rounded-xl border border-gray-200 shadow-xs p-6 divide-y divide-gray-150 space-y-4">
                            @forelse ($myReviews as $rev)
                                <div class="pt-4 first:pt-0">
                                    <div class="flex justify-between items-center">
                                        <h4 class="font-semibold text-gray-900 text-sm">{{ $rev->user->nama }} - <span class="text-gray-500 text-xs">{{ $rev->product->nama_produk }}</span></h4>
                                        <div class="flex text-yellow-400 text-xs">
                                            @for ($i = 0; $i < $rev->rating; $i++) ★ @endfor
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-600 mt-2">{{ $rev->komentar }}</p>
                                </div>
                            @empty
                                <p class="text-gray-400 text-center py-6 text-sm">Belum ada ulasan produk.</p>
                            @endforelse
                        </div>
                    </section>

                    <!-- SECTION: DAFTAR ANGGOTA -->
                    <section id="mgr-members" class="tab-content hidden space-y-6">
                        <h3 class="font-bold text-lg md:text-xl text-gray-900">Daftar Anggota KopDes</h3>
                        
                        @if (session('success'))
                            <div class="bg-green-50 text-green-700 p-4 rounded-xl text-sm border border-green-100 font-semibold shadow-xs">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="bg-red-50 text-red-700 p-4 rounded-xl text-sm border border-red-100 font-semibold shadow-xs">
                                @foreach ($errors->all() as $err)
                                    <p>{{ $err }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[600px]">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                        <th class="p-4">Nama Anggota</th>
                                        <th class="p-4">Email</th>
                                        <th class="p-4">No. HP</th>
                                        <th class="p-4">Kode Pos</th>
                                        <th class="p-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-150 text-sm">
                                    @forelse ($myMembers as $mb)
                                        <tr class="{{ $mb->reset_requested ? 'bg-red-50/50' : '' }}">
                                            <td class="p-4 font-semibold text-gray-900 flex items-center">
                                                {{ $mb->nama }}
                                                @if ($mb->reset_requested)
                                                    <span class="inline-flex items-center ml-2.5 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-red-100 text-red-700 border border-red-200 uppercase tracking-wider animate-pulse">Butuh Reset</span>
                                                @endif
                                            </td>
                                            <td class="p-4 text-gray-600">{{ $mb->email }}</td>
                                            <td class="p-4 text-gray-600">{{ $mb->no_hp }}</td>
                                            <td class="p-4 text-gray-600">{{ $mb->kode_pos }}</td>
                                            <td class="p-4">
                                                @if ($mb->reset_requested)
                                                    <form action="{{ route('manager.reset-password', $mb->id_user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui reset password anggota ini menjadi kopdes123?')" class="inline">
                                                        @csrf
                                                        <button type="submit" class="bg-[#c52228] hover:bg-[#a51c21] text-white shadow-xs px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer">
                                                            Setujui Reset
                                                        </button>
                                                    </form>
                                                @else
                                                    <button disabled class="bg-gray-100 text-gray-400 px-3 py-1.5 rounded-lg text-xs font-semibold cursor-not-allowed select-none">
                                                        Tidak Ada Permintaan
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="p-8 text-center text-gray-400">Belum ada anggota terdaftar.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif
            </main>
        </div>
    @endif


    <!-- ============================================== -->
    <!-- 3. USER DASHBOARD (WITH SMOOTH SLIDING NAVBAR) -->
    <!-- ============================================== -->
    @if ($roleName === 'user' || $roleName === 'guest')
        @php
            if ($roleName === 'user') {
                $userKodePos = $user->kode_pos;
                $recommendedKopdes = $user->kopdes;
                
                if (is_null($user->id_kopdes) && $userKodePos) {
                    $recommendedKopdes = \App\Models\Kopdes::where('status', 'aktif')->where('kode_pos', $userKodePos)->first();
                    if (!$recommendedKopdes) {
                        $prefix = substr($userKodePos, 0, 2);
                        $recommendedKopdes = \App\Models\Kopdes::where('status', 'aktif')->where('kode_pos', 'LIKE', $prefix . '%')->first();
                    }
                    if ($recommendedKopdes) {
                        $user->update(['id_kopdes' => $recommendedKopdes->id_kopdes]);
                    }
                }
                $myCarts = \App\Models\Cart::where('id_user', $user->id_user)->with('product')->get();
                $myHistory = \App\Models\Transaction::where('id_user', $user->id_user)->with(['kopdes', 'payment', 'details.product', 'details.review'])->latest()->get();
            } else {
                $userKodePos = null;
                $recommendedKopdes = \App\Models\Kopdes::where('status', 'aktif')->first();
                
                $sessionCart = session()->get('cart', []);
                $myCarts = collect();
                if (!empty($sessionCart)) {
                    $cartProducts = \App\Models\Product::whereIn('id_product', array_keys($sessionCart))->with('category')->get();
                    foreach ($cartProducts as $p) {
                        $myCarts->push((object)[
                            'id_product' => $p->id_product,
                            'product' => $p,
                            'quantity' => $sessionCart[$p->id_product]
                        ]);
                    }
                }
                $myHistory = collect();
            }

            $selectedKopdesId = request('kopdes_id', $recommendedKopdes ? $recommendedKopdes->id_kopdes : null);
            $selectedKopdes = $selectedKopdesId ? \App\Models\Kopdes::where('status', 'aktif')->find($selectedKopdesId) : null;

            if (!$selectedKopdes) {
                $selectedKopdes = \App\Models\Kopdes::where('status', 'aktif')->first();
                $selectedKopdesId = $selectedKopdes ? $selectedKopdes->id_kopdes : null;
            }

            $allActiveKopdes = \App\Models\Kopdes::where('status', 'aktif')->get();
            $myCatalog = $selectedKopdes ? $selectedKopdes->products()->with('category')->get() : collect();
            $myCategories = $selectedKopdes ? $selectedKopdes->categories : collect();
        @endphp

        <!-- Top Navbar (Responsive Mobile & Centered Desktop with Animated Underline) -->
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Left: Logo -->
                    <div class="flex items-center space-x-3 cursor-pointer" onclick="switchTabTop('user-catalog')">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-8 w-auto">
                        <span class="text-[#c52228] font-bold text-base md:text-lg tracking-tight">KoperasiDesa</span>
                    </div>

                    <!-- Center: Desktop Centered Links with Animated Sliding Active Indicator -->
                    <div id="nav-links-container" class="relative hidden md:flex items-center space-x-8 h-16">
                        <button onclick="switchTabTop('user-catalog')" id="nav-btn-user-catalog" class="nav-tab-item py-5 font-bold text-sm text-gray-900 transition-colors cursor-pointer outline-none">Katalog Koperasi</button>
                        <button onclick="switchTabTop('user-products')" id="nav-btn-user-products" class="nav-tab-item py-5 font-semibold text-sm text-gray-500 hover:text-gray-900 transition-colors cursor-pointer outline-none">Barang</button>
                        @if ($roleName === 'user')
                            <button onclick="switchTabTop('user-history')" id="nav-btn-user-history" class="nav-tab-item py-5 font-semibold text-sm text-gray-500 hover:text-gray-900 transition-colors cursor-pointer outline-none">Riwayat Belanja</button>
                        @endif
                        
                        <!-- Smooth Sliding Underline Indicator -->
                        <div id="nav-active-indicator" class="absolute bottom-0 h-0.5 bg-[#c52228] rounded-full transition-all duration-300 ease-out pointer-events-none"></div>
                    </div>

                    <!-- Right: Cart, Profile Icons & Mobile Hamburger -->
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <button onclick="switchTabTop('user-carts')" title="Keranjang Belanja" class="p-2 rounded-lg text-gray-600 hover:text-red-600 hover:bg-red-50 relative flex items-center transition-all cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span id="cart-badge" class="{{ $myCarts->count() > 0 ? '' : 'hidden' }} absolute top-0 right-0 bg-[#c52228] text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center animate-scale-up">
                                {{ $myCarts->count() }}
                            </span>
                        </button>

                        @if ($roleName === 'user')
                            <button onclick="switchTabTop('user-profile')" title="Profil Saya" class="p-1 rounded-full text-gray-600 hover:text-[#c52228] transition-all flex items-center cursor-pointer">
                                @if ($user->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->foto))
                                    <img src="{{ asset('storage/' . $user->foto) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-gray-200 shadow-xs">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-[#c52228] font-extrabold text-xs">
                                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                                    </div>
                                @endif
                            </button>

                            <form action="{{ route('logout') }}" method="POST" class="hidden sm:inline">
                                @csrf
                                <button type="submit" class="bg-[#c52228] hover:bg-[#a51c21] text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer">Keluar</button>
                            </form>
                        @else
                            <div class="hidden sm:flex items-center space-x-2">
                                <a href="{{ route('login') }}" class="bg-[#c52228] hover:bg-[#a51c21] text-white px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all shadow-xs">Masuk</a>
                                <a href="{{ route('register') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3.5 py-1.5 rounded-lg text-xs font-bold transition-all">Daftar</a>
                            </div>
                        @endif

                        <!-- Mobile Hamburger Button -->
                        <button onclick="toggleUserMobileMenu()" class="md:hidden p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                    </div>
                </div>
>>>>>>> 15db2764b4b4d33a4a57b7752454984bf23d7a7b
            </div>

            <!-- Responsive Mobile Drawer for User -->
            <div id="user-mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white px-4 pt-2 pb-4 space-y-2">
                <button onclick="switchTabTop('user-catalog'); toggleUserMobileMenu();" class="block w-full text-left font-semibold text-sm py-2 px-3 rounded-lg text-gray-700 hover:bg-red-50 hover:text-[#c52228]">Katalog Koperasi</button>
                <button onclick="switchTabTop('user-products'); toggleUserMobileMenu();" class="block w-full text-left font-semibold text-sm py-2 px-3 rounded-lg text-gray-700 hover:bg-red-50 hover:text-[#c52228]">Barang</button>
                @if ($roleName === 'user')
                    <button onclick="switchTabTop('user-history'); toggleUserMobileMenu();" class="block w-full text-left font-semibold text-sm py-2 px-3 rounded-lg text-gray-700 hover:bg-red-50 hover:text-[#c52228]">Riwayat Belanja</button>
                    <button onclick="switchTabTop('user-profile'); toggleUserMobileMenu();" class="block w-full text-left font-semibold text-sm py-2 px-3 rounded-lg text-gray-700 hover:bg-red-50 hover:text-[#c52228]">Profil Saya</button>
                    <form action="{{ route('logout') }}" method="POST" class="pt-2 border-t border-gray-100">
                        @csrf
                        <button type="submit" class="w-full bg-[#c52228] text-white py-2 rounded-lg text-xs font-bold">Keluar</button>
                    </form>
                @else
                    <div class="pt-2 border-t border-gray-100 grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}" class="text-center bg-[#c52228] text-white py-2 rounded-lg text-xs font-bold">Masuk</a>
                        <a href="{{ route('register') }}" class="text-center bg-gray-100 text-gray-700 py-2 rounded-lg text-xs font-bold">Daftar</a>
                    </div>
                @endif
            </div>
        </nav>

        <!-- Main Container -->
        <main class="max-w-7xl mx-auto px-4 py-6 md:py-8 sm:px-6 lg:px-8">

            <!-- SECTION 1: KATALOG KOPERASI (PREVIEW BARANG 4 ITEM) -->
            <section id="user-catalog" class="tab-content-top space-y-8">
                <div class="bg-white p-4 md:p-6 rounded-xl border border-gray-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="font-bold text-base md:text-lg text-gray-900">Koperasi Desa (KopDes) Anda</h2>
                        <p class="text-xs md:text-sm text-gray-500 mt-0.5">Rekomendasi berdasarkan Kode Pos <strong>({{ $user->kode_pos ?? 'Default' }})</strong> Anda.</p>
                    </div>
                    <div class="w-full md:w-80">
                        <form method="GET" action="{{ route('dashboard') }}" id="kopdes-select-form">
                            <select name="kopdes_id" onchange="document.getElementById('kopdes-select-form').submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-xs md:text-sm outline-none focus:ring-2 focus:ring-red-500">
                                <option value="">-- Pilih Koperasi Desa --</option>
                                @foreach ($allActiveKopdes as $ak)
                                    <option value="{{ $ak->id_kopdes }}" {{ $selectedKopdesId == $ak->id_kopdes ? 'selected' : '' }}>{{ $ak->nama_kopdes }} ({{ $ak->kode_pos ?? '-' }})</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>

                @if ($selectedKopdes)
                    <div class="space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h3 class="font-bold text-lg md:text-xl text-gray-900 flex items-center">
                                    <span class="w-2.5 h-6 bg-[#c52228] rounded-full mr-3"></span>
                                    Barang Tersedia: {{ $selectedKopdes->nama_kopdes }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">Pratinjau barang terpopuler. Buka menu Barang untuk katalog lengkap.</p>
                            </div>
                            <button onclick="switchTabTop('user-products')" class="bg-[#c52228] hover:bg-[#a51c21] text-white px-4 py-2 rounded-lg text-xs font-bold shadow-xs transition-all flex items-center justify-center w-fit cursor-pointer">
                                Lihat Semua Barang ({{ $myCatalog->count() }}) &rarr;
                            </button>
                        </div>

                        <!-- Product Preview Grid (Max 4 items) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                            @forelse ($myCatalog->take(4) as $p)
                                <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-hidden flex flex-col justify-between p-4 space-y-3 hover:shadow-md transition-all">
                                    <div class="w-full h-40 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                        @if ($p->gambar)
                                            <img src="{{ asset($p->gambar) }}" alt="Gambar Produk" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-gray-400 text-xs font-bold uppercase">{{ $p->nama_produk }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="text-[10px] uppercase font-bold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ $p->category->nama_kategori ?? 'Umum' }}</span>
                                        <h4 class="font-bold text-gray-900 text-sm mt-2 line-clamp-1">{{ $p->nama_produk }}</h4>
                                        <p class="text-xs text-gray-500 line-clamp-2 mt-1">{{ $p->deskripsi }}</p>
                                    </div>
                                    <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                        <span class="font-bold text-[#c52228] text-sm">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                                        @if ($p->stok > 0)
                                            <button onclick="addItemToCart({{ $p->id_product }})" class="bg-[#c52228] hover:bg-[#a51c21] text-white px-2.5 py-1 rounded text-xs font-semibold shadow-xs cursor-pointer">+ Keranjang</button>
                                        @else
                                            <button disabled class="bg-gray-100 text-gray-400 px-2.5 py-1 rounded text-xs font-semibold cursor-not-allowed">Stok Habis</button>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="col-span-4 text-center py-12 text-gray-400 text-sm">Belum ada barang terdaftar di KopDes ini.</p>
                            @endforelse
                        </div>
                    </div>
                @endif
            </section>

            <!-- SECTION 2: HALAMAN BARANG (FULL KATALOG & SEARCH) -->
            <section id="user-products" class="tab-content-top hidden space-y-8">
                <div class="bg-white p-4 md:p-6 rounded-xl border border-gray-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <span class="text-[10px] md:text-xs uppercase font-bold text-[#c52228] tracking-wider">Katalog Produk Lengkap</span>
                        <h1 class="text-xl md:text-2xl font-bold text-gray-900 mt-0.5">{{ $selectedKopdes->nama_kopdes ?? 'Semua Barang' }}</h1>
                        <p class="text-xs md:text-sm text-gray-500 mt-0.5">{{ $selectedKopdes->alamat ?? '' }} (Kode Pos: {{ $selectedKopdes->kode_pos ?? '-' }})</p>
                    </div>

                    <!-- Instant JS Client Search Bar -->
                    <div class="w-full md:w-80 relative">
                        <input type="text" id="catalog-search-input" onkeyup="filterCatalogProducts()" placeholder="Cari nama barang instan..." class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg text-xs md:text-sm outline-none focus:ring-2 focus:ring-red-500">
                    </div>
                </div>

                <!-- Products Grid (Full Catalog) -->
                <div id="full-catalog-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                    @forelse ($myCatalog as $p)
                        <div class="product-item-card bg-white rounded-xl border border-gray-200 shadow-xs overflow-hidden flex flex-col justify-between p-4 space-y-3 hover:shadow-md transition-all" data-title="{{ strtolower($p->nama_produk) }}">
                            <div class="w-full h-40 md:h-44 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden relative group">
                                @if ($p->gambar)
                                    <img src="{{ asset($p->gambar) }}" alt="Gambar Barang" class="w-full h-full object-cover">
                                @else
                                    <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">{{ $p->nama_produk }}</span>
                                @endif
                                <button onclick="openDetailModal({{ $p->id_product }}, '{{ $p->nama_produk }}', '{{ number_format($p->harga, 0, ',', '.') }}', '{{ $p->stok }}', '{{ $p->deskripsi }}', '{{ $p->category->nama_kategori ?? 'Umum' }}')" class="absolute inset-0 bg-black/40 text-white font-bold text-xs opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center backdrop-blur-xs cursor-pointer">
                                    Detail Produk
                                </button>
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">{{ $p->category->nama_kategori ?? 'Umum' }}</span>
                                <h3 class="font-bold text-gray-900 text-sm md:text-base mt-2 line-clamp-1">{{ $p->nama_produk }}</h3>
                                <p class="text-xs text-gray-500 line-clamp-2 mt-1">{{ $p->deskripsi }}</p>
                            </div>
                            <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] text-gray-400 block font-semibold">Harga</span>
                                    <span class="font-bold text-[#c52228] text-sm md:text-base">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                                </div>
                                @if ($p->stok > 0)
                                    <button onclick="addItemToCart({{ $p->id_product }})" class="bg-[#c52228] hover:bg-[#a51c21] text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-xs cursor-pointer">+ Beli</button>
                                @else
                                    <button disabled class="bg-gray-100 text-gray-400 px-3 py-1.5 rounded-lg text-xs font-bold cursor-not-allowed">Habis</button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-4 text-center py-16 bg-white rounded-xl border border-gray-200">
                            <p class="text-gray-400 font-medium text-sm">Tidak ada barang terdaftar di KopDes ini.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- SECTION 3: KERANJANG BELANJA -->
            <section id="user-carts" class="tab-content-top hidden space-y-6">
                <h3 class="font-bold text-lg md:text-xl text-gray-900">Keranjang Belanja Anda</h3>
                
                @if (session('success') && Auth::check() && Auth::user()->id_role == 3)
                    <div class="bg-green-50 text-green-700 p-4 rounded-xl text-sm border border-green-100 font-semibold shadow-xs">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any() && Auth::check() && Auth::user()->id_role == 3)
                    <div class="bg-red-50 text-red-700 p-4 rounded-xl text-sm border border-red-100 font-semibold shadow-xs">
                        @foreach ($errors->all() as $err)
                            <p>{{ $err }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                    <!-- Left: Cart Items List -->
                    <div class="md:col-span-2 space-y-4">
                        @php $cartTotal = 0; @endphp
                        @forelse ($myCarts as $c)
                            @php $cartTotal += $c->product->harga * $c->quantity; @endphp
                            <div id="cart-item-{{ $c->product->id_product }}" class="bg-white rounded-xl border border-gray-200 p-4 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 md:w-16 md:h-16 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0">
                                        @if ($c->product->gambar)
                                            <img src="{{ asset($c->product->gambar) }}" alt="Gambar" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-gray-400 text-[10px] font-bold uppercase">{{ substr($c->product->nama_produk, 0, 3) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-gray-900">{{ $c->product->nama_produk }}</h4>
                                        <span class="text-xs text-gray-400 block mt-0.5">Rp {{ number_format($c->product->harga, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between sm:justify-end space-x-6">
                                    <!-- Quantity Selector -->
                                    <div class="flex items-center border border-gray-200 rounded-lg bg-gray-50 overflow-hidden">
                                        <button onclick="decrementQty({{ $c->product->id_product }})" class="px-2.5 py-1 text-gray-500 hover:bg-gray-100 font-bold text-xs cursor-pointer">-</button>
                                        <input type="text" id="qty-input-{{ $c->product->id_product }}" value="{{ $c->quantity }}" readonly class="w-8 text-center text-xs font-bold bg-transparent border-none outline-none">
                                        <button onclick="incrementQty({{ $c->product->id_product }}, {{ $c->product->stok }})" class="px-2.5 py-1 text-gray-500 hover:bg-gray-100 font-bold text-xs cursor-pointer">+</button>
                                    </div>
                                    <!-- Item Subtotal -->
                                    <span id="subtotal-{{ $c->product->id_product }}" class="font-bold text-sm text-gray-900 w-24 text-right">
                                        Rp {{ number_format($c->product->harga * $c->quantity, 0, ',', '.') }}
                                    </span>
                                    <button onclick="removeItemFromCart({{ $c->product->id_product }})" class="text-red-600 hover:text-red-800 text-xs font-bold transition-all cursor-pointer">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center shadow-xs">
                                <p class="text-gray-400 font-medium text-sm">Keranjang belanja Anda kosong.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Right: Summary Card -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-xs h-fit space-y-6">
                        <h4 class="font-bold text-base md:text-lg text-gray-900">Ringkasan Belanja</h4>
                        <div class="flex justify-between text-sm pt-2 border-t border-gray-100">
                            <span class="text-gray-500 font-medium">Total Harga</span>
                            <span id="cart-total-price" class="font-extrabold text-lg text-[#c52228]">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                        </div>
                        
                        @if ($roleName === 'user')
                            @if ($myCarts->isNotEmpty())
                                <button onclick="openCheckoutModal()" class="w-full bg-[#c52228] hover:bg-[#a51c21] text-white py-2.5 rounded-xl font-bold text-sm shadow-xs transition-all cursor-pointer">
                                    Checkout Sekarang
                                </button>
                            @else
                                <button disabled class="w-full bg-gray-100 text-gray-400 py-2.5 rounded-xl font-bold text-sm cursor-not-allowed select-none">
                                    Keranjang Kosong
                                </button>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="w-full block text-center bg-[#c52228] hover:bg-[#a51c21] text-white py-2.5 rounded-xl font-bold text-sm shadow-xs transition-all">
                                Masuk untuk Checkout
                            </a>
                        @endif
                    </div>
                </div>
            </section>

            <!-- SECTION 4: RIWAYAT TRANSAKSI -->
            <section id="user-history" class="tab-content-top hidden space-y-6">
                <h3 class="font-bold text-lg md:text-xl text-gray-900">Riwayat Belanja</h3>
                <div class="bg-white rounded-xl border border-gray-200 shadow-xs overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[600px]">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                <th class="p-4">Kode Transaksi</th>
                                <th class="p-4">Koperasi</th>
                                <th class="p-4">Total</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-150 text-sm">
                            @forelse ($myHistory as $h)
                                <tr>
                                    <td class="p-4 font-semibold text-gray-900">{{ $h->kode_transaksi }}</td>
                                    <td class="p-4 text-gray-600">{{ $h->kopdes->nama_kopdes ?? 'N/A' }}</td>
                                    <td class="p-4 font-bold text-gray-900">Rp {{ number_format($h->total_harga, 0, ',', '.') }}</td>
                                    <td class="p-4">
                                        @if ($h->status_transaksi === 'menunggu_pembayaran')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 uppercase">Belum Bayar</span>
                                        @elseif ($h->status_transaksi === 'menunggu_verifikasi')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 uppercase">Menunggu Verifikasi</span>
                                        @elseif ($h->status_transaksi === 'diproses')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase">Diproses</span>
                                        @elseif ($h->status_transaksi === 'dibatalkan')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200 uppercase">Dibatalkan</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200 uppercase">{{ $h->status_transaksi }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right space-x-2">
                                        @if ($h->status_transaksi === 'menunggu_pembayaran')
                                            <button onclick="openPaymentModal({{ $h->id_transaction }}, '{{ number_format($h->total_harga, 0, ',', '.') }}')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer">
                                                Bayar
                                            </button>
                                        @endif
                                        
                                        @if ($h->status_transaksi === 'menunggu_pembayaran' || $h->status_transaksi === 'menunggu_verifikasi')
                                            <form action="{{ route('transaction.cancel', $h->id_transaction) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Stok akan dikembalikan.')">
                                                @csrf
                                                <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-700 px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer">
                                                    Batal
                                                </button>
                                            </form>
                                        @elseif ($h->status_transaksi === 'selesai')
                                            <button onclick="openReviewModal({{ $h->details->map(fn($d) => ['id_transaction_detail' => $d->id_transaction_detail, 'nama_produk' => $d->product->nama_produk, 'reviewed' => $d->review !== null])->toJson() }})" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer">
                                                Ulas
                                            </button>
                                        @else
                                            <span class="text-gray-400 text-xs font-semibold">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="p-8 text-center text-gray-400 text-sm">Belum ada riwayat belanja.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- SECTION 5: PROFIL SAYA -->
            @if ($user)
                <section id="user-profile" class="tab-content-top hidden max-w-3xl mx-auto space-y-6">
                    @if (session('success'))
                        <div class="bg-green-50 text-green-700 p-4 rounded-xl text-sm border border-green-100 font-semibold shadow-xs flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-xs overflow-hidden">
                        <div class="h-32 bg-gradient-to-r from-[#c52228] via-rose-700 to-red-800 relative">
                            <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md text-white text-xs px-3 py-1 rounded-full font-semibold">Anggota Koperasi</div>
                        </div>
                        <form id="profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-8 pt-0 space-y-8 relative">
                            @csrf
                            <div class="flex flex-col sm:flex-row items-center sm:items-end space-y-4 sm:space-y-0 sm:space-x-6 -mt-16 mb-2">
                                <div class="relative">
                                    <div id="avatar-container" class="w-24 h-24 md:w-28 md:h-28 rounded-full overflow-hidden shadow-lg border-4 border-white bg-white flex items-center justify-center">
                                        @if ($user->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->foto))
                                            <img id="avatar-preview-img" src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="w-full h-full object-cover" data-original-src="{{ asset('storage/' . $user->foto) }}">
                                            <div id="avatar-fallback-initial" class="hidden w-full h-full bg-red-100 flex items-center justify-center text-[#c52228] text-3xl md:text-4xl font-extrabold">
                                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                                            </div>
                                        @else
                                            <img id="avatar-preview-img" src="" alt="Foto Profil" class="hidden w-full h-full object-cover" data-original-src="">
                                            <div id="avatar-fallback-initial" class="w-full h-full bg-red-100 flex items-center justify-center text-[#c52228] text-3xl md:text-4xl font-extrabold">
                                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <label for="foto" class="absolute bottom-0 right-0 bg-[#c52228] hover:bg-[#a51c21] text-white p-2 rounded-full shadow-md cursor-pointer transition-all" title="Ubah Foto Profil">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                                    </label>
                                    <input type="file" id="foto" name="foto" accept="image/*" class="hidden" onchange="previewProfileImage(this)">
                                </div>
                                <div class="text-center sm:text-left flex-1">
                                    <h2 class="text-xl md:text-2xl font-bold text-gray-900">{{ $user->nama }}</h2>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="space-y-6 pt-4 border-t border-gray-100">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <label for="nama" class="block text-xs font-bold text-gray-600 uppercase mb-1">Nama Lengkap</label>
                                        <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl text-sm outline-none">
                                    </div>
                                    <div>
                                        <label for="kode_pos" class="block text-xs font-bold text-gray-600 uppercase mb-1">Kode Pos</label>
                                        <input type="text" id="kode_pos" name="kode_pos" value="{{ old('kode_pos', $user->kode_pos) }}" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl text-sm outline-none">
                                    </div>
                                    <div>
                                        <label for="no_hp" class="block text-xs font-bold text-gray-600 uppercase mb-1">No Handphone</label>
                                        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl text-sm outline-none">
                                    </div>
                                    <div>
                                        <label for="alamat" class="block text-xs font-bold text-gray-600 uppercase mb-1">Detail Alamat</label>
                                        <input type="text" id="alamat" name="alamat" value="{{ old('alamat', $user->alamat) }}" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-xl text-sm outline-none">
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                                <button type="button" onclick="cancelProfileChanges()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-5 rounded-xl font-bold text-sm transition-all cursor-pointer">Batal</button>
                                <button type="submit" class="bg-[#c52228] hover:bg-[#a51c21] text-white py-2 px-5 rounded-xl font-bold text-sm transition-all cursor-pointer shadow-xs">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </section>
            @endif
        </main>

        <!-- Detail Barang Modal -->
        <div id="detail-modal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs hidden items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-5 shadow-2xl relative">
                <button onclick="closeDetailModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
                <div>
                    <span id="modal-category" class="text-[10px] uppercase font-bold text-red-600 bg-red-50 px-2.5 py-1 rounded-full"></span>
                    <h3 id="modal-title" class="text-lg md:text-xl font-bold text-gray-900 mt-2"></h3>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl space-y-2 border border-gray-100 text-xs md:text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Harga:</span>
                        <span id="modal-price" class="font-bold text-[#c52228] text-base"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Stok:</span>
                        <span id="modal-stock" class="font-bold text-gray-900"></span>
                    </div>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Deskripsi Produk</h4>
                    <p id="modal-desc" class="text-xs md:text-sm text-gray-600 leading-relaxed"></p>
                </div>
                <div class="pt-4 border-t border-gray-100 flex space-x-3">
                    <button onclick="closeDetailModal()" class="w-1/2 bg-gray-100 text-gray-700 font-bold py-2 rounded-xl text-xs md:text-sm">Tutup</button>
                    <button id="modal-buy-btn" class="w-1/2 bg-[#c52228] text-white font-bold py-2 rounded-xl text-xs md:text-sm shadow-xs">+ Beli</button>
                </div>
            </div>
        </div>

<<<<<<< HEAD
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

            <!-- KOLOM KANAN: TABEL KATEGORI, PRODUK, PESANAN, LAPORAN & ULASAN -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Kartu Ringkasan Omzet / Laporan Penjualan -->
                <div class="bg-gradient-to-r from-gray-900 to-gray-800 text-white rounded-xl shadow-sm p-6 border border-gray-700 flex justify-between items-center">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">Total Omzet Penjualan (Selesai)</p>
                        <h3 class="text-2xl font-extrabold mt-1">Rp {{ number_format($totalOmzet ?? 0, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-red-600 p-3 rounded-lg text-white">
                        <i class="fa-solid fa-chart-line text-xl"></i>
                    </div>
                </div>

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

                <!-- Tabel Ulasan Pelanggan -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b flex items-center">
                        <i class="fa-solid fa-star text-yellow-500 mr-2"></i> Ulasan & Rating Produk
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-red-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-bold text-red-700">Produk</th>
                                    <th class="px-4 py-2 text-left font-bold text-red-700">Pelanggan</th>
                                    <th class="px-4 py-2 text-left font-bold text-red-700">Rating</th>
                                    <th class="px-4 py-2 text-left font-bold text-red-700">Ulasan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($reviews ?? [] as $rev)
                                    <tr>
                                        <td class="px-4 py-3 font-medium">{{ $rev->nama_produk }}</td>
                                        <td class="px-4 py-3 text-gray-500">{{ $rev->nama_pelanggan ?? 'Pembeli' }}</td>
                                        <td class="px-4 py-3 text-yellow-500 font-bold">
                                            {{ str_repeat('★', $rev->rating ?? 5) }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-600 italic">"{{ $rev->komentar ?? $rev->ulasan }}"</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-4 py-4 text-center text-gray-400 italic">Belum ada ulasan dari pelanggan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

    </div>

=======
        <!-- Checkout Modal -->
        <div id="checkout-modal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs hidden items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-5 shadow-2xl relative">
                <button onclick="closeCheckoutModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900">Konfirmasi Pesanan</h3>
                    <p class="text-xs text-gray-500 mt-1">Lengkapi alamat pengiriman dan catatan untuk membuat pesanan Anda.</p>
                </div>
                <form id="checkout-form" action="{{ route('checkout') }}" method="POST" class="space-y-4" onsubmit="disableSubmitButton(this)">
                    @csrf
                    <div>
                        <label for="checkout-alamat" class="block text-xs font-bold text-gray-600 uppercase mb-1">Alamat Pengiriman</label>
                        <textarea id="checkout-alamat" name="alamat_pengiriman" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm outline-none focus:ring-2 focus:ring-red-500 bg-gray-50">{{ $user ? $user->alamat : '' }}</textarea>
                    </div>
                    <div>
                        <label for="checkout-catatan" class="block text-xs font-bold text-gray-600 uppercase mb-1">Catatan Pesanan (Opsional)</label>
                        <input type="text" id="checkout-catatan" name="catatan" placeholder="Contoh: Titip di pos satpam" class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm outline-none focus:ring-2 focus:ring-red-500 bg-gray-50">
                    </div>
                    <div class="pt-4 border-t border-gray-100 flex space-x-3">
                        <button type="button" onclick="closeCheckoutModal()" class="w-1/2 bg-gray-100 text-gray-700 font-bold py-2 rounded-xl text-xs md:text-sm">Tutup</button>
                        <button type="submit" class="w-1/2 bg-[#c52228] text-white font-bold py-2 rounded-xl text-xs md:text-sm shadow-xs hover:bg-[#a51c21] transition-all cursor-pointer">Buat Pesanan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Upload Bukti Bayar Modal -->
        <div id="payment-modal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs hidden items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-5 shadow-2xl relative">
                <button onclick="closePaymentModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900">Unggah Bukti Pembayaran</h3>
                    <p class="text-xs text-gray-500 mt-1">Silakan transfer sesuai total harga ke rekening bank koperasi desa setempat.</p>
                </div>
                <form id="payment-form" action="" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="disableSubmitButton(this)">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Total Harus Dibayar</label>
                        <span id="payment-total-label" class="font-extrabold text-lg text-[#c52228]"></span>
                    </div>
                    <div>
                        <label for="payment-metode" class="block text-xs font-bold text-gray-600 uppercase mb-1">Metode Pembayaran</label>
                        <input type="text" id="payment-metode" name="metode_pembayaran" value="Transfer Bank" required class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm outline-none focus:ring-2 focus:ring-red-500 bg-gray-50">
                    </div>
                    <div>
                        <label for="payment-jumlah" class="block text-xs font-bold text-gray-600 uppercase mb-1">Jumlah Transfer (Rp)</label>
                        <input type="number" id="payment-jumlah" name="jumlah_bayar" required class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm outline-none focus:ring-2 focus:ring-red-500 bg-gray-50">
                    </div>
                    <div>
                        <label for="payment-bukti" class="block text-xs font-bold text-gray-600 uppercase mb-1">Foto/Gambar Bukti Transfer</label>
                        <input type="file" id="payment-bukti" name="bukti_pembayaran" accept="image/*" required class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-[#c52228] hover:file:bg-red-100 cursor-pointer">
                    </div>
                    <div class="pt-4 border-t border-gray-100 flex space-x-3">
                        <button type="button" onclick="closePaymentModal()" class="w-1/2 bg-gray-100 text-gray-700 font-bold py-2 rounded-xl text-xs md:text-sm">Batal</button>
                        <button type="submit" class="w-1/2 bg-[#c52228] text-white font-bold py-2 rounded-xl text-xs md:text-sm shadow-xs hover:bg-[#a51c21] transition-all cursor-pointer">Unggah</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Review Modal -->
        <div id="review-modal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs hidden items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-5 shadow-2xl relative">
                <button onclick="closeReviewModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-lg font-bold">✕</button>
                <div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900">Ulas Produk</h3>
                    <p class="text-xs text-gray-500 mt-1">Berikan penilaian Anda terhadap produk yang telah Anda beli.</p>
                </div>
                <form id="review-form" action="{{ route('review.store') }}" method="POST" class="space-y-4" onsubmit="disableSubmitButton(this)">
                    @csrf
                    <div>
                        <label for="review-product-select" class="block text-xs font-bold text-gray-600 uppercase mb-1">Pilih Produk yang Ingin Diulas</label>
                        <select id="review-product-select" name="id_transaction_detail" required class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm outline-none bg-gray-50">
                            <!-- Populated via JS -->
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Rating</label>
                        <div class="flex items-center space-x-2 text-2xl cursor-pointer select-none">
                            <span onclick="setRating(1)" class="star-rating text-gray-300 hover:text-yellow-400">★</span>
                            <span onclick="setRating(2)" class="star-rating text-gray-300 hover:text-yellow-400">★</span>
                            <span onclick="setRating(3)" class="star-rating text-gray-300 hover:text-yellow-400">★</span>
                            <span onclick="setRating(4)" class="star-rating text-gray-300 hover:text-yellow-400">★</span>
                            <span onclick="setRating(5)" class="star-rating text-gray-300 hover:text-yellow-400">★</span>
                        </div>
                        <input type="hidden" id="review-rating-value" name="rating" value="" required>
                    </div>
                    <div>
                        <label for="review-komentar" class="block text-xs font-bold text-gray-600 uppercase mb-1">Ulasan Anda</label>
                        <textarea id="review-komentar" name="komentar" rows="3" placeholder="Bagikan pengalaman Anda menggunakan produk ini..." class="w-full px-4 py-2 border border-gray-300 rounded-xl text-sm outline-none focus:ring-2 focus:ring-red-500 bg-gray-50"></textarea>
                    </div>
                    <div class="pt-4 border-t border-gray-100 flex space-x-3">
                        <button type="button" onclick="closeReviewModal()" class="w-1/2 bg-gray-100 text-gray-700 font-bold py-2 rounded-xl text-xs md:text-sm">Batal</button>
                        <button type="submit" class="w-1/2 bg-[#c52228] text-white font-bold py-2 rounded-xl text-xs md:text-sm shadow-xs hover:bg-[#a51c21] transition-all cursor-pointer">Kirim Ulasan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Shared Responsive JS Scripts -->
    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar-menu');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            if (sidebar && backdrop) {
                sidebar.classList.toggle('-translate-x-full');
                backdrop.classList.toggle('hidden');
            }
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebar-menu');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            if (sidebar && backdrop) {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        function toggleUserMobileMenu() {
            const menu = document.getElementById('user-mobile-menu');
            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        function switchTabAdmin(tabId, btnEl) {
            closeMobileSidebar();
            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            const target = document.getElementById(tabId);
            if (target) target.classList.remove('hidden');

            document.querySelectorAll('.tab-btn-admin').forEach(b => {
                b.classList.remove('bg-red-50', 'text-[#c52228]');
                b.classList.add('hover:bg-gray-50', 'text-gray-600');
            });
            if (btnEl) {
                btnEl.classList.remove('hover:bg-gray-50', 'text-gray-600');
                btnEl.classList.add('bg-red-50', 'text-[#c52228]');
            }
        }

        // SMOOTH ANIMATED ACTIVE SLIDING UNDERLINE INDICATOR FOR NAVBAR
        function moveNavIndicator(activeEl) {
            const indicator = document.getElementById('nav-active-indicator');
            if (indicator && activeEl) {
                indicator.style.left = activeEl.offsetLeft + 'px';
                indicator.style.width = activeEl.offsetWidth + 'px';
                indicator.style.opacity = '1';
            } else if (indicator) {
                indicator.style.opacity = '0';
            }
        }

        function switchTabTop(tabId) {
            // 1. Instantly hide all section contents
            document.querySelectorAll('.tab-content-top').forEach(c => c.classList.add('hidden'));

            // 2. Instantly show target section content
            const target = document.getElementById(tabId);
            if (target) {
                target.classList.remove('hidden');
            }

            // 3. Highlight text and slide active indicator smoothly
            const btnCatalog = document.getElementById('nav-btn-user-catalog');
            const btnProducts = document.getElementById('nav-btn-user-products');
            const btnHistory = document.getElementById('nav-btn-user-history');

            let activeEl = null;

            if (btnCatalog && btnProducts && btnHistory) {
                [btnCatalog, btnProducts, btnHistory].forEach(el => {
                    el.classList.remove('text-gray-900', 'font-bold');
                    el.classList.add('text-gray-500', 'font-semibold');
                });

                if (tabId === 'user-catalog' && btnCatalog) {
                    btnCatalog.classList.remove('text-gray-500', 'font-semibold');
                    btnCatalog.classList.add('text-gray-900', 'font-bold');
                    activeEl = btnCatalog;
                } else if (tabId === 'user-products' && btnProducts) {
                    btnProducts.classList.remove('text-gray-500', 'font-semibold');
                    btnProducts.classList.add('text-gray-900', 'font-bold');
                    activeEl = btnProducts;
                } else if (tabId === 'user-history' && btnHistory) {
                    btnHistory.classList.remove('text-gray-500', 'font-semibold');
                    btnHistory.classList.add('text-gray-900', 'font-bold');
                    activeEl = btnHistory;
                }

                moveNavIndicator(activeEl);
            }

            if (window.history && window.history.pushState) {
                window.history.pushState(null, null, '#' + tabId);
            }
        }

        function filterCatalogProducts() {
            const query = (document.getElementById('catalog-search-input').value || '').toLowerCase();
            document.querySelectorAll('.product-item-card').forEach(card => {
                const title = card.getAttribute('data-title') || '';
                if (title.includes(query)) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        }

        function openDetailModal(idProduct, title, price, stock, desc, category) {
            document.getElementById('modal-title').innerText = title;
            document.getElementById('modal-price').innerText = 'Rp ' + price;
            document.getElementById('modal-stock').innerText = stock + ' pcs';
            document.getElementById('modal-desc').innerText = desc || 'Tidak ada deskripsi.';
            document.getElementById('modal-category').innerText = category;

            const buyBtn = document.getElementById('modal-buy-btn');
            if (buyBtn) {
                const stockInt = parseInt(stock) || 0;
                if (stockInt > 0) {
                    buyBtn.disabled = false;
                    buyBtn.innerText = '+ Beli';
                    buyBtn.className = 'w-1/2 bg-[#c52228] text-white font-bold py-2 rounded-xl text-xs md:text-sm shadow-xs cursor-pointer hover:bg-[#a51c21]';
                    buyBtn.onclick = function() {
                        addItemToCart(idProduct);
                        closeDetailModal();
                    };
                } else {
                    buyBtn.disabled = true;
                    buyBtn.innerText = 'Stok Habis';
                    buyBtn.className = 'w-1/2 bg-gray-200 text-gray-400 font-bold py-2 rounded-xl text-xs md:text-sm cursor-not-allowed';
                    buyBtn.onclick = null;
                }
            }

            const modal = document.getElementById('detail-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDetailModal() {
            const modal = document.getElementById('detail-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openCheckoutModal() {
            const modal = document.getElementById('checkout-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }
        
        function closeCheckoutModal() {
            const modal = document.getElementById('checkout-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function openPaymentModal(trxId, totalFormatted) {
            const modal = document.getElementById('payment-modal');
            if (modal) {
                document.getElementById('payment-total-label').innerText = 'Rp ' + totalFormatted;
                document.getElementById('payment-jumlah').value = parseFloat(totalFormatted.replace(/[^0-9]/g, ''));
                const form = document.getElementById('payment-form');
                form.action = `/transaction/${trxId}/pay`;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closePaymentModal() {
            const modal = document.getElementById('payment-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function openReviewModal(details) {
            const select = document.getElementById('review-product-select');
            select.innerHTML = '';
            details.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.id_transaction_detail;
                opt.innerText = d.nama_produk + (d.reviewed ? ' ✓ (sudah diulas)' : '');
                select.appendChild(opt);
            });
            setRating(0);
            document.getElementById('review-komentar').value = '';
            
            const modal = document.getElementById('review-modal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeReviewModal() {
            const modal = document.getElementById('review-modal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function setRating(val) {
            document.getElementById('review-rating-value').value = val || '';
            const stars = document.querySelectorAll('.star-rating');
            stars.forEach((star, idx) => {
                if (idx < val) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });
        }

        function disableSubmitButton(form) {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerText = 'Memproses...';
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        function addItemToCart(productId) {
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id_product: productId, quantity: 1 })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    updateCartBadge(data.cart_count);
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast(data.message || 'Gagal menambahkan barang.', 'error');
                }
            })
            .catch(err => {
                showToast('Terjadi kesalahan koneksi.', 'error');
            });
        }

        function incrementQty(productId, stock) {
            const input = document.getElementById('qty-input-' + productId);
            let currentVal = parseInt(input.value);
            if (currentVal >= stock) {
                showToast('Kuantitas melebihi stok yang tersedia!', 'error');
                return;
            }
            updateCartQuantity(productId, currentVal + 1);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const hash = window.location.hash.replace('#', '') || 'user-catalog';
            if (document.getElementById(hash)) {
                switchTabTop(hash);
            } else {
                switchTabTop('user-catalog');
            }
        });

        function decrementQty(productId) {
            const input = document.getElementById('qty-input-' + productId);
            let currentVal = parseInt(input.value);
            if (currentVal <= 1) {
                return;
            }
            updateCartQuantity(productId, currentVal - 1);
        }

        function updateCartQuantity(productId, newQty) {
            fetch('{{ route("cart.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id_product: productId, quantity: newQty })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('qty-input-' + productId).value = newQty;
                    document.getElementById('subtotal-' + productId).innerText = data.item_subtotal;
                    document.getElementById('cart-total-price').innerText = data.cart_total;
                    updateCartBadge(data.cart_count);
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Gagal memperbarui kuantitas.', 'error');
                }
            })
            .catch(err => {
                showToast('Terjadi kesalahan koneksi.', 'error');
            });
        }

        function removeItemFromCart(productId) {
            if (!confirm('Apakah Anda yakin ingin menghapus barang ini dari keranjang?')) {
                return;
            }
            fetch('{{ route("cart.remove") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id_product: productId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById('cart-item-' + productId);
                    if (row) {
                        row.remove();
                    }
                    document.getElementById('cart-total-price').innerText = data.cart_total;
                    updateCartBadge(data.cart_count);
                    showToast(data.message, 'success');
                    if (data.cart_count === 0) {
                        setTimeout(() => window.location.reload(), 800);
                    }
                } else {
                    showToast(data.message || 'Gagal menghapus barang.', 'error');
                }
            })
            .catch(err => {
                showToast('Terjadi kesalahan koneksi.', 'error');
            });
        }

        function updateCartBadge(count) {
            const badge = document.getElementById('cart-badge');
            if (badge) {
                badge.innerText = count;
                if (count > 0) {
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        }

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-4 py-2.5 rounded-xl text-white text-xs font-bold shadow-lg transition-all transform translate-y-10 opacity-0 z-50 flex items-center space-x-2 ${type === 'success' ? 'bg-emerald-600' : 'bg-[#c52228]'}`;
            toast.innerHTML = `
                <span>${message}</span>
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.classList.remove('translate-y-10', 'opacity-0');
            }, 50);
            setTimeout(() => {
                toast.classList.add('translate-y-10', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        window.addEventListener('resize', function() {
            const activeBtn = document.querySelector('.nav-tab-item.text-gray-900');
            if (activeBtn) {
                moveNavIndicator(activeBtn);
            }
        });

        function previewProfileImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('avatar-preview-img');
                    const fallback = document.getElementById('avatar-fallback-initial');
                    if (img && fallback) {
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                        fallback.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function cancelProfileChanges() {
            const form = document.getElementById('profile-form');
            const input = document.getElementById('foto');
            const img = document.getElementById('avatar-preview-img');
            const fallback = document.getElementById('avatar-fallback-initial');

            if (form) {
                form.reset();
            }
            if (input) {
                input.value = '';
            }

            if (img && fallback) {
                const originalSrc = img.getAttribute('data-original-src');
                if (originalSrc) {
                    img.src = originalSrc;
                    img.classList.remove('hidden');
                    fallback.classList.add('hidden');
                } else {
                    img.src = '';
                    img.classList.add('hidden');
                    fallback.classList.remove('hidden');
                }
            }
        }
    </script>
>>>>>>> 15db2764b4b4d33a4a57b7752454984bf23d7a7b
</body>
</html>