<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KopdesController;
use App\Http\Controllers\ManagerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Manager\ProductController;
use App\Http\Controllers\Manager\CategoryController;
use App\Http\Controllers\Manager\DashboardController;
use App\Http\Controllers\Manager\TransactionController as ManagerTransactionController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CartController;

// ─── Guest-only ───────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPasswordLookup'])->name('forgot-password');
});

// ─── Public ────────────────────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('dashboard');
});

Route::get('/products', function () {
    return redirect()->to('/#user-products');
})->name('products.index');

// Cart: accessible by guests (session) and members (DB)
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');

// ─── Authenticated ─────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Admin: KopDes & Manager CRUD ──────────────────────────────────────────
    Route::resource('admin/kopdes', KopdesController::class)->names(['index' => 'admin.kopdes']);
    Route::resource('admin/manager', ManagerController::class)->names(['index' => 'admin.manager']);
    Route::post('admin/manager/{id}/reset-password', [ManagerController::class, 'resetPassword'])->name('admin.manager.reset-password');

    // ── Profile ───────────────────────────────────────────────────────────────
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/manager/members/{id}/reset-password', [AuthController::class, 'resetMemberPassword'])->name('manager.member.reset-password');

    // ── Transaksi (Member & Manager) ──────────────────────────────────────────
    Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout');
    Route::post('/transaction/{id}/pay', [TransactionController::class, 'uploadPayment'])->name('transaction.pay');
    Route::post('/transaction/{id}/cancel', [TransactionController::class, 'cancelTransaction'])->name('transaction.cancel');

    // ── Manager: verifikasi bayar & status transaksi ──────────────────────────
    Route::post('/manager/payments/{id}/verify', [TransactionController::class, 'verifyPayment'])->name('manager.payments.verify');
    Route::post('/manager/transactions/{id}/status', [ManagerTransactionController::class, 'updateStatus'])->name('manager.transactions.updateStatus');

    // ── Review ────────────────────────────────────────────────────────────────
    Route::post('/review', [TransactionController::class, 'storeReview'])->name('review.store');
    Route::post('/review/{id}/reply', [TransactionController::class, 'replyReview'])->name('review.reply');

    // ── Manager: Produk & Kategori ────────────────────────────────────────────
    Route::prefix('manager')->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('manager.products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('manager.products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('manager.products.store');
        Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('manager.products.edit');
        Route::put('/products/{id}', [ProductController::class, 'update'])->name('manager.products.update');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('manager.products.destroy');

        Route::get('/categories', [CategoryController::class, 'index'])->name('manager.categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('manager.categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('manager.categories.store');
        Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('manager.categories.edit');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('manager.categories.update');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('manager.categories.destroy');
    });
});