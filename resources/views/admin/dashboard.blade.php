@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-5">

    <!-- Header (Responsive Layout) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-gray-800 tracking-tight">
                Selamat Datang, {{ Auth::user()->nama }} 👋
            </h1>
            <p class="text-xs text-gray-400 mt-0.5">
                Dashboard Administrator KopDes Merah Putih
            </p>
        </div>

        <div class="flex items-center gap-2 sm:text-right">
            <span class="inline-block px-3 py-1 bg-red-100 text-red-600 rounded-full font-bold text-xs uppercase tracking-wider">
                {{ strtoupper(Auth::user()->role->nama_role ?? 'ADMIN') }}
            </span>
        </div>
    </div>

    <!-- Statistik (Responsive Grid, Compact Size) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-white rounded-xl shadow-xs border border-gray-150 p-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total KopDes</p>
            <h2 class="text-2xl font-extrabold mt-1 text-red-600">
                {{ $totalKopdes }}
            </h2>
            <p class="text-green-600 text-[10px] font-medium mt-1">
                ↑ Total terdaftar
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-xs border border-gray-150 p-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Manager</p>
            <h2 class="text-2xl font-extrabold mt-1 text-blue-600">
                {{ $totalManager }}
            </h2>
            <p class="text-green-600 text-[10px] font-medium mt-1">
                ↑ Akun pengelola
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-xs border border-gray-150 p-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Transaksi</p>
            <h2 class="text-2xl font-extrabold mt-1 text-orange-500">
                {{ number_format($totalTransaksi, 0, ',', '.') }}
            </h2>
            <p class="text-green-600 text-[10px] font-medium mt-1">
                ↑ Seluruh transaksi
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-xs border border-gray-150 p-4">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pembayaran</p>
            <h2 class="text-2xl font-extrabold mt-1 text-green-600">
                Rp{{ number_format($totalPembayaran, 0, ',', '.') }}
            </h2>
            <p class="text-green-600 text-[10px] font-medium mt-1">
                ↑ Status Sukses
            </p>
        </div>

    </div>

    <!-- Table (Compact and Clean Layout) -->
    <div class="bg-white rounded-xl shadow-xs border border-gray-150 overflow-hidden">

        <div class="border-b border-gray-150 px-4 py-3 flex justify-between items-center bg-gray-50/50">
            <h2 class="font-bold text-sm text-gray-800">
                Transaksi Terbaru
            </h2>
            <a href="{{ route('admin.transaksi') }}"
                class="bg-red-600 hover:bg-red-700 text-white px-3.5 py-1.5 rounded-lg text-xs font-semibold shadow-xs transition-all duration-150">
                Lihat Semua
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-150">
                <thead class="bg-gray-50 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-left">
                    <tr>
                        <th class="px-4 py-2.5">Invoice</th>
                        <th class="px-4 py-2.5">KopDes</th>
                        <th class="px-4 py-2.5">Pembeli</th>
                        <th class="px-4 py-2.5">Total</th>
                        <th class="px-4 py-2.5">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-150 bg-white text-xs text-gray-600">
                    @forelse($latestTransactions as $tx)
                    <tr class="hover:bg-gray-50/75 transition-colors">
                        <td class="px-4 py-2.5 font-bold text-gray-800">
                            {{ $tx->kode_transaksi ?? $tx->invoice ?? ('#' . $tx->id_transaction) }}
                        </td>
                        <td class="px-4 py-2.5 font-medium text-gray-700">
                            {{ $tx->kopdes->nama_kopdes ?? '-' }}
                        </td>
                        <td class="px-4 py-2.5 text-gray-600">
                            {{ $tx->user->nama ?? $tx->user->name ?? '-' }}
                        </td>
                        <td class="px-4 py-2.5 font-bold text-gray-900">
                            Rp{{ number_format($tx->total_harga ?? $tx->total ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2.5">
                            @php
                                $status = $tx->status_transaksi ?? $tx->status ?? 'menunggu';
                                $statusClasses = [
                                    'selesai'             => 'bg-green-50 text-green-700 border-green-200',
                                    'diproses'            => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'dikirim'             => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'menunggu_verifikasi' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'menunggu_pembayaran' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    'pending'             => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                    'dibatalkan'          => 'bg-red-50 text-red-700 border-red-200',
                                ];
                                $badgeClass = $statusClasses[$status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                $badgeLabel = ucfirst(str_replace('_', ' ', $status));
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $badgeClass }}">
                                {{ $badgeLabel }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada transaksi terbaru</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection