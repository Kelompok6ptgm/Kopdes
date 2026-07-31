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
