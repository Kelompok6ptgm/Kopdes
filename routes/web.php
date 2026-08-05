<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\ProductController;
use App\Http\Controllers\Manager\CategoryController;
use App\Http\Controllers\Manager\DashboardController;
use App\Http\Controllers\Manager\TransactionController;


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/', function () {
    return redirect()->route('login');
});

Route::prefix('manager')->group(function () {
    // Tampil & Tambah Produk
    Route::get('/products', [ProductController::class, 'index'])->name('manager.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('manager.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('manager.products.store');

    // Edit, Update, & Hapus Produk
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('manager.products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('manager.products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('manager.products.destroy');
});

Route::get('/categories', [CategoryController::class, 'index'])->name('manager.categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('manager.categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('manager.categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('manager.categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('manager.categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('manager.categories.destroy');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::put('/manager/transactions/{id}/status', [TransactionController::class, 'updateStatus'])->name('manager.transactions.updateStatus');