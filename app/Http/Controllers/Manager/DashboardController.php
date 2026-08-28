<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return $this->noCache(view('dashboard'));
        }

        if ($user->id_role == 1) { // Admin
            $totalKopdes = \App\Models\Kopdes::count();
            $totalManager = \App\Models\User::where('id_role', 2)->count();
            $totalTransaksi = \App\Models\Transaction::count();
            $totalPembayaran = \App\Models\Payment::where('status_pembayaran', 'diverifikasi')->sum('jumlah_bayar');
            $latestTransactions = \App\Models\Transaction::with(['kopdes', 'user'])->latest()->take(5)->get();

            return $this->noCache(view('admin.dashboard', compact('totalKopdes', 'totalManager', 'totalTransaksi', 'totalPembayaran', 'latestTransactions')));
        }

        if ($user->id_role == 2) { // Manager
            $id_kopdes = $user->id_kopdes;

            $products = $id_kopdes ? Product::where('id_kopdes', $id_kopdes)->get() : collect();
            $categories = $id_kopdes ? Category::where('id_kopdes', $id_kopdes)->get() : collect();
            $transactions = $id_kopdes ? \App\Models\Transaction::where('id_kopdes', $id_kopdes)->get() : collect();
            $totalOmzet = $id_kopdes ? \App\Models\Transaction::where('id_kopdes', $id_kopdes)->where('status_transaksi', 'selesai')->sum('total_harga') : 0;
            $reviews = $id_kopdes ? \App\Models\Review::whereHas('product', fn($q) => $q->where('id_kopdes', $id_kopdes))->with(['user', 'product'])->get() : collect();

            return $this->noCache(view('dashboard', compact(
                'products', 
                'categories', 
                'transactions', 
                'totalOmzet', 
                'reviews', 
                'id_kopdes'
            )));
        }

        // Member
        return $this->noCache(view('dashboard'));
    }

    private function noCache($view)
    {
        return response($view)->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);
    }
}