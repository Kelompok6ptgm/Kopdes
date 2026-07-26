@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-8">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Selamat Datang, {{ Auth::user()->nama }} 👋
            </h1>

            <p class="text-gray-500 mt-1">
                Dashboard Administrator KopDes Merah Putih
            </p>
        </div>

        <div class="text-right">
            <p class="text-sm text-gray-500">
                {{ now()->format('d M Y') }}
            </p>

            <span class="inline-block mt-2 px-4 py-2 bg-red-100 text-red-600 rounded-full font-semibold">
                {{ strtoupper(Auth::user()->role->nama_role ?? 'ADMIN') }}
            </span>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        <div class="bg-white rounded-2xl shadow-md p-6">
            <p class="text-gray-500">Total KopDes</p>

            <h2 class="text-4xl font-bold mt-3 text-red-600">
                {{ $totalKopdes }}
            </h2>

            <p class="text-green-600 text-sm mt-2">
                ↑ Total terdaftar
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <p class="text-gray-500">Manager</p>

            <h2 class="text-4xl font-bold mt-3 text-blue-600">
                {{ $totalManager }}
            </h2>

            <p class="text-green-600 text-sm mt-2">
                ↑ Akun pengelola
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <p class="text-gray-500">Transaksi</p>

            <h2 class="text-4xl font-bold mt-3 text-orange-500">
                {{ number_format($totalTransaksi, 0, ',', '.') }}
            </h2>

            <p class="text-green-600 text-sm mt-2">
                ↑ Seluruh transaksi
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-md p-6">
            <p class="text-gray-500">Total Pembayaran</p>

            <h2 class="text-4xl font-bold mt-3 text-green-600">
                Rp{{ number_format($totalPembayaran, 0, ',', '.') }}
            </h2>

            <p class="text-green-600 text-sm mt-2">
                ↑ Status Sukses
            </p>
        </div>

    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-md">

        <div class="border-b px-6 py-4 flex justify-between items-center">

            <h2 class="font-bold text-lg">
                Transaksi Terbaru
            </h2>

            <a href="{{ route('admin.transaksi') }}"
                class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg transition inline-block text-sm">
                Lihat Semua
            </a>

        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="px-6 py-4 text-left">Invoice</th>

                        <th class="px-6 py-4 text-left">KopDes</th>

                        <th class="px-6 py-4 text-left">Manager</th>

                        <th class="px-6 py-4 text-left">Total</th>

                        <th class="px-6 py-4 text-left">Status</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($latestTransactions as $tx)
                    <tr class="border-b hover:bg-gray-50">

                        <td class="px-6 py-4 font-medium">{{ $tx->invoice }}</td>

                        <td class="px-6 py-4">{{ $tx->kopdes->nama_kopdes ?? '-' }}</td>

                        <td class="px-6 py-4">{{ $tx->user->nama ?? '-' }}</td>

                        <td class="px-6 py-4">Rp{{ number_format($tx->total, 0, ',', '.') }}</td>

                        <td class="px-6 py-4">
                            @if($tx->status === 'selesai')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                Selesai
                            </span>
                            @elseif($tx->status === 'pending')
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">
                                Pending
                            </span>
                            @else
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                {{ ucfirst($tx->status) }}
                            </span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Belum ada transaksi terbaru</td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection