<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use App\Models\Kopdes;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return $this->noCache(view('dashboard'));
        }

        if ($user->id_role == 1) { // Admin
            $totalKopdes      = Kopdes::count();
            $totalManager     = User::where('id_role', 2)->count();
            $totalTransaksi   = Transaction::count();
            $totalPembayaran  = Payment::where('status_pembayaran', 'diverifikasi')->sum('jumlah_bayar');
            $latestTransactions = Transaction::with(['kopdes', 'user'])->latest()->take(5)->get();

            return $this->noCache(view('admin.dashboard', compact(
                'totalKopdes', 'totalManager', 'totalTransaksi', 'totalPembayaran', 'latestTransactions'
            )));
        }

        if ($user->id_role == 2) { // Manager
            $id_kopdes  = $user->id_kopdes;
            $kopdes     = $id_kopdes ? Kopdes::find($id_kopdes) : null;
            $hasKopdes  = (bool) $kopdes;

            $myProducts     = $id_kopdes ? Product::where('id_kopdes', $id_kopdes)->with('category')->get() : collect();
            $myCategories   = $id_kopdes ? Category::where('id_kopdes', $id_kopdes)->get() : collect();
            $myTransactions = $id_kopdes
                ? Transaction::where('id_kopdes', $id_kopdes)->with(['user', 'details.product', 'payment'])->latest()->get()
                : collect();
            $myPayments     = $id_kopdes
                ? Payment::whereHas('transaction', fn($q) => $q->where('id_kopdes', $id_kopdes))
                    ->with(['transaction.user'])->latest()->get()
                : collect();
            $myReviews      = $id_kopdes
                ? Review::whereHas('product', fn($q) => $q->where('id_kopdes', $id_kopdes))
                    ->with(['user', 'product'])->latest()->get()
                : collect();
            $myMembers      = $id_kopdes
                ? User::where('id_role', 3)->where('id_kopdes', $id_kopdes)->latest()->get()
                : collect();

            // Stats for overview cards
            $totalProducts    = $myProducts->count();
            $kopdesEarnings   = $id_kopdes
                ? Transaction::where('id_kopdes', $id_kopdes)->where('status_transaksi', 'selesai')->sum('total_harga')
                : 0;
            $processingOrders = $id_kopdes
                ? Transaction::where('id_kopdes', $id_kopdes)->whereIn('status_transaksi', ['diproses', 'dikirim'])->count()
                : 0;

            return $this->noCache(view('dashboard', compact(
                'kopdes', 'hasKopdes',
                'myProducts', 'myCategories', 'myTransactions', 'myPayments', 'myReviews', 'myMembers',
                'totalProducts', 'kopdesEarnings', 'processingOrders'
            )));
        }

        // Member (id_role == 3)
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