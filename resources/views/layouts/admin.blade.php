<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - KopDes</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 antialiased font-sans">

<div class="min-h-screen flex flex-col md:flex-row">

    <!-- Mobile Top Navigation Bar -->
    <header class="md:hidden bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between sticky top-0 z-30">
        <div class="flex items-center gap-3">
            <button onclick="toggleAdminSidebar()" class="p-1 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-all focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="w-8 h-8 rounded-lg object-cover shadow-xs">
                <span class="text-lg font-extrabold text-red-600 tracking-tight">KOPDES</span>
            </div>
        </div>
        <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center font-bold text-red-600 text-xs shadow-xs">
            {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
        </div>
    </header>

    <!-- Overlay Backdrop for Mobile Sidebar -->
    <div id="admin-sidebar-backdrop" onclick="closeAdminSidebar()" class="fixed inset-0 bg-black/40 z-40 hidden md:hidden transition-opacity duration-300 opacity-0"></div>

    <!-- Sidebar Container -->
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-50 w-60 bg-white border-r border-gray-200 flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out md:static md:translate-x-0 h-screen sticky top-0 shadow-xs">
        
        <!-- Sidebar Brand Logo -->
        <div class="border-b border-gray-150 px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo KopDes" class="w-8 h-8 rounded-lg object-cover shadow-xs">
                <div>
                    <h1 class="text-lg font-extrabold text-red-600 tracking-tight leading-none">KOPDES</h1>
                    <span class="text-[10px] font-semibold text-gray-400 mt-0.5 block uppercase">Admin Panel</span>
                </div>
            </div>
            <!-- Close Button for Mobile -->
            <button onclick="closeAdminSidebar()" class="md:hidden p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Sidebar Navigation Menu -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm transition-all duration-150
                {{ request()->routeIs('dashboard')
                    ? 'bg-red-50 text-red-600 font-bold border-l-2 border-red-600'
                    : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.kopdes') }}"
                class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm transition-all duration-150
                {{ request()->routeIs('admin.kopdes') || request()->routeIs('kopdes.*')
                    ? 'bg-red-50 text-red-600 font-bold border-l-2 border-red-600'
                    : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 16.5h1.5M13.5 16.5H15m-10.5-9h1.5m-1.5 3h1.5m-1.5 3h1.5m10.5-6h1.5m-1.5 3h1.5m-1.5 3h1.5m-13.5 3h16.5" />
                </svg>
                <span>KopDes</span>
            </a>

            <a href="{{ route('admin.manager') }}"
                class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm transition-all duration-150
                {{ request()->routeIs('admin.manager') || request()->routeIs('manager.*')
                    ? 'bg-red-50 text-red-600 font-bold border-l-2 border-red-600'
                    : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
                <span>Manager</span>
            </a>

            <a href="{{ route('admin.transaksi') }}"
                class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm transition-all duration-150
                {{ request()->routeIs('admin.transaksi')
                    ? 'bg-red-50 text-red-600 font-bold border-l-2 border-red-600'
                    : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <span>Transaksi</span>
            </a>

            <a href="{{ route('admin.pembayaran') }}"
                class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm transition-all duration-150
                {{ request()->routeIs('admin.pembayaran')
                    ? 'bg-red-50 text-red-600 font-bold border-l-2 border-red-600'
                    : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                </svg>
                <span>Pembayaran</span>
            </a>

            <a href="{{ route('admin.laporan') }}"
                class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm transition-all duration-150
                {{ request()->routeIs('admin.laporan')
                    ? 'bg-red-50 text-red-600 font-bold border-l-2 border-red-600'
                    : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900 font-medium' }}">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
                <span>Laporan</span>
            </a>
        </nav>

        <!-- Sidebar User Footer -->
        <div class="border-t border-gray-150 p-3.5 space-y-3">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold text-sm shadow-xs">
                    {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="font-semibold text-gray-800 text-xs truncate leading-tight">
                        {{ Auth::user()->nama }}
                    </h3>
                    <p class="text-[10px] text-gray-400 truncate">
                        {{ Auth::user()->role->nama_role ?? 'Administrator' }}
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full rounded-lg bg-gray-50 border border-gray-200 py-1.5 text-xs font-semibold text-gray-700 hover:bg-red-50 hover:text-red-600 hover:border-red-100 transition-all duration-150 cursor-pointer">
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content wrapper -->
    <div class="flex-1 flex flex-col min-w-0">

        <!-- Top Header for Desktop -->
        <header class="hidden md:flex bg-white border-b border-gray-200 px-6 py-3.5 justify-between items-center sticky top-0 z-20">
            <div>
                <h2 class="text-lg font-bold text-gray-800 leading-tight">
                    @yield('title')
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">
                    Platform Pusat Sistem Manajemen Koperasi Desa
                </p>
            </div>
            <div class="flex items-center gap-3.5">
                <span class="text-xs text-gray-400 font-medium">
                    {{ now()->format('d F Y') }}
                </span>
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center font-bold text-red-600 text-xs shadow-xs">
                    {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                </div>
            </div>
        </header>

        <!-- Main Inner Content -->
        <main class="flex-1 p-4 md:p-6 overflow-y-auto">
            @yield('content')
        </main>

    </div>

</div>

<!-- JavaScript for Sidebar toggles -->
<script>
    function toggleAdminSidebar() {
        const sidebar = document.getElementById('admin-sidebar');
        const backdrop = document.getElementById('admin-sidebar-backdrop');
        if (sidebar && backdrop) {
            const isHidden = sidebar.classList.contains('-translate-x-full');
            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
            } else {
                closeAdminSidebar();
            }
        }
    }

    function closeAdminSidebar() {
        const sidebar = document.getElementById('admin-sidebar');
        const backdrop = document.getElementById('admin-sidebar-backdrop');
        if (sidebar && backdrop) {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('opacity-0');
            setTimeout(() => backdrop.classList.add('hidden'), 300);
        }
    }
</script>

</body>
</html>