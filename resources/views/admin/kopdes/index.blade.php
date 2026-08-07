@extends('layouts.admin')

@section('title', 'Kelola KopDes')

@section('content')
<div class="space-y-4">

    <!-- Flash Message -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-2.5 rounded-xl text-xs font-semibold shadow-xs" role="alert">
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Header Actions (Responsive) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl md:text-2xl font-bold text-gray-800 tracking-tight">Daftar KopDes</h1>
            <p class="text-xs text-gray-400 mt-0.5">Kelola data Koperasi Desa aktif dan nonaktif</p>
        </div>
        <div>
            <a href="{{ route('kopdes.create') }}" 
               class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-lg shadow-xs transition-all duration-150 text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah KopDes
            </a>
        </div>
    </div>

    <!-- Search & Filter Card (Compact) -->
    <div class="bg-white rounded-xl shadow-xs border border-gray-150 p-3">
        <form method="GET" action="{{ route('admin.kopdes') }}" class="flex flex-col sm:flex-row gap-2">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.602 10.602z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, alamat, kode pos..." 
                       class="w-full pl-9 pr-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs transition duration-150">
            </div>
            <div class="flex gap-2">
                <button type="submit" 
                        class="bg-gray-800 hover:bg-gray-900 text-white font-bold px-4 py-1.5 rounded-lg text-xs transition duration-150 cursor-pointer">
                    Cari
                </button>
                @if($search)
                <a href="{{ route('admin.kopdes') }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold px-4 py-1.5 rounded-lg text-xs transition duration-150 inline-flex items-center">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Card (Compact and Responsive) -->
    <div class="bg-white rounded-xl shadow-xs border border-gray-150 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-150">
                <thead class="bg-gray-50 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-left">
                    <tr>
                        <th class="px-4 py-2.5">Nama KopDes</th>
                        <th class="px-4 py-2.5">No. HP</th>
                        <th class="px-4 py-2.5">Kode Pos</th>
                        <th class="px-4 py-2.5">Provinsi</th>
                        <th class="px-4 py-2.5">Alamat</th>
                        <th class="px-4 py-2.5 text-center">Status</th>
                        <th class="px-4 py-2.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-150 bg-white text-xs text-gray-600">
                    @forelse($kopdes as $item)
                    <tr class="hover:bg-gray-50/75 transition-colors">
                        <td class="px-4 py-2.5 font-bold text-gray-800">{{ $item->nama_kopdes }}</td>
                        <td class="px-4 py-2.5">{{ $item->no_hp }}</td>
                        <td class="px-4 py-2.5">{{ $item->kode_pos }}</td>
                        <td class="px-4 py-2.5">{{ $item->provinsi }}</td>
                        <td class="px-4 py-2.5 max-w-xs truncate">{{ $item->alamat }}</td>
                        <td class="px-4 py-2.5 text-center">
                            @if($item->status === 'aktif')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-green-50 text-green-700 border border-green-150">
                                Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-gray-50 text-gray-500 border border-gray-150">
                                Nonaktif
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-2.5 text-right font-medium">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('kopdes.edit', $item->id_kopdes) }}" 
                                   class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-2 py-1 rounded-md transition duration-150">
                                    Edit
                                </a>
                                <form action="{{ route('kopdes.destroy', $item->id_kopdes) }}" method="POST" 
                                      class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data KopDes ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-2 py-1 rounded-md transition duration-150 cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                            Tidak ada data KopDes ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($kopdes->hasPages())
        <div class="px-4 py-2.5 bg-gray-50 border-t border-gray-100">
            {{ $kopdes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
