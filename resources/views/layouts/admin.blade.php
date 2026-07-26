<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title') - KopDes</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-xl flex flex-col">

        <!-- Logo -->
        <div class="border-b p-6 flex items-center gap-3">

             <img src="{{ asset('images/logo.jpg') }}"
            alt="Logo KopDes"
            class="w-10 h-10 rounded-lg object-cover shadow-sm">

            <div>
                <h1 class="text-2xl font-extrabold text-red-600 leading-none">
                    KOPDES
                </h1>

                <p class="text-xs text-gray-500 mt-1">
                    Admin Platform
                </p>
            </div>

        </div>

        <!-- Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2">

            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
                {{ request()->routeIs('dashboard')
                    ? 'bg-red-50 text-red-600 border-l-4 border-red-600 font-semibold'
                    : 'text-gray-500 hover:bg-red-50 hover:text-red-600' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                <span>Dashboard</span>

            </a>

            <a href="{{ route('admin.kopdes') }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
                {{ request()->routeIs('admin.kopdes') || request()->routeIs('kopdes.*')
                    ? 'bg-red-50 text-red-600 border-l-4 border-red-600 font-semibold'
                    : 'text-gray-500 hover:bg-red-50 hover:text-red-600' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 16.5h1.5M13.5 16.5H15m-10.5-9h1.5m-1.5 3h1.5m-1.5 3h1.5m10.5-6h1.5m-1.5 3h1.5m-1.5 3h1.5m-13.5 3h16.5" />
                </svg>
                <span>KopDes</span>

            </a>

            <a href="{{ route('admin.manager') }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
                {{ request()->routeIs('admin.manager') || request()->routeIs('manager.*')
                    ? 'bg-red-50 text-red-600 border-l-4 border-red-600 font-semibold'
                    : 'text-gray-500 hover:bg-red-50 hover:text-red-600' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
                <span>Manager</span>

            </a>

            <a href="{{ route('admin.transaksi') }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
                {{ request()->routeIs('admin.transaksi')
                    ? 'bg-red-50 text-red-600 border-l-4 border-red-600 font-semibold'
                    : 'text-gray-500 hover:bg-red-50 hover:text-red-600' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <span>Transaksi</span>

            </a>

            <a href="{{ route('admin.pembayaran') }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
                {{ request()->routeIs('admin.pembayaran')
                    ? 'bg-red-50 text-red-600 border-l-4 border-red-600 font-semibold'
                    : 'text-gray-500 hover:bg-red-50 hover:text-red-600' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                </svg>
                <span>Pembayaran</span>

            </a>

            <a href="{{ route('admin.laporan') }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 transition-all duration-200
                {{ request()->routeIs('admin.laporan')
                    ? 'bg-red-50 text-red-600 border-l-4 border-red-600 font-semibold'
                    : 'text-gray-500 hover:bg-red-50 hover:text-red-600' }}">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
                <span>Laporan</span>

            </a>

        </nav>

        <!-- Footer Sidebar -->
        <div class="border-t p-5">

            <div class="flex items-center gap-3">

                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold text-xl">

                    {{ strtoupper(substr(Auth::user()->nama,0,1)) }}

                </div>

                <div>

                    <h3 class="font-semibold text-gray-800">

                        {{ Auth::user()->nama }}

                    </h3>

                    <p class="text-xs text-gray-500">

                        {{ Auth::user()->role->nama_role ?? 'Administrator' }}

                    </p>

                </div>

            </div>

            <form method="POST"
                action="{{ route('logout') }}"
                class="mt-5">

                @csrf

                <button
                    class="w-full rounded-xl bg-red-600 py-3 text-white hover:bg-red-700 transition">

                    Logout

                </button>

            </form>

        </div>

    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col">

        <!-- Navbar -->
        <header class="bg-white shadow-sm px-8 py-5 flex justify-between items-center">

            <div>

                <h2 class="text-2xl font-bold text-gray-800">

                    @yield('title')

                </h2>

                <p class="text-sm text-gray-500">

                    Sistem Manajemen Koperasi Desa

                </p>

            </div>

            <div class="flex items-center gap-4">

                <span class="text-gray-500">

                    {{ now()->format('d M Y') }}

                </span>

                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center font-bold text-red-600">

                    {{ strtoupper(substr(Auth::user()->nama,0,1)) }}

                </div>

            </div>

        </header>

        <!-- Content -->
        <main class="flex-1 p-8">

            @yield('content')

        </main>

    </div>

</div>

</body>

</html>