<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Kopdes;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    /**
     * Halaman Utama: Menampilkan Kartu/Card KopDes
     */
    public function index()
    {
        $user = Auth::user();

        $kopdesQuery = Kopdes::query();

        if ($user && $user->id_role == 2) {
            $kopdesQuery->where('id_kopdes', $user->id_kopdes);
        }

        $kopdesList = $kopdesQuery->withCount('transactions')->get();

        return view('admin.transaksi.index', compact('kopdesList'));
    }

    /**
     * Halaman Detail: Menampilkan Laporan Keuangan & Transaksi Khusus 1 KopDes
     */
    public function show($id)
    {
        $user = Auth::user();

        if ($user && $user->id_role == 2 && $user->id_kopdes != $id) {
            abort(403, 'Unauthorized.');
        }

        $kopdes = Kopdes::with([
            'transactions' => function ($q) {
                $q->with(['user', 'details.product'])->latest();
            }
        ])->findOrFail($id);

        // Perhitungan Keuangan KopDes
        $kopdes->total_pemasukan = $kopdes->transactions
            ->whereIn('status_transaksi', ['diproses', 'dikirim', 'selesai'])
            ->sum('total_harga');

        $kopdes->total_pengeluaran = $kopdes->transactions
            ->where('status_transaksi', 'dibatalkan')
            ->sum('total_harga');

        return view('admin.transaksi.show', compact('kopdes'));
    }


    /**
     * Display transaction reports per KopDes per month.
     */
    public function reports(Request $request)
    {
        $user = Auth::user();

        if (!in_array($user->id_role, [1, 2])) {
            abort(403, 'Unauthorized.');
        }

        $bulan = $request->input('bulan', 'all');
        $tahun = $request->input('tahun', date('Y'));

        $kopdesQuery = Kopdes::query();

        if ($user->id_role == 2) {
            $kopdesQuery->where('id_kopdes', $user->id_kopdes);
        }

        $laporan = $kopdesQuery->with(['transactions' => function ($q) use ($bulan, $tahun) {
            if ($bulan && $bulan !== 'all') {
                $q->whereMonth('created_at', $bulan);
            }
            if ($tahun && $tahun !== 'all') {
                $q->whereYear('created_at', $tahun);
            }
        }])->get()->map(function ($kopdes) {
            $transaksiBerhasil = $kopdes->transactions->whereIn('status_transaksi', ['diproses', 'dikirim', 'selesai']);
            
            $kopdes->total_transaksi = $kopdes->transactions->count();
            $kopdes->transaksi_berhasil = $transaksiBerhasil->count();
            $kopdes->total_pemasukan = $transaksiBerhasil->sum('total_harga');
            
            return $kopdes;
        });

        return view('admin.laporan.index', compact('laporan', 'bulan', 'tahun'));
    }

    /**
     * Handle cart checkout and create transaction.
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'alamat_pengiriman'   => ['required', 'string', 'max:500'],
            'catatan'             => ['nullable', 'string', 'max:500'],
            'selected_products'   => ['required', 'array', 'min:1'],
            'selected_products.*' => ['integer', 'exists:product,id_product'],
        ]);

        $user = Auth::user();
        $selectedProductIds = $request->selected_products;

        $cartItems = Cart::where('id_user', $user->id_user)
            ->whereIn('id_product', $selectedProductIds)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->withErrors([
                'error' => 'Tidak ada barang yang dipilih atau barang tidak ditemukan di keranjang.'
            ]);
        }

        $kopdesIds = $cartItems->pluck('product.id_kopdes')->unique();

        if ($kopdesIds->count() > 1) {
            return back()->withErrors([
                'error' => 'Barang yang dipilih berasal dari beberapa koperasi berbeda. Pilih barang dari satu koperasi saja.'
            ]);
        }

        $idKopdes = $kopdesIds->first();

        $kopdes = Kopdes::find($idKopdes);

        if (!$kopdes || $kopdes->status !== 'aktif') {
            return back()->withErrors([
                'error' => 'Koperasi Desa asal barang ini sedang tidak aktif.'
            ]);
        }

        try {
            $transaction = DB::transaction(function () use (
                $user,
                $cartItems,
                $selectedProductIds,
                $idKopdes,
                $request
            ) {
                $totalHarga = 0;

                foreach ($cartItems as $item) {
                    $product = Product::lockForUpdate()->find($item->id_product);

                    if ($item->quantity > $product->stok) {
                        throw new \Exception(
                            'Stok produk "' . $product->nama_produk .
                                '" tidak mencukupi. Tersedia: ' . $product->stok
                        );
                    }

                    $totalHarga += $product->harga * $item->quantity;
                }

                $trx = Transaction::create([
                    'id_user'           => $user->id_user,
                    'id_kopdes'         => $idKopdes,
                    'kode_transaksi'    => 'TRX-' . time() . '-' . rand(1000, 9999),
                    'total_harga'       => $totalHarga,
                    'status_transaksi'  => 'menunggu_pembayaran',
                    'alamat_pengiriman' => $request->alamat_pengiriman,
                    'catatan'           => $request->catatan,
                ]);

                foreach ($cartItems as $item) {
                    $product = Product::find($item->id_product);

                    TransactionDetail::create([
                        'id_transaction' => $trx->id_transaction,
                        'id_product'     => $item->id_product,
                        'quantity'       => $item->quantity,
                        'harga_beli'     => $product->harga,
                    ]);

                    $product->decrement('stok', $item->quantity);
                }

                Cart::where('id_user', $user->id_user)
                    ->whereIn('id_product', $selectedProductIds)
                    ->delete();

                return $trx;
            });

            return redirect()
                ->to(route('dashboard') . '#user-history')
                ->with(
                    'success',
                    'Pesanan "' . $transaction->kode_transaksi . '" berhasil dibuat!'
                );
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Upload payment proof receipt for a pending transaction.
     */
    public function uploadPayment(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran'  => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'jumlah_bayar'      => ['required', 'numeric', 'min:1'],
            'metode_pembayaran' => ['required', 'string', 'max:50'],
        ]);

        $user = Auth::user();
        $trx = Transaction::findOrFail($id);

        if ($trx->id_user != $user->id_user) {
            abort(403, 'Unauthorized.');
        }

        if ($trx->status_transaksi != 'menunggu_pembayaran') {
            return back()->withErrors([
                'error' => 'Status transaksi tidak mendukung pembayaran.'
            ]);
        }

        $path = $request->file('bukti_pembayaran')->store('payments', 'public');

        DB::transaction(function () use ($trx, $request, $path) {
            Payment::updateOrCreate(
                ['id_transaction' => $trx->id_transaction],
                [
                    'jumlah_bayar'      => $request->jumlah_bayar,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'bukti_pembayaran'  => $path,
                    'status_pembayaran' => 'menunggu_verifikasi',
                ]
            );

            $trx->update([
                'status_transaksi' => 'menunggu_verifikasi'
            ]);
        });

        return redirect()
            ->to(route('dashboard') . '#user-history')
            ->with('success', 'Bukti pembayaran berhasil diunggah!');
    }

    /**
     * Menampilkan bukti pembayaran.
     */
    public function showPaymentProof($path)
    {
        $filename = basename($path);

        if ($filename !== $path) {
            abort(404);
        }

        $filePath = 'payments/' . $filename;

        $payment = Payment::with('transaction')
            ->where('bukti_pembayaran', $filePath)
            ->firstOrFail();

        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        // Admin
        if ($user->id_role == 1) {
            // Boleh melihat semua bukti pembayaran
        }

        // Manager hanya boleh melihat bukti dari KopDes miliknya
        elseif ($user->id_role == 2) {
            if (
                !$payment->transaction ||
                $payment->transaction->id_kopdes != $user->id_kopdes
            ) {
                abort(403, 'Unauthorized.');
            }
        }

        // User hanya boleh melihat bukti pembayaran miliknya sendiri
        elseif ($user->id_role == 3) {
            if (
                !$payment->transaction ||
                $payment->transaction->id_user != $user->id_user
            ) {
                abort(403, 'Unauthorized.');
            }
        } else {
            abort(403, 'Unauthorized.');
        }

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'Bukti pembayaran tidak ditemukan.');
        }

        return response()->file(Storage::disk('public')->path($filePath));
    }

    /**
     * Cancel transaction and restore stock.
     */
    public function cancelTransaction($id)
    {
        $user = Auth::user();
        $trx = Transaction::findOrFail($id);

        if ($user->id_role == 3 && $trx->id_user != $user->id_user) {
            abort(403, 'Unauthorized.');
        }

        if ($user->id_role == 2 && $trx->id_kopdes != $user->id_kopdes) {
            abort(403, 'Unauthorized.');
        }

        if (
            $trx->status_transaksi != 'menunggu_pembayaran' &&
            $trx->status_transaksi != 'menunggu_verifikasi'
        ) {
            return back()->withErrors([
                'error' => 'Pesanan tidak dapat dibatalkan pada status ini.'
            ]);
        }

        DB::transaction(function () use ($trx) {
            $details = TransactionDetail::where(
                'id_transaction',
                $trx->id_transaction
            )->get();

            foreach ($details as $detail) {
                Product::where(
                    'id_product',
                    $detail->id_product
                )->increment('stok', $detail->quantity);
            }

            $trx->update([
                'status_transaksi' => 'dibatalkan'
            ]);

            $payment = Payment::where(
                'id_transaction',
                $trx->id_transaction
            )->first();

            if ($payment) {
                $payment->update([
                    'status_pembayaran' => 'ditolak'
                ]);
            }
        });

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Verify payment receipt.
     */
    public function verifyPayment(Request $request, $id)
    {
        $request->validate([
            'action' => ['required', 'in:approve,reject'],
        ]);

        $manager = Auth::user();

        if ($manager->id_role != 2) {
            abort(403, 'Unauthorized.');
        }

        $payment = Payment::findOrFail($id);
        $trx = $payment->transaction;

        if ($trx->id_kopdes != $manager->id_kopdes) {
            abort(403, 'Unauthorized.');
        }

        if ($request->action === 'approve') {
            DB::transaction(function () use ($payment, $trx, $manager) {
                $payment->update([
                    'status_pembayaran'  => 'diverifikasi',
                    'diverifikasi_oleh'  => $manager->id_user,
                    'tanggal_verifikasi' => now(),
                ]);

                $trx->update([
                    'status_transaksi' => 'diproses'
                ]);
            });

            return back()->with('success', 'Pembayaran berhasil diverifikasi!');
        } else {
            DB::transaction(function () use ($payment, $trx) {
                $payment->update([
                    'status_pembayaran' => 'ditolak',
                ]);

                $trx->update([
                    'status_transaksi' => 'menunggu_pembayaran'
                ]);
            });

            return back()->with('success', 'Pembayaran ditolak.');
        }
    }

    /**
     * Update client transaction status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:diproses,dikirim,selesai,dibatalkan'],
        ]);

        $manager = Auth::user();

        if ($manager->id_role != 2) {
            abort(403, 'Unauthorized.');
        }

        $trx = Transaction::findOrFail($id);

        if ($trx->id_kopdes != $manager->id_kopdes) {
            abort(403, 'Unauthorized.');
        }

        $oldStatus = $trx->status_transaksi;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return back()->with('success', 'Status transaksi tidak berubah.');
        }

        try {
            DB::transaction(function () use ($trx, $oldStatus, $newStatus) {
                $trx->status_transaksi = $newStatus;
                $trx->save();

                if (
                    $newStatus === 'dibatalkan' &&
                    $oldStatus !== 'dibatalkan'
                ) {
                    foreach ($trx->details as $detail) {
                        $product = Product::lockForUpdate()
                            ->find($detail->id_product);

                        if ($product) {
                            $product->increment(
                                'stok',
                                $detail->quantity
                            );
                        }
                    }
                } elseif (
                    $oldStatus === 'dibatalkan' &&
                    $newStatus !== 'dibatalkan'
                ) {
                    foreach ($trx->details as $detail) {
                        $product = Product::lockForUpdate()
                            ->find($detail->id_product);

                        if ($product) {
                            if ($product->stok < $detail->quantity) {
                                throw new \Exception(
                                    'Stok produk ' .
                                        $product->nama_produk .
                                        ' tidak mencukupi.'
                                );
                            }

                            $product->decrement(
                                'stok',
                                $detail->quantity
                            );
                        }
                    }
                }
            });

            return back()->with(
                'success',
                'Status transaksi berhasil diperbarui.'
            );
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * User submit ulasan produk (dari riwayat belanja)
     */
    public function storeReview(Request $request)
    {
        $request->validate([
            'id_transaction_detail' => [
                'required',
                'exists:transaction_detail,id_transaction_detail'
            ],
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:5'
            ],
            'komentar' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ]);

        $user = Auth::user();

        // Pastikan detail transaksi ini milik user yang login
        $detail = TransactionDetail::whereHas(
            'transaction',
            function ($q) use ($user) {
                $q->where('id_user', $user->id_user);
            }
        )->findOrFail($request->id_transaction_detail);

        // Cek apakah sudah pernah diulas
        $existing = Review::where(
            'id_transaction_detail',
            $detail->id_transaction_detail
        )->first();

        if ($existing) {
            return redirect()
                ->to(route('dashboard') . '#user-history')
                ->with('error', 'Produk ini sudah pernah Anda ulas.');
        }

        Review::create([
            'id_user'               => $user->id_user,
            'id_product'            => $detail->id_product,
            'id_transaction_detail' => $detail->id_transaction_detail,
            'rating'                => $request->rating,
            'komentar'              => $request->komentar,
            'reviewed_at'           => now(),
        ]);

        return redirect()
            ->to(route('dashboard') . '#user-history')
            ->with('success', 'Terima kasih! Ulasan Anda sudah terkirim.');
    }

    /**
     * Manager balas ulasan produk
     */
    public function replyReview(Request $request, $id)
    {
        $request->validate([
            'tanggapan_manager' => [
                'required',
                'string',
                'max:1000'
            ],
        ]);

        $manager = Auth::user();

        if (!$manager || $manager->id_role != 2 || !$manager->id_kopdes) {
            abort(403, 'Unauthorized.');
        }

        // Pastikan review ini milik produk KopDes-nya manager
        $review = Review::whereHas('product', function ($q) use ($manager) {
            $q->where('id_kopdes', $manager->id_kopdes);
        })->findOrFail($id);

        $review->tanggapan_manager = $request->tanggapan_manager;
        $review->save();

        return redirect()
            ->to(route('dashboard') . '#mgr-reviews')
            ->with('success', 'Tanggapan berhasil dikirim!');
    }
}
