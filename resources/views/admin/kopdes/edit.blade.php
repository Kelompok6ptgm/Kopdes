@extends('layouts.admin')

@section('title', 'Edit KopDes')

@section('content')
<div class="max-w-xl mx-auto space-y-4">

    <!-- Header (Compact) -->
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.kopdes') }}" class="text-gray-400 hover:text-gray-700 transition-all p-1 hover:bg-gray-150 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-lg md:text-xl font-bold text-gray-800 tracking-tight">Edit KopDes</h1>
            <p class="text-xs text-gray-400 mt-0.5">Ubah informasi koperasi desa yang sudah terdaftar</p>
        </div>
    </div>

    <!-- Form Card (Compact) -->
    <div class="bg-white rounded-xl shadow-xs border border-gray-150 p-4 sm:p-5">
        <form method="POST" action="{{ route('kopdes.update', $kopdes->id_kopdes) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Nama KopDes -->
            <div>
                <label for="nama_kopdes" class="block text-xs font-semibold text-gray-600 mb-1.5">Nama KopDes</label>
                <input type="text" name="nama_kopdes" id="nama_kopdes" value="{{ old('nama_kopdes', $kopdes->nama_kopdes) }}" required
                       class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs transition @error('nama_kopdes') border-red-500 focus:ring-red-500 @enderror"
                       placeholder="Contoh: KopDes Sukamaju">
                @error('nama_kopdes')
                <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- No. HP / Telepon -->
            <div>
                <label for="no_hp" class="block text-xs font-semibold text-gray-600 mb-1.5">Nomor HP / Telepon</label>
                <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $kopdes->no_hp) }}" required
                       class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs transition @error('no_hp') border-red-500 focus:ring-red-500 @enderror"
                       placeholder="Contoh: 08123456789">
                @error('no_hp')
                <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid: Kode Pos & Provinsi -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Kode Pos -->
                <div>
                    <label for="kode_pos" class="block text-xs font-semibold text-gray-600 mb-1.5">Kode Pos</label>
                    <input type="text" name="kode_pos" id="kode_pos" value="{{ old('kode_pos', $kopdes->kode_pos) }}" required
                           class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs transition @error('kode_pos') border-red-500 focus:ring-red-500 @enderror"
                           placeholder="Contoh: 12345">
                    @error('kode_pos')
                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Provinsi -->
                <div>
                    <label for="provinsi" class="block text-xs font-semibold text-gray-600 mb-1.5">Provinsi</label>
                    <input type="text" name="provinsi" id="provinsi" value="{{ old('provinsi', $kopdes->provinsi) }}" required
                           class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs transition @error('provinsi') border-red-500 focus:ring-red-500 @enderror"
                           placeholder="Contoh: Jawa Barat">
                    @error('provinsi')
                    <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kota / Kabupaten -->
                <div>
                    <label for="kota" class="block text-xs font-semibold text-gray-600 mb-1.5">Kota / Kabupaten</label>
                    <input type="text" name="kota" id="kota" value="{{ old('kota', $kopdes->kota) }}"
                           class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs transition"
                           placeholder="Contoh: Jakarta Selatan">
                </div>

                <!-- Kecamatan -->
                <div>
                    <label for="kecamatan" class="block text-xs font-semibold text-gray-600 mb-1.5">Kecamatan</label>
                    <input type="text" name="kecamatan" id="kecamatan" value="{{ old('kecamatan', $kopdes->kecamatan) }}"
                           class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs transition"
                           placeholder="Contoh: Kebayoran Baru">
                </div>
            </div>

            <!-- Alamat -->
            <div>
                <label for="alamat" class="block text-xs font-semibold text-gray-600 mb-1.5">Alamat Lengkap</label>
                <textarea name="alamat" id="alamat" rows="3" required
                          class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs transition @error('alamat') border-red-500 focus:ring-red-500 @enderror"
                          placeholder="Masukkan alamat lengkap kantor koperasi desa...">{{ old('alamat', $kopdes->alamat) }}</textarea>
                @error('alamat')
                <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
                <select name="status" id="status" required
                        class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs transition cursor-pointer">
                    <option value="aktif" {{ old('status', $kopdes->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $kopdes->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons (Compact border) -->
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-150">
                <a href="{{ route('admin.kopdes') }}" 
                   class="px-4 py-1.5 border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold rounded-lg text-xs transition duration-150">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-1.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg text-xs shadow-xs transition duration-150 cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
