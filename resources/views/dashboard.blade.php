<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kopdes</title>
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
<body class="bg-gray-100 min-h-screen">
    <!-- Top Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Kopdes Logo" class="h-8 w-auto">
                    <span class="text-[#c52228] font-bold text-lg">Koperasi Desa</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500 font-medium">Halo, <strong class="text-gray-900">{{ Auth::user()->nama }}</strong></span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-[#c52228] hover:bg-[#a51c21] text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm cursor-pointer">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-8 text-sm border border-green-100 shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Banner Cover -->
            <div class="h-32 bg-gradient-to-r from-red-600 to-red-800 flex items-center justify-between px-8 text-white relative">
                <div>
                    <h2 class="text-2xl font-bold">Dashboard Anggota</h2>
                    <p class="text-red-100 text-xs mt-1">Selamat datang di sistem manajemen anggota Koperasi Desa.</p>
                </div>
                <!-- Mini Badge for Role -->
                <span class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                    Role: {{ Auth::user()->role->nama_role ?? 'user' }}
                </span>
            </div>

            <!-- Profile Info Body -->
            <div class="p-8">
                <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6 border-b border-gray-100 pb-8">
                    <!-- User Placeholder Avatar -->
                    <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center text-red-600 text-3xl font-extrabold shadow-inner">
                        {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ Auth::user()->nama }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ Auth::user()->email }}</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700 border border-green-200">
                                Status: {{ ucfirst(Auth::user()->status) }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 uppercase">
                                {{ Auth::user()->role->nama_role ?? 'user' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Detailed Information Grid -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nama Lengkap</h4>
                        <p class="text-gray-900 font-medium mt-1">{{ Auth::user()->nama }}</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nomor Handphone</h4>
                        <p class="text-gray-900 font-medium mt-1">{{ Auth::user()->no_hp }}</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Email Address</h4>
                        <p class="text-gray-900 font-medium mt-1">{{ Auth::user()->email }}</p>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Alamat</h4>
                        <p class="text-gray-900 font-medium mt-1">{{ Auth::user()->alamat }}</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
