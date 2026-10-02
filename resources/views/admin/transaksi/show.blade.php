@extends('layouts.admin')

@section('title', 'Detail Transaksi - ' . $kopdes->nama_kopdes)

@section('content')
<div class="space-y-6">
    <!-- Header + Navigation -->
    <div>
        <a href="{{ route('admin.transaksi') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-600 hover:text-red-700 mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Koperasi
        </a>
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">{{ $kopdes->nama_kopdes }}</h1>
        <p class="text-sm text-gray-500">{{ $kopdes->alamat ?? 'Wilayah ' . $kopdes->provinsi }}</p>
    </div>

    <!-- 1. Ringkasan Keuangan KopDes -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm">
            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Total Pemasukan</p>
            <p class="text-2xl font-extrabold text-gray-800 mt-1">Rp {{ number_format($kopdes->total_pemasukan, 0, ',', '.') }}</p>
            <span class="text-[11px] text-gray-400">Transaksi selesai & diproses</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-rose-100 shadow-sm">
            <p class="text-xs font-semibold text-rose-500 uppercase tracking-wider">Total Pengeluaran / Refund</p>
            <p class="text-2xl font-extrabold text-gray-800 mt-1">Rp {{ number_format($kopdes->total_pengeluaran, 0, ',', '.') }}</p>
            <span class="text-[11px] text-gray-400">Transaksi dibatalkan</span>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-blue-100 shadow-sm">
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Total Volume Order</p>
            <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $kopdes->transactions->count() }} Pesanan</p>
            <span class="text-[11px] text-gray-400">Semua riwayat terdaftar</span>
        </div>
    </div>

    <!-- 2. Catatan Pengeluaran Operasional -->
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-xs text-amber-900 space-y-1">
        <div class="flex items-center gap-1.5 font-bold text-amber-800">
            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>Catatan Pengeluaran Operasional & Modal</span>
        </div>
        <p class="text-amber-700 pl-5">
            Pengeluaran direkap otomatis berdasarkan pengembalian dana transaksi dibatalkan serta modal penyediaan stok ke supplier KopDes {{ $kopdes->nama_kopdes }}.
        </p>
    </div>

    <!-- 3. Tabel Detail Barang yang Dibeli User -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="font-bold text-sm text-gray-700 uppercase tracking-wider">Daftar Barang Dibeli User (Pemasukan)</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-100/70 border-b border-gray-200 font-semibold text-gray-500 uppercase">
                        <th class="px-6 py-4">Kode TRX</th>
                        <th class="px-6 py-4">Pembeli</th>
                        <th class="px-6 py-4">Rincian Barang Dibeli</th>
                        <th class="px-6 py-4">Total Harga</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($kopdes->transactions as $trx)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $trx->kode_transaksi }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">{{ $trx->user->name ?? 'Guest' }}</div>
                                <div class="text-gray-400 text-[10px]">{{ $trx->user->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <ul class="space-y-1">
                                    @foreach($trx->details as $detail)
                                        <li class="flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                            <span class="font-medium text-gray-800">{{ $detail->product->nama_produk ?? 'Produk Dihapus' }}</span> 
                                            <span class="text-gray-400">({{ $detail->quantity }}x)</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-6 py-4 font-bold text-emerald-600">
                                Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'menunggu_pembayaran' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'menunggu_verifikasi' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'diproses'            => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'dikirim'             => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'selesai'             => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'dibatalkan'          => 'bg-rose-50 text-rose-700 border-rose-200',
                                    ];
                                    $class = $statusClasses[$trx->status_transaksi] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-medium border {{ $class }}">
                                    {{ ucfirst(str_replace('_', ' ', $trx->status_transaksi)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-[11px]">
                                {{ $trx->created_at ? $trx->created_at->format('d/m/Y H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                Belum ada riwayat transaksi di KopDes ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection