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

        $products = Product::where('id_kopdes', $id_kopdes)->get();
        $categories = Category::where('id_kopdes', $id_kopdes)->get();
        
        $transactions = DB::table('transactions')->get();

        $salesReport = collect();
        $totalOmzet = 0;
        $reviews = collect();

        return view('dashboard', compact(
            'products', 
            'categories', 
            'transactions', 
            'salesReport', 
            'totalOmzet', 
            'reviews', 
            'id_kopdes'
        ));
    }
}