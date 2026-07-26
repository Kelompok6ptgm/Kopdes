<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KopdesController;
use App\Http\Controllers\ManagerController;
use App\Models\Kopdes;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function () {
        $totalKopdes = Kopdes::count();
        $totalManager = User::where('id_role', 2)->count();
        
        $totalTransaksi = 0;
        $totalPembayaran = 0;
        $latestTransactions = collect();

        return view('admin.dashboard', compact('totalKopdes', 'totalManager', 'totalTransaksi', 'totalPembayaran', 'latestTransactions'));
    })->name('dashboard');

    // KopDes CRUD resource
    Route::resource('admin/kopdes', KopdesController::class)->names([
        'index' => 'admin.kopdes',
    ]);

    // Manager CRUD resource
    Route::resource('admin/manager', ManagerController::class)->names([
        'index' => 'admin.manager',
    ]);
    Route::post('admin/manager/{id}/reset-password', [ManagerController::class, 'resetPassword'])->name('manager.reset-password');

    Route::view('/admin/transaksi', 'admin.transaksi')->name('admin.transaksi');
    Route::view('/admin/pembayaran', 'admin.pembayaran')->name('admin.pembayaran');
    Route::view('/admin/laporan', 'admin.laporan')->name('admin.laporan');
});

Route::get('/', function () {
    return redirect()->route('login');
});
