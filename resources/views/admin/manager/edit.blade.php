@extends('layouts.admin')

@section('title', 'Edit Manager')

@section('content')
<div class="max-w-xl mx-auto space-y-4">

    <!-- Header (Compact) -->
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.manager') }}" class="text-gray-400 hover:text-gray-700 transition-all p-1 hover:bg-gray-150 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-lg md:text-xl font-bold text-gray-800 tracking-tight">Edit Akun Manager</h1>
            <p class="text-xs text-gray-400 mt-0.5">Perbarui data profil manager terpilih</p>
        </div>
    </div>

    <!-- Form Card (Compact) -->
    <div class="bg-white rounded-xl shadow-xs border border-gray-150 p-4 sm:p-5">
        <form method="POST" action="{{ route('manager.update', $manager->id_user) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div>
                <label for="nama" class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $manager->nama) }}" required
                       class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs transition @error('nama') border-red-500 focus:ring-red-500 @enderror"
                       placeholder="Masukkan nama lengkap...">
                @error('nama')
                <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-gray-600 mb-1.5">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $manager->email) }}" required
                       class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs transition @error('email') border-red-500 focus:ring-red-500 @enderror"
                       placeholder="Contoh: manager@kopdes.com">
                @error('email')
                <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nomor HP -->
            <div>
                <label for="no_hp" class="block text-xs font-semibold text-gray-600 mb-1.5">Nomor HP / WhatsApp</label>
                <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $manager->no_hp) }}" required
                       class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs transition @error('no_hp') border-red-500 focus:ring-red-500 @enderror"
                       placeholder="Contoh: 081234567890">
                @error('no_hp')
                <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Alamat -->
            <div>
                <label for="alamat" class="block text-xs font-semibold text-gray-600 mb-1.5">Alamat Tinggal</label>
                <textarea name="alamat" id="alamat" rows="3" required
                          class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs transition @error('alamat') border-red-500 focus:ring-red-500 @enderror"
                          placeholder="Masukkan alamat tinggal lengkap...">{{ old('alamat', $manager->alamat) }}</textarea>
                @error('alamat')
                <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-xs font-semibold text-gray-600 mb-1.5">Status Akun</label>
                <select name="status" id="status" required
                        class="w-full px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500 text-xs transition cursor-pointer">
                    <option value="aktif" {{ old('status', $manager->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $manager->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-150">
                <a href="{{ route('admin.manager') }}" 
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
