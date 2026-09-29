@extends('layouts.admin')

@section('title', 'Transaksi Koperasi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">
                Transaksi Koperasi Desa (KopDes)
            </h1>
            <p class="text-sm text-gray-500">Pilih salah satu kartu KopDes untuk melihat detail transaksi, pengeluaran, dan pemasukan.</p>
        </div>
    </div>

    <!-- Grid Kartu KopDes Visual Nuansa Merah -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($kopdesList as $kopdes)
            <a href="{{ route('admin.transaksi.show', $kopdes->id_kopdes) }}" 
               class="group bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col hover:-translate-y-1 hover:border-red-300">
                
                <!-- Card Header Visual (Gradient Merah ke Rose) -->
                <div class="h-40 bg-gradient-to-tr from-red-600 to-rose-500 relative p-5 flex flex-col justify-between overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                    
                    <div class="flex justify-between items-start z-10">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-white/20 text-white backdrop-blur-md">
                            {{ ucfirst($kopdes->status) }}
                        </span>
                        <span class="text-xs text-white/90 font-medium bg-black/20 px-2.5 py-1 rounded-lg backdrop-blur-sm">
                            {{ $kopdes->transactions_count }} Order
                        </span>
                    </div>

                    <div class="z-10">
                        <h2 class="text-xl font-extrabold text-white group-hover:underline decoration-2">
                            {{ $kopdes->nama_kopdes }}
                        </h2>
                        <p class="text-xs text-red-100 mt-0.5">
                            {{ $kopdes->alamat ?? 'Wilayah ' . $kopdes->provinsi }}
                        </p>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5 bg-white flex-1 flex flex-col justify-between">
                    <p class="text-xs text-gray-500">
                        Buka rincian lengkap mengenai pemasukan penjualan produk user serta rekap pengeluaran operasional KopDes ini.
                    </p>

                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-xs font-semibold text-red-600">
                        <span>Lihat Laporan Transaksi</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-12 text-center text-gray-400 border border-gray-200">
                Belum ada data Koperasi Desa.
            </div>
        @endforelse
    </div>
</div>
@endsection