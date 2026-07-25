<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kopdes</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">

    @php
        $user = Auth::user();
        $roleName = $user->role->nama_role ?? 'user';
    @endphp

    <!-- ============================================== -->
    <!-- 1. ADMIN DASHBOARD (CLEAN WHITE SIDEBAR LAYOUT)-->
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

        <div class="flex min-h-screen">
            <!-- Sidebar (Clean White) -->
            <aside class="w-64 bg-white text-gray-800 border-r border-gray-200 flex flex-col fixed inset-y-0 left-0 z-30">
                <div class="p-6 flex items-center space-x-3 border-b border-gray-100">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-8 w-auto">
                    <span class="font-bold text-lg text-[#c52228]">Admin Platform</span>
                </div>
                <nav class="flex-1 p-4 space-y-1.5">
                    <button onclick="switchTab('admin-overview')" class="tab-btn w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all bg-red-50 text-[#c52228] flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
                        Ringkasan
                    </button>
                    <button onclick="switchTab('admin-kopdes')" class="tab-btn w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Kelola KopDes
                    </button>
                    <button onclick="switchTab('admin-managers')" class="tab-btn w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Kelola Manager
                    </button>
                    <button onclick="switchTab('admin-transactions')" class="tab-btn w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 00-2 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Semua Transaksi
                    </button>
                    <button onclick="switchTab('admin-payments')" class="tab-btn w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
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
            <main class="flex-1 ml-64 p-8">
                <!-- Top Nav / Profile Header -->
                <header class="flex justify-between items-center pb-6 border-b border-gray-200 mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Platform Administrator</h1>
                        <p class="text-sm text-gray-500">Mengelola Koperasi Desa dan Pengawas Toko</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-semibold text-gray-700 bg-gray-100 px-3 py-1 rounded-full uppercase">Admin: {{ $user->nama }}</span>
                    </div>
                </header>

                <!-- SECTION: RINGKASAN (OVERVIEW) -->
                <section id="admin-overview" class="tab-content space-y-8">
                    <!-- Stat Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                            <span class="text-sm font-medium text-gray-400">Total KopDes</span>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $totalKopdes }}</h3>
                        </div>
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                            <span class="text-sm font-medium text-gray-400">Total Akun Manager</span>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $totalManager }}</h3>
                        </div>
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                            <span class="text-sm font-medium text-gray-400">Total Transaksi</span>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $totalTransactions }}</h3>
                        </div>
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                            <span class="text-sm font-medium text-gray-400">Total Omset Platform</span>
                            <h3 class="text-3xl font-bold text-red-600 mt-2">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</h3>
                        </div>
                    </div>

                    <!-- Platform Analytics Summary -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                        <h3 class="font-bold text-lg text-gray-900 mb-4">Laporan Keseluruhan Platform</h3>
                        <p class="text-sm text-gray-600">Dashboard Admin menyajikan metrik performa operasional seluruh Koperasi Desa. Gunakan menu sidebar untuk melihat detail data.</p>
                    </div>
                </section>

                <!-- SECTION: KELOLA KOPDES -->
                <section id="admin-kopdes" class="tab-content hidden space-y-6">
                    <div class="flex justify-between items-center">
                        <h3 class="font-bold text-xl text-gray-900">Daftar Koperasi Desa (KopDes)</h3>
                        <button class="bg-[#c52228] hover:bg-[#a51c21] text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm cursor-pointer">+ Tambah KopDes</button>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                    <th class="p-4">Nama KopDes</th>
                                    <th class="p-4">Alamat</th>
                                    <th class="p-4">No. HP</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-150 text-sm">
                                @forelse ($allKopdes as $kd)
                                    <tr>
                                        <td class="p-4 font-semibold text-gray-900">{{ $kd->nama_kopdes }}</td>
                                        <td class="p-4 text-gray-600">{{ $kd->alamat }}</td>
                                        <td class="p-4 text-gray-600">{{ $kd->no_hp }}</td>
                                        <td class="p-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">{{ $kd->status }}</span>
                                        </td>
                                        <td class="p-4 flex space-x-2">
                                            <button class="text-blue-600 font-semibold hover:underline">Edit</button>
                                            <button class="text-red-600 font-semibold hover:underline">Nonaktifkan</button>
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
                    <div class="flex justify-between items-center">
                        <h3 class="font-bold text-xl text-gray-900">Kelola Akun Manager</h3>
                        <button class="bg-[#c52228] hover:bg-[#a51c21] text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm cursor-pointer">+ Tambah Akun Manager</button>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                    <th class="p-4">Nama Manager</th>
                                    <th class="p-4">Email</th>
                                    <th class="p-4">Penempatan KopDes</th>
                                    <th class="p-4">No. HP</th>
                                    <th class="p-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-150 text-sm">
                                @forelse ($allManagers as $m)
                                    <tr>
                                        <td class="p-4 font-semibold text-gray-900">{{ $m->nama }}</td>
                                        <td class="p-4 text-gray-600">{{ $m->email }}</td>
                                        <td class="p-4 font-semibold text-[#c52228]">{{ $m->kopdes->nama_kopdes ?? 'Belum Ditugaskan' }}</td>
                                        <td class="p-4 text-gray-600">{{ $m->no_hp }}</td>
                                        <td class="p-4">
                                            <button class="text-blue-600 font-semibold hover:underline">Edit</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="p-8 text-center text-gray-400">Belum ada akun Manager.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- SECTION: SEMUA TRANSAKSI -->
                <section id="admin-transactions" class="tab-content hidden space-y-6">
                    <h3 class="font-bold text-xl text-gray-900">Semua Transaksi Koperasi</h3>
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <table class="w-full text-left border-collapse">
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
                    <h3 class="font-bold text-xl text-gray-900">Semua Pembayaran Masuk</h3>
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                    <th class="p-4">Kode Transaksi</th>
                                    <th class="p-4">KopDes</th>
                                    <th class="p-4">Jumlah Bayar</th>
                                    <th class="p-4">Metode</th>
                                    <th class="p-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-150 text-sm">
                                @forelse ($allPayments as $p)
                                    <tr>
                                        <td class="p-4 font-semibold text-gray-900">{{ $p->transaction->kode_transaksi ?? 'N/A' }}</td>
                                        <td class="p-4 text-gray-600">{{ $p->transaction->kopdes->nama_kopdes ?? 'N/A' }}</td>
                                        <td class="p-4 font-bold">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                                        <td class="p-4 text-gray-600 uppercase">{{ $p->metode_pembayaran }}</td>
                                        <td class="p-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">{{ $p->status_pembayaran }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="p-8 text-center text-gray-400">Belum ada bukti pembayaran.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </main>
        </div>
    @endif


    <!-- ============================================== -->
    <!-- 2. MANAGER DASHBOARD (CLEAN WHITE SIDEBAR LAYOUT)-->
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
        @endphp

        <div class="flex min-h-screen">
            <!-- Sidebar (Clean White) -->
            <aside class="w-64 bg-white text-gray-800 border-r border-gray-200 flex flex-col fixed inset-y-0 left-0 z-30">
                <div class="p-6 flex items-center space-x-3 border-b border-gray-100">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-8 w-auto">
                    <span class="font-bold text-base text-gray-900 truncate">{{ $hasKopdes ? $kopdes->nama_kopdes : 'KopDes Manager' }}</span>
                </div>
                <nav class="flex-1 p-4 space-y-1.5">
                    <button onclick="switchTab('mgr-overview')" class="tab-btn w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all bg-red-50 text-[#c52228] flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Ringkasan
                    </button>
                    <button onclick="switchTab('mgr-products')" class="tab-btn w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Produk & Stok
                    </button>
                    <button onclick="switchTab('mgr-categories')" class="tab-btn w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Kategori Produk
                    </button>
                    <button onclick="switchTab('mgr-orders')" class="tab-btn w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Pesanan Pelanggan
                    </button>
                    <button onclick="switchTab('mgr-payments')" class="tab-btn w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Verifikasi Bayar
                    </button>
                    <button onclick="switchTab('mgr-reviews')" class="tab-btn w-full text-left px-4 py-2.5 rounded-lg text-sm font-semibold transition-all hover:bg-gray-50 text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        Ulasan Produk
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
            <main class="flex-1 ml-64 p-8">
                @if (!$hasKopdes)
                    <div class="bg-yellow-50 text-yellow-800 p-6 rounded-xl border border-yellow-100 shadow-sm">
                        <h2 class="font-bold text-lg">Penempatan KopDes Belum Ditugaskan</h2>
                        <p class="text-sm mt-1">Anda belum ditugaskan untuk mengelola Koperasi Desa manapun oleh Administrator. Harap hubungi administrator platform.</p>
                    </div>
                @else
                    <!-- Top Nav / Profile Header -->
                    <header class="flex justify-between items-center pb-6 border-b border-gray-200 mb-8">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $kopdes->nama_kopdes }}</h1>
                            <p class="text-sm text-gray-500">Alamat: {{ $kopdes->alamat }}</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="text-sm font-semibold text-gray-700 bg-gray-100 px-3 py-1 rounded-full">Manager: {{ $user->nama }}</span>
                        </div>
                    </header>

                    <!-- SECTION: OVERVIEW -->
                    <section id="mgr-overview" class="tab-content space-y-8">
                        <!-- Stat Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                                <span class="text-sm font-medium text-gray-400">Total Produk Aktif</span>
                                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $totalProducts }}</h3>
                            </div>
                            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                                <span class="text-sm font-medium text-gray-400">Pendapatan Koperasi</span>
                                <h3 class="text-3xl font-bold text-green-600 mt-2">Rp {{ number_format($kopdesEarnings, 0, ',', '.') }}</h3>
                            </div>
                            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                                <span class="text-sm font-medium text-gray-400">Pesanan Sedang Diproses</span>
                                <h3 class="text-3xl font-bold text-red-600 mt-2">{{ $processingOrders }}</h3>
                            </div>
                        </div>

                        <!-- Info Card -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                            <h3 class="font-bold text-lg text-gray-900 mb-2">Laporan Penjualan KopDes Anda</h3>
                            <p class="text-sm text-gray-600">Perhatikan status transaksi dan lakukan pengiriman barang jika pembayaran telah diverifikasi. Laporan statistik ini terhubung secara real-time.</p>
                        </div>
                    </section>

                    <!-- SECTION: PRODUK & STOK -->
                    <section id="mgr-products" class="tab-content hidden space-y-6">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-xl text-gray-900">Katalog Produk & Stok</h3>
                            <button class="bg-[#c52228] hover:bg-[#a51c21] text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm cursor-pointer">+ Tambah Produk</button>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                        <th class="p-4">Produk</th>
                                        <th class="p-4">Kategori</th>
                                        <th class="p-4">Harga</th>
                                        <th class="p-4">Stok Tersedia</th>
                                        <th class="p-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-150 text-sm">
                                    @forelse ($myProducts as $prod)
                                        <tr>
                                            <td class="p-4 font-semibold text-gray-900">{{ $prod->nama_produk }}</td>
                                            <td class="p-4 text-gray-600">{{ $prod->category->nama_kategori ?? 'Umum' }}</td>
                                            <td class="p-4 font-semibold">Rp {{ number_format($prod->harga, 0, ',', '.') }}</td>
                                            <td class="p-4 font-bold text-blue-600">{{ $prod->stok }} pcs</td>
                                            <td class="p-4 flex space-x-2">
                                                <button class="text-blue-600 font-semibold hover:underline">Edit</button>
                                                <button class="text-red-600 font-semibold hover:underline">Hapus</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="p-8 text-center text-gray-400">Belum ada produk terdaftar.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- SECTION: KATEGORI PRODUK -->
                    <section id="mgr-categories" class="tab-content hidden space-y-6">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-xl text-gray-900">Kelola Kategori Produk</h3>
                            <button class="bg-[#c52228] hover:bg-[#a51c21] text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm cursor-pointer">+ Tambah Kategori</button>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm max-w-md overflow-hidden">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                        <th class="p-4">Kategori</th>
                                        <th class="p-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-150 text-sm">
                                    @forelse ($myCategories as $cat)
                                        <tr>
                                            <td class="p-4 font-semibold text-gray-900">{{ $cat->nama_kategori }}</td>
                                            <td class="p-4">
                                                <button class="text-red-600 font-semibold hover:underline">Hapus</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="p-8 text-center text-gray-400">Belum ada kategori ditambahkan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- SECTION: PESANAN PELANGGAN -->
                    <section id="mgr-orders" class="tab-content hidden space-y-6">
                        <h3 class="font-bold text-xl text-gray-900">Pesanan Pelanggan</h3>
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                        <th class="p-4">Kode Transaksi</th>
                                        <th class="p-4">Pelanggan</th>
                                        <th class="p-4">Total</th>
                                        <th class="p-4">Status</th>
                                        <th class="p-4">Tindakan Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-150 text-sm">
                                    @forelse ($myTransactions as $trx)
                                        <tr>
                                            <td class="p-4 font-semibold text-gray-900">{{ $trx->kode_transaksi }}</td>
                                            <td class="p-4 text-gray-600">{{ $trx->user->nama ?? 'N/A' }}</td>
                                            <td class="p-4 font-bold">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                                            <td class="p-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-50 text-orange-700 border border-orange-200">{{ $trx->status_transaksi }}</span>
                                            </td>
                                            <td class="p-4 flex space-x-2">
                                                <button class="bg-[#1a2d42] text-white px-2.5 py-1 rounded text-xs hover:bg-slate-700">Proses</button>
                                                <button class="bg-green-600 text-white px-2.5 py-1 rounded text-xs hover:bg-green-700">Kirim</button>
                                                <button class="bg-blue-600 text-white px-2.5 py-1 rounded text-xs hover:bg-blue-700">Selesai</button>
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
                        <h3 class="font-bold text-xl text-gray-900">Verifikasi Pembayaran</h3>
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                        <th class="p-4">Kode Transaksi</th>
                                        <th class="p-4">Pelanggan</th>
                                        <th class="p-4">Jumlah Transfer</th>
                                        <th class="p-4">Status Verifikasi</th>
                                        <th class="p-4">Verifikasi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-150 text-sm">
                                    @forelse ($myPayments as $pay)
                                        <tr>
                                            <td class="p-4 font-semibold text-gray-900">{{ $pay->transaction->kode_transaksi ?? 'N/A' }}</td>
                                            <td class="p-4 text-gray-600">{{ $pay->transaction->user->nama ?? 'N/A' }}</td>
                                            <td class="p-4 font-bold">Rp {{ number_format($pay->jumlah_bayar, 0, ',', '.') }}</td>
                                            <td class="p-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">{{ $pay->status_pembayaran }}</span>
                                            </td>
                                            <td class="p-4 flex space-x-2">
                                                @if ($pay->status_pembayaran === 'menunggu_verifikasi')
                                                    <button class="bg-green-600 text-white px-2.5 py-1 rounded text-xs font-semibold hover:bg-green-700">Terima</button>
                                                    <button class="bg-[#c52228] text-white px-2.5 py-1 rounded text-xs font-semibold hover:bg-[#a51c21]">Tolak</button>
                                                @else
                                                    <span class="text-xs text-gray-400 font-semibold">Sudah Diproses</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="p-8 text-center text-gray-400">Belum ada pembayaran yang membutuhkan tindakan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <!-- SECTION: ULASAN PRODUK -->
                    <section id="mgr-reviews" class="tab-content hidden space-y-6">
                        <h3 class="font-bold text-xl text-gray-900">Ulasan & Rating Produk</h3>
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden p-6 divide-y divide-gray-150 space-y-4">
                            @forelse ($myReviews as $rev)
                                <div class="pt-4 first:pt-0">
                                    <div class="flex justify-between items-center">
                                        <h4 class="font-semibold text-gray-900">{{ $rev->user->nama }} - <span class="text-gray-500 font-normal text-xs">{{ $rev->product->nama_produk }}</span></h4>
                                        <div class="flex text-yellow-400">
                                            @for ($i = 0; $i < $rev->rating; $i++)
                                                ★
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2">{{ $rev->komentar }}</p>
                                </div>
                            @empty
                                <p class="text-gray-400 text-center py-6">Belum ada ulasan produk.</p>
                            @endforelse
                        </div>
                    </section>
                @endif
            </main>
        </div>
    @endif


    <!-- ============================================== -->
    <!-- 3. USER DASHBOARD (COMPACT ICON NAV TOPBAR)   -->
    <!-- ============================================== -->
    @if ($roleName === 'user')
        @php
            $userKodePos = $user->kode_pos;
            $recommendedKopdes = $userKodePos ? \App\Models\Kopdes::where('status', 'aktif')->where('kode_pos', $userKodePos)->first() : null;
            if (!$recommendedKopdes && $userKodePos) {
                $prefix = substr($userKodePos, 0, 2);
                $recommendedKopdes = \App\Models\Kopdes::where('status', 'aktif')->where('kode_pos', 'LIKE', $prefix . '%')->first();
            }

            $selectedKopdesId = request('kopdes_id', $recommendedKopdes ? $recommendedKopdes->id_kopdes : null);
            $selectedKopdes = $selectedKopdesId ? \App\Models\Kopdes::find($selectedKopdesId) : null;

            $allActiveKopdes = \App\Models\Kopdes::where('status', 'aktif')->get();
            $myCatalog = $selectedKopdes ? $selectedKopdes->products()->with('category')->get() : collect();
            $myCarts = \App\Models\Cart::where('id_user', $user->id_user)->with('product')->get();
            $myHistory = \App\Models\Transaction::where('id_user', $user->id_user)->with(['kopdes', 'payment'])->latest()->get();
        @endphp

        <!-- Top Navbar (Centered Links matching user mockup) -->
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
                        <button onclick="switchTabTop('user-catalog')" class="tab-btn-top font-semibold text-sm border-b-2 border-[#c52228] text-gray-900 pb-1">Katalog Koperasi</button>
                        <a href="{{ route('products.index', ['kopdes_id' => $selectedKopdes->id_kopdes ?? '']) }}" class="font-semibold text-sm text-gray-600 hover:text-[#c52228] transition-all">Barang</a>
                        <button onclick="switchTabTop('user-history')" class="tab-btn-top font-semibold text-sm border-b-2 border-transparent text-gray-500 hover:text-gray-900 pb-1">Riwayat Belanja</button>
                    </div>

                    <!-- Right: Cart & Profile Icons -->
                    <div class="flex items-center space-x-4">
                        <button onclick="switchTabTop('user-carts')" title="Keranjang Belanja" class="tab-btn-top p-2 rounded-lg text-gray-600 hover:text-red-600 hover:bg-red-50 relative flex items-center transition-all cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            @if ($myCarts->count() > 0)
                                <span class="absolute top-0 right-0 bg-[#c52228] text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                                    {{ $myCarts->count() }}
                                </span>
                            @endif
                        </button>

                        <button onclick="switchTabTop('user-profile')" title="Profil Saya" class="tab-btn-top p-1 rounded-full text-gray-600 hover:text-[#c52228] transition-all flex items-center cursor-pointer">
                            @if ($user->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->foto))
                                <img src="{{ asset('storage/' . $user->foto) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-gray-200 shadow-sm">
                            @else
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-[#c52228] font-extrabold text-xs">
                                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                                </div>
                            @endif
                        </button>

                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-[#c52228] hover:bg-[#a51c21] text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Container -->
        <main class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">

            <!-- SECTION: KATALOG KOPERASI (PREVIEW BARANG) -->
            <section id="user-catalog" class="tab-content-top space-y-8">
                <!-- Dropdown Pilih Kopdes -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="font-bold text-lg text-gray-900">Koperasi Desa (KopDes) Anda</h2>
                        <p class="text-sm text-gray-500 mt-1">Sistem otomatis merekomendasikan KopDes berdasarkan Kode Pos <strong>({{ $user->kode_pos ?? 'Default' }})</strong> Anda.</p>
                    </div>
                    <div class="w-full md:w-80">
                        <form method="GET" action="{{ route('dashboard') }}" id="kopdes-select-form">
                            <select name="kopdes_id" onchange="document.getElementById('kopdes-select-form').submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
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
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-bold text-xl text-gray-900 flex items-center">
                                    <span class="w-2.5 h-6 bg-[#c52228] rounded-full mr-3"></span>
                                    Barang Tersedia: {{ $selectedKopdes->nama_kopdes }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1">Menampilkan beberapa barang terpopuler. Lihat halaman barang untuk katalog lengkap.</p>
                            </div>
                            <a href="{{ route('products.index', ['kopdes_id' => $selectedKopdes->id_kopdes]) }}" class="bg-[#c52228] hover:bg-[#a51c21] text-white px-4 py-2 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center">
                                Lihat Semua Barang ({{ $myCatalog->count() }}) &rarr;
                            </a>
                        </div>

                        <!-- Product Preview Grid (Max 4 items) -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            @forelse ($myCatalog->take(4) as $p)
                                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col justify-between p-4 space-y-3 hover:shadow-md transition-all">
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
                                        <button class="bg-[#c52228] hover:bg-[#a51c21] text-white px-2.5 py-1 rounded text-xs font-semibold shadow-sm cursor-pointer">+ Keranjang</button>
                                    </div>
                                </div>
                            @empty
                                <p class="col-span-4 text-center py-12 text-gray-400">Belum ada barang terdaftar di KopDes ini.</p>
                            @endforelse
                        </div>

                        @if ($myCatalog->count() > 4)
                            <div class="text-center pt-2">
                                <a href="{{ route('products.index', ['kopdes_id' => $selectedKopdes->id_kopdes]) }}" class="inline-flex items-center text-sm font-bold text-[#c52228] hover:underline">
                                    Lihat {{ $myCatalog->count() - 4 }} barang lainnya di halaman Barang &rarr;
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-12 text-gray-400 font-medium">Silakan pilih salah satu Koperasi Desa di atas terlebih dahulu.</div>
                @endif
            </section>

            <!-- SECTION: KERANJANG BELANJA -->
            <section id="user-carts" class="tab-content-top hidden space-y-6">
                <h3 class="font-bold text-xl text-gray-900">Keranjang Belanja Anda</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Cart Items -->
                    <div class="md:col-span-2 space-y-4">
                        @forelse ($myCarts as $c)
                            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-xs font-bold text-gray-400">BAG</div>
                                    <div>
                                        <h4 class="font-bold text-sm text-gray-900">{{ $c->product->nama_produk }}</h4>
                                        <span class="text-xs text-gray-400">Rp {{ number_format($c->product->harga, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="font-semibold text-sm">{{ $c->quantity }} pcs</span>
                                    <button class="text-red-600 text-sm font-semibold hover:underline">Hapus</button>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-400 text-center py-12 font-medium">Keranjang belanja Anda masih kosong.</p>
                        @endforelse
                    </div>

                    <!-- Checkout Summary Card -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm h-fit space-y-6">
                        <h4 class="font-bold text-lg text-gray-900">Ringkasan Checkout</h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Total Item</span>
                                <span>{{ $myCarts->sum('quantity') }} pcs</span>
                            </div>
                            <div class="flex justify-between font-bold text-gray-900 border-t border-gray-100 pt-3 text-base">
                                <span>Total Harga</span>
                                @php
                                    $cartTotal = $myCarts->sum(fn($c) => $c->product->harga * $c->quantity);
                                @endphp
                                <span>Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <button class="w-full bg-[#c52228] hover:bg-[#a51c21] text-white py-2.5 rounded-lg font-bold text-sm shadow-md transition-all cursor-pointer">Checkout Sekarang</button>
                    </div>
                </div>
            </section>

            <!-- SECTION: RIWAYAT TRANSAKSI -->
            <section id="user-history" class="tab-content-top hidden space-y-6">
                <h3 class="font-bold text-xl text-gray-900">Riwayat Belanja</h3>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase">
                                <th class="p-4">Kode Transaksi</th>
                                <th class="p-4">Koperasi</th>
                                <th class="p-4">Total</th>
                                <th class="p-4">Status Transaksi</th>
                                <th class="p-4">Status Bayar</th>
                                <th class="p-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-150 text-sm">
                            @forelse ($myHistory as $h)
                                <tr>
                                    <td class="p-4 font-semibold text-gray-900">{{ $h->kode_transaksi }}</td>
                                    <td class="p-4 text-gray-600">{{ $h->kopdes->nama_kopdes ?? 'N/A' }}</td>
                                    <td class="p-4 font-bold">Rp {{ number_format($h->total_harga, 0, ',', '.') }}</td>
                                    <td class="p-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 uppercase">{{ $h->status_transaksi }}</span>
                                    </td>
                                    <td class="p-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200">{{ $h->payment->status_pembayaran ?? 'Belum Bayar' }}</span>
                                    </td>
                                    <td class="p-4">
                                        @if (!$h->payment)
                                            <button class="bg-[#c52228] text-white px-2.5 py-1 rounded text-xs font-semibold shadow-sm hover:bg-[#a51c21]">Bayar</button>
                                        @else
                                            <button class="text-blue-600 font-semibold hover:underline">Detail</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="p-8 text-center text-gray-400">Belum ada riwayat belanja.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- SECTION: PROFIL SAYA (DESIGNED & ENHANCED) -->
            <section id="user-profile" class="tab-content-top hidden max-w-3xl mx-auto space-y-6">
                @if (session('success'))
                    <div class="bg-green-50 text-green-700 p-4 rounded-xl text-sm border border-green-100 font-semibold shadow-sm flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Profile Container Card -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <!-- Cover Header Banner -->
                    <div class="h-32 bg-gradient-to-r from-[#c52228] via-rose-700 to-red-800 relative">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md text-white text-xs px-3 py-1 rounded-full font-semibold">
                            Anggota Koperasi
                        </div>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8 pt-0 space-y-8 relative">
                        @csrf
                        
                        <!-- Avatar & Profile Header Info -->
                        <div class="flex flex-col sm:flex-row items-center sm:items-end space-y-4 sm:space-y-0 sm:space-x-6 -mt-16 mb-2">
                            <div class="relative group">
                                @if ($user->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->foto))
                                    <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="w-28 h-28 rounded-full object-cover shadow-lg border-4 border-white bg-white">
                                @else
                                    <div class="w-28 h-28 rounded-full bg-red-100 border-4 border-white shadow-lg flex items-center justify-center text-[#c52228] text-4xl font-extrabold">
                                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                                    </div>
                                @endif
                                <label for="foto" class="absolute bottom-0 right-0 bg-[#c52228] hover:bg-[#a51c21] text-white p-2 rounded-full shadow-md cursor-pointer transition-all hover:scale-110">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </label>
                                <input type="file" id="foto" name="foto" accept="image/*" class="hidden" onchange="this.form.submit()">
                            </div>

                            <div class="text-center sm:text-left flex-1">
                                <h2 class="text-2xl font-bold text-gray-900">{{ $user->nama }}</h2>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $user->email }}</p>
                                <div class="flex flex-wrap justify-center sm:justify-start gap-2 mt-3">
                                    <span class="bg-red-50 text-[#c52228] border border-red-100 text-[11px] font-bold px-2.5 py-0.5 rounded-full">Kode Pos: {{ $user->kode_pos ?? 'Belum diisi' }}</span>
                                    <span class="bg-gray-100 text-gray-600 text-[11px] font-bold px-2.5 py-0.5 rounded-full">Terdaftar: {{ $user->created_at ? $user->created_at->format('d M Y') : 'Baru' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Form Input Fields Grid -->
                        <div class="space-y-6 pt-4 border-t border-gray-100">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Detail Informasi Diri</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                                <div>
                                    <label for="nama" class="block text-xs font-bold text-gray-600 uppercase mb-1.5">Nama Lengkap</label>
                                    <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Email (Tetap)</label>
                                    <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 rounded-xl text-sm text-gray-500 cursor-not-allowed">
                                </div>
                                <div>
                                    <label for="no_hp" class="block text-xs font-bold text-gray-600 uppercase mb-1.5">Nomor Handphone</label>
                                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all">
                                </div>
                                <div>
                                    <label for="kode_pos" class="block text-xs font-bold text-gray-600 uppercase mb-1.5">Kode Pos (Area/Provinsi)</label>
                                    <input type="text" id="kode_pos" name="kode_pos" value="{{ old('kode_pos', $user->kode_pos) }}" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="alamat" class="block text-xs font-bold text-gray-600 uppercase mb-1.5">Detail Alamat Pengiriman (Diisi lengkap saat transaksi)</label>
                                    <input type="text" id="alamat" name="alamat" value="{{ old('alamat', $user->alamat) }}" placeholder="Contoh: Jl. Sudirman No. 12, RT 01 RW 02" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 flex justify-end">
                            <button type="submit" class="bg-[#c52228] hover:bg-[#a51c21] text-white py-2.5 px-6 rounded-xl font-bold text-sm shadow-md transition-all cursor-pointer hover:shadow-lg">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    @endif

    <!-- Simple Tab Switching JS -->
    <script>
        // Switch tab for Admin / Manager Sidebar Layout (White Theme)
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(function(content) {
                content.classList.add('hidden');
            });
            document.getElementById(tabId).classList.remove('hidden');

            document.querySelectorAll('.tab-btn').forEach(function(btn) {
                btn.classList.remove('bg-red-50', 'text-[#c52228]');
                btn.classList.add('hover:bg-gray-50', 'text-gray-600');
            });

            const currentBtn = event.currentTarget;
            currentBtn.classList.remove('hover:bg-gray-50', 'text-gray-600');
            currentBtn.classList.add('bg-red-50', 'text-[#c52228]');
        }

        // Switch tab for User Top Navbar Layout
        function switchTabTop(tabId) {
            document.querySelectorAll('.tab-content-top').forEach(function(content) {
                content.classList.add('hidden');
            });
            const target = document.getElementById(tabId);
            if (target) {
                target.classList.remove('hidden');
            }

            document.querySelectorAll('.tab-btn-top').forEach(function(btn) {
                btn.classList.remove('border-[#c52228]', 'text-gray-900', 'text-red-600', 'bg-red-50');
                if (btn.tagName.toLowerCase() === 'button' && !btn.querySelector('svg')) {
                    btn.classList.add('border-transparent', 'text-gray-500');
                } else if (btn.querySelector('svg')) {
                    btn.classList.add('text-gray-600');
                }
            });

            if (event && event.currentTarget) {
                const current = event.currentTarget;
                if (!current.querySelector('svg')) {
                    current.classList.remove('border-transparent', 'text-gray-500');
                    current.classList.add('border-[#c52228]', 'text-gray-900');
                } else {
                    current.classList.add('text-red-600', 'bg-red-50');
                }
            }
        }
    </script>
</body>
</html>
