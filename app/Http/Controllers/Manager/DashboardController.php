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
        $id_kopdes = Auth::user()->id_kopdes ?? 1;

        // Ambil data produk, kategori, dan transaksi milik KopDes ini
        $products = Product::where('id_kopdes', $id_kopdes)->get();
        $categories = Category::where('id_kopdes', $id_kopdes)->get();
        
        // Mengambil data transaksi (menyesuaikan tabel 'transactions' di database)
        // Jika belum ada tabel transactions, variabel akan kosong jadi tidak error
        $transactions = DB::table('transactions')
            ->where('id_kopdes', $id_kopdes)
            ->get();

        return view('dashboard', compact('products', 'categories', 'transactions', 'id_kopdes'));
    }
}