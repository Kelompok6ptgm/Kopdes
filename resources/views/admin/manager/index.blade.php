@extends('layouts.admin')

@section('title', 'Kelola Manager')

@section('content')
<div class="space-y-6">

    <!-- Flash Message -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative shadow-sm" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Error Validation Messages (For Reset Password) -->
    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative shadow-sm" role="alert">
        <strong class="font-bold">Terjadi Kesalahan!</strong>
        <ul class="list-disc pl-5 mt-1 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Akun Manager</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data dan hak akses para Manager Koperasi Desa</p>
        </div>
        <div>
            <a href="{{ route('manager.create') }}" 
               class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow transition duration-150 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                </svg>
                Tambah Manager
            </a>
        </div>
    </div>

    <!-- Search Card -->
    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
        <form method="GET" action="{{ route('admin.manager') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.602 10.602z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, nomor HP, alamat..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm transition duration-150">
            </div>
            <div class="flex gap-2">
                <button type="submit" 
                        class="bg-gray-800 hover:bg-gray-900 text-white font-medium px-5 py-2.5 rounded-xl text-sm transition duration-150">
                    Cari
                </button>
                @if($search)
                <a href="{{ route('admin.manager') }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-5 py-2.5 rounded-xl text-sm transition duration-150 inline-flex items-center">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor HP</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Alamat</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($managers as $item)
                    <tr class="hover:bg-gray-50 transition duration-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">{{ $item->nama }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->no_hp }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $item->alamat }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($item->status === 'aktif')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Nonaktif
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" 
                                        onclick="openResetModal('{{ $item->id_user }}', '{{ $item->nama }}')"
                                        class="text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-lg transition duration-150 text-xs">
                                    Reset Password
                                </button>
                                <a href="{{ route('manager.edit', $item->id_user) }}" 
                                   class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition duration-150 text-xs">
                                    Edit
                                </a>
                                <form action="{{ route('manager.destroy', $item->id_user) }}" method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun Manager ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition duration-150 text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                            Tidak ada data Manager ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($managers->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $managers->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500/75" onclick="closeResetModal()"></div>

        <!-- Center modal content -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
            <form id="resetPasswordForm" method="POST" action="">
                @csrf
                <div class="bg-white px-6 py-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Reset Password Manager</h3>
                    <p class="text-sm text-gray-500 mt-1">Nama: <span id="resetManagerName" class="font-semibold text-gray-800"></span></p>
                </div>
                <div class="bg-white px-6 py-6 space-y-4">
                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                        <input type="password" name="password" id="password" required minlength="8"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm transition"
                               placeholder="Min 8 karakter...">
                    </div>
                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm transition"
                               placeholder="Ketik ulang password baru...">
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeResetModal()"
                            class="px-4 py-2 border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 font-semibold rounded-xl text-sm transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-xl text-sm shadow transition">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openResetModal(managerId, managerName) {
        const modal = document.getElementById('resetPasswordModal');
        const form = document.getElementById('resetPasswordForm');
        const nameSpan = document.getElementById('resetManagerName');
        
        nameSpan.innerText = managerName;
        // set action URL dynamically
        form.action = `/admin/manager/${managerId}/reset-password`;
        
        modal.classList.remove('hidden');
    }

    function closeResetModal() {
        const modal = document.getElementById('resetPasswordModal');
        modal.classList.add('hidden');
    }
</script>
@endsection
