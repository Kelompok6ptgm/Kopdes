<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KopdesController;
use App\Http\Controllers\ManagerController;
use App\Models\Kopdes;
use App\Models\User;
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
    Route::post('/forgot-password', [AuthController::class, 'forgotPasswordLookup'])->name('forgot-password');
});

// Guest landing page - can browse products and use session cart
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('dashboard');
});

Route::get('/products', function () {
    return redirect()->to('/#user-products');
})->name('products.index');

// Guest & Auth Cart operations
Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [\App\Http\Controllers\CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [\App\Http\Controllers\CartController::class, 'removeFromCart'])->name('cart.remove');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->id_role == 1) { // Admin
            $totalKopdes = Kopdes::count();
            $totalManager = User::where('id_role', 2)->count();
            
            $totalTransaksi = \App\Models\Transaction::count();
            $totalPembayaran = \App\Models\Payment::where('status_pembayaran', 'diverifikasi')->sum('jumlah_bayar');
            $latestTransactions = \App\Models\Transaction::with(['kopdes', 'user'])->latest()->take(5)->get();

            return view('admin.dashboard', compact('totalKopdes', 'totalManager', 'totalTransaksi', 'totalPembayaran', 'latestTransactions'));
        }
        
        // Manager or Member
        return view('dashboard');
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
    
    // Member & Manager actions
    Route::post('/manager/members/{id}/reset-password', [AuthController::class, 'resetMemberPassword'])->name('manager.reset-password');
    Route::post('/checkout', [\App\Http\Controllers\TransactionController::class, 'checkout'])->name('checkout');
    Route::post('/transaction/{id}/pay', [\App\Http\Controllers\TransactionController::class, 'uploadPayment'])->name('transaction.pay');
    Route::post('/transaction/{id}/cancel', [\App\Http\Controllers\TransactionController::class, 'cancelTransaction'])->name('transaction.cancel');
    Route::post('/manager/payments/{id}/verify', [\App\Http\Controllers\TransactionController::class, 'verifyPayment'])->name('manager.payments.verify');
    Route::post('/manager/transactions/{id}/status', [\App\Http\Controllers\TransactionController::class, 'updateStatus'])->name('manager.transactions.status');
    Route::post('/review', [\App\Http\Controllers\TransactionController::class, 'storeReview'])->name('review.store');
    Route::post('/review/{id}/reply', [\App\Http\Controllers\TransactionController::class, 'replyReview'])->name('review.reply');
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
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