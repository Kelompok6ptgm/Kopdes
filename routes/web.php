<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/products', function (\Illuminate\Http\Request $request) {
        $user = Auth::user();
        $kopdesId = $request->get('kopdes_id');
        if (!$kopdesId && $user && $user->kode_pos) {
            $recommended = \App\Models\Kopdes::where('status', 'aktif')->where('kode_pos', $user->kode_pos)->first();
            if (!$recommended) {
                $prefix = substr($user->kode_pos, 0, 2);
                $recommended = \App\Models\Kopdes::where('status', 'aktif')->where('kode_pos', 'LIKE', $prefix . '%')->first();
            }
            $kopdesId = $recommended ? $recommended->id_kopdes : null;
        }

        $allActiveKopdes = \App\Models\Kopdes::where('status', 'aktif')->get();
        $selectedKopdes = $kopdesId ? \App\Models\Kopdes::find($kopdesId) : $allActiveKopdes->first();

        $categories = $selectedKopdes ? $selectedKopdes->categories : collect();
        $categoryId = $request->get('category_id');
        $search = $request->get('search');

        $productsQuery = $selectedKopdes ? $selectedKopdes->products() : \App\Models\Product::query();

        if ($categoryId) {
            $productsQuery->where('id_category', $categoryId);
        }
        if ($search) {
            $productsQuery->where('nama_produk', 'LIKE', '%' . $search . '%');
        }

        $products = $productsQuery->with('category')->get();
        $myCarts = \App\Models\Cart::where('id_user', $user->id_user)->get();

        return view('products.index', compact('allActiveKopdes', 'selectedKopdes', 'categories', 'products', 'myCarts', 'search', 'categoryId'));
    })->name('products.index');
});

Route::get('/', function () {
    return redirect()->route('login');
});
