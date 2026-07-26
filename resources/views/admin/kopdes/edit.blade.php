@extends('layouts.admin')

@section('title', 'Edit KopDes')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.kopdes') }}" class="text-gray-500 hover:text-gray-800 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit KopDes</h1>
            <p class="text-sm text-gray-500 mt-1">Ubah informasi koperasi desa yang sudah terdaftar</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <form method="POST" action="{{ route('kopdes.update', $kopdes->id_kopdes) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama KopDes -->
            <div>
                <label for="nama_kopdes" class="block text-sm font-semibold text-gray-700 mb-2">Nama KopDes</label>
                <input type="text" name="nama_kopdes" id="nama_kopdes" value="{{ old('nama_kopdes', $kopdes->nama_kopdes) }}" required
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm transition @error('nama_kopdes') border-red-500 focus:ring-red-500 @enderror"
                       placeholder="Contoh: KopDes Sukamaju">
                @error('nama_kopdes')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $kopdes->email) }}" required
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm transition @error('email') border-red-500 focus:ring-red-500 @enderror"
                       placeholder="Contoh: sukamaju@kopdes.com">
                @error('email')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nomor Telepon -->
            <div>
                <label for="no_telp" class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp', $kopdes->no_telp) }}" required
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm transition @error('no_telp') border-red-500 focus:ring-red-500 @enderror"
                       placeholder="Contoh: 08123456789">
                @error('no_telp')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Alamat -->
            <div>
                <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                <textarea name="alamat" id="alamat" rows="4" required
                          class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm transition @error('alamat') border-red-500 focus:ring-red-500 @enderror"
                          placeholder="Masukkan alamat lengkap kantor koperasi desa...">{{ old('alamat', $kopdes->alamat) }}</textarea>
                @error('alamat')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                <select name="status" id="status" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm transition">
                    <option value="aktif" {{ old('status', $kopdes->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $kopdes->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.kopdes') }}" 
                   class="px-5 py-2.5 border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold rounded-xl text-sm transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl text-sm shadow transition">
                    Perbarui KopDes
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
