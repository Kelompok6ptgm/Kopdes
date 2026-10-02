@extends('layouts.admin')

@section('content')
<!-- Tailwind CDN & FontAwesome -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<style>
    /* Custom Styling Fallback & Gradient KopDes */
    .bg-kopdes-gradient {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%) !important;
    }
</style>

<div class="min-h-screen bg-slate-50 p-4 md:p-8 font-sans antialiased text-slate-800">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <div class="flex items-center gap-2 text-red-600 font-semibold text-xs tracking-wider uppercase mb-1">
                    <i class="fa-solid fa-chart-line"></i> Laporan Keuangan
                </div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Rekapan Bulanan Per KopDes
                </h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Ringkasan statistik transaksi dan omzet Koperasi Desa seluruh wilayah.
                </p>
            </div>
            <div>
                <button onclick="window.print()" class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium px-4 py-2.5 rounded-xl transition-all text-sm shadow-sm">
                    <i class="fa-solid fa-print"></i> Cetak Laporan
                </button>
            </div>
        </div>

        @php
            $namaBulan = [
                'all' => 'Semua Bulan (Keseluruhan)',
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];
            $totalSemuaPemasukan = $laporan->sum('total_pemasukan');
            $totalSemuaTransaksi = $laporan->sum('total_transaksi');
            $totalTransaksiBerhasil = $laporan->sum('transaksi_berhasil');
        @endphp

        <!-- Stat Cards / KPI Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Total Omzet / Pemasukan -->
            <div class="bg-kopdes-gradient text-white p-6 rounded-2xl shadow-lg shadow-red-600/30 relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 opacity-20 text-7xl text-white">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div class="text-red-100 text-xs font-semibold uppercase tracking-wider mb-2">Total Omzet Pemasukan</div>
                <div class="text-3xl font-black tracking-tight text-white">
                    Rp {{ number_format($totalSemuaPemasukan, 0, ',', '.') }}
                </div>
                <div class="mt-3 text-xs text-white bg-white/20 backdrop-blur-md px-3 py-1 rounded-full inline-block font-medium border border-white/10">
                    Periode: {{ $namaBulan[$bulan] ?? $bulan }} {{ $tahun }}
                </div>
            </div>

            <!-- Transaksi Berhasil -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <div class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Transaksi Berhasil</div>
                    <div class="text-2xl font-bold text-slate-900 mt-0.5">
                        {{ number_format($totalTransaksiBerhasil) }} <span class="text-sm font-normal text-slate-400">Trx</span>
                    </div>
                    <div class="text-xs text-emerald-600 font-medium mt-1">Status Selesai & Diproses</div>
                </div>
            </div>

            <!-- Total Semua Transaksi -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <div class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Total Semua Transaksi</div>
                    <div class="text-2xl font-bold text-slate-900 mt-0.5">
                        {{ number_format($totalSemuaTransaksi) }} <span class="text-sm font-normal text-slate-400">Trx</span>
                    </div>
                    <div class="text-xs text-slate-500 font-medium mt-1">Termasuk Dibatalkan/Pending</div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200/80">
            <form action="{{ route('admin.laporan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Bulan Laporan</label>
                    <div class="relative">
                        <select name="bulan" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-3 appearance-none focus:outline-none focus:ring-2 focus:ring-red-600 focus:bg-white transition-all font-medium">
                            @foreach($namaBulan as $key => $val)
                                <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>
                                    {{ $val }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 text-xs">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Tahun Laporan</label>
                    <div class="relative">
                        <select name="tahun" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl px-4 py-3 appearance-none focus:outline-none focus:ring-2 focus:ring-red-600 focus:bg-white transition-all font-medium">
                            @foreach(range(date('Y') - 3, date('Y')) as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 text-xs">
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-xl transition-all shadow-md shadow-red-600/20 text-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-filter"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Table Data Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Rincian Performa KopDes</h3>
                    <p class="text-xs text-slate-500">Rekapan aktif untuk: <span class="font-semibold text-red-600">{{ $namaBulan[$bulan] ?? $bulan }} {{ $tahun }}</span></p>
                </div>
                <span class="text-xs font-semibold text-red-700 bg-red-50 px-3 py-1.5 rounded-full border border-red-100">
                    {{ count($laporan) }} KopDes Terdaftar
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/80 text-slate-500 text-[11px] font-bold uppercase tracking-wider">
                            <th class="py-4 px-6 text-center w-16">No</th>
                            <th class="py-4 px-6">Nama Koperasi Desa</th>
                            <th class="py-4 px-6 text-center">Total Transaksi</th>
                            <th class="py-4 px-6 text-center">Status Berhasil</th>
                            <th class="py-4 px-6 text-right">Total Pemasukan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($laporan as $index => $item)
                            @php
                                $nama = $item->nama_kopdes ?? $item->nama_koperasi ?? 'KopDes';
                                $initial = strtoupper(substr($nama, 0, 2));
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="py-4 px-6 text-center font-medium text-slate-400 group-hover:text-slate-600">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-red-700 to-red-500 text-white flex items-center justify-center font-bold text-xs shadow-sm shadow-red-600/30">
                                            {{ $initial }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 group-hover:text-red-600 transition-colors">
                                                {{ $nama }}
                                            </div>
                                            <div class="text-xs text-slate-400">
                                                ID: #{{ $item->id_kopdes ?? ($index + 1) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center font-semibold text-slate-700">
                                    {{ number_format($item->total_transaksi) }}
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-check text-[10px]"></i> {{ number_format($item->transaksi_berhasil) }} Trx
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right font-black text-slate-900 text-base">
                                    Rp {{ number_format($item->total_pemasukan, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 text-2xl mb-3">
                                            <i class="fa-solid fa-inbox"></i>
                                        </div>
                                        <h4 class="font-bold text-slate-700 mb-1">Belum Ada Data Transaksi</h4>
                                        <p class="text-xs text-slate-400 max-w-xs">
                                            Tidak ada catatan aktivitas transaksi untuk bulan {{ $namaBulan[$bulan] ?? $bulan }} {{ $tahun }}.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection