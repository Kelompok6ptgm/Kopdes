<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Gunakan DB facade atau Model Transaction jika ada

class TransactionController extends Controller
{
    // Fungsi untuk mengubah status pesanan (Diproses, Dikirim, Selesai) & Verifikasi Pembayaran
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diproses,Dikirim,Selesai,Ditolak',
            'status_pembayaran' => 'nullable|string'
        ]);

        // Cari transaksi (menyesuaikan nama tabel/kolom teman lo, misal tabel 'transactions')
        // Asumsi tabel transaksi memiliki kolom id, id_kopdes, status, dll.
        DB::table('transactions')
            ->where('id', $id)
            ->update([
                'status' => $request->status,
                'updated_at' => now(),
            ]);

        return redirect()->route('dashboard')->with('success', 'Status pesanan berhasil diperbarui!');
    }
}