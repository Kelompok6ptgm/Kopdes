<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    /**
     * Handle cart checkout and create transaction (using DB transaction).
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'alamat_pengiriman' => ['required', 'string', 'max:500'],
            'catatan'           => ['nullable', 'string', 'max:500'],
            'selected_products' => ['required', 'array', 'min:1'],
            'selected_products.*' => ['integer', 'exists:product,id_product'],
        ]);

        $user = Auth::user();
        $selectedProductIds = $request->selected_products;

        // Get only the selected cart items
        $cartItems = Cart::where('id_user', $user->id_user)
            ->whereIn('id_product', $selectedProductIds)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->withErrors(['error' => 'Tidak ada barang yang dipilih atau barang tidak ditemukan di keranjang.']);
        }

        // Validate all cart items belong to the same KopDes (defense in depth after cart-level check)
        $kopdesIds = $cartItems->pluck('product.id_kopdes')->unique();
        if ($kopdesIds->count() > 1) {
            return back()->withErrors(['error' => 'Barang yang dipilih berasal dari beberapa koperasi berbeda. Pilih barang dari satu koperasi saja.']);
        }
        $idKopdes = $kopdesIds->first();

        $kopdes = \App\Models\Kopdes::find($idKopdes);
        if (!$kopdes || $kopdes->status !== 'aktif') {
            return back()->withErrors(['error' => 'Koperasi Desa asal barang ini sedang tidak aktif.']);
        }

        try {
            $transaction = DB::transaction(function () use ($user, $cartItems, $selectedProductIds, $idKopdes, $request) {
                $totalHarga = 0;

                // Validate stock in real-time before transaction
                foreach ($cartItems as $item) {
                    $product = Product::lockForUpdate()->find($item->id_product);
                    if ($item->quantity > $product->stok) {
                        throw new \Exception('Stok produk "' . $product->nama_produk . '" tidak mencukupi. Tersedia: ' . $product->stok);
                    }
                    $totalHarga += $product->harga * $item->quantity;
                }

                // Create Transaction record
                $trx = Transaction::create([
                    'id_user'           => $user->id_user,
                    'id_kopdes'         => $idKopdes,
                    'kode_transaksi'    => 'TRX-' . time() . '-' . rand(1000, 9999),
                    'total_harga'       => $totalHarga,
                    'status_transaksi'  => 'menunggu_pembayaran',
                    'alamat_pengiriman' => $request->alamat_pengiriman,
                    'catatan'           => $request->catatan,
                ]);

                // Create TransactionDetails & Deduct Stock
                foreach ($cartItems as $item) {
                    $product = Product::find($item->id_product);

                    TransactionDetail::create([
                        'id_transaction' => $trx->id_transaction,
                        'id_product'     => $item->id_product,
                        'quantity'       => $item->quantity,
                        'harga_beli'     => $product->harga,
                    ]);

                    // Deduct stock only for checked-out items
                    $product->decrement('stok', $item->quantity);
                }

                // Only clear checked-out items from cart (leave the rest)
                Cart::where('id_user', $user->id_user)
                    ->whereIn('id_product', $selectedProductIds)
                    ->delete();

                return $trx;
            });

            return redirect()->to(route('dashboard') . '#user-history')->with('success', 'Pesanan "' . $transaction->kode_transaksi . '" berhasil dibuat! Silakan lakukan pembayaran.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Upload payment proof receipt for a pending transaction.
     */
    public function uploadPayment(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'jumlah_bayar' => ['required', 'numeric', 'min:1'],
            'metode_pembayaran' => ['required', 'string', 'max:50'],
        ]);

        $user = Auth::user();
        $trx = Transaction::findOrFail($id);

        if ($trx->id_user != $user->id_user) {
            abort(403, 'Unauthorized.');
        }

        if ($trx->status_transaksi != 'menunggu_pembayaran') {
            return back()->withErrors(['error' => 'Status transaksi tidak mendukung pembayaran.']);
        }

        $path = $request->file('bukti_pembayaran')->store('payments', 'public');

        DB::transaction(function () use ($trx, $request, $path) {
            // Create or update payment record
            Payment::updateOrCreate(
                ['id_transaction' => $trx->id_transaction],
                [
                    'jumlah_bayar' => $request->jumlah_bayar,
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'bukti_pembayaran' => $path,
                    'status_pembayaran' => 'menunggu_verifikasi',
                ]
            );

            // Update transaction status
            $trx->update(['status_transaksi' => 'menunggu_verifikasi']);
        });

        return redirect()->to(route('dashboard') . '#user-history')->with('success', 'Bukti pembayaran berhasil diunggah! Menunggu verifikasi dari Manager KopDes.');
    }

    /**
     * Cancel transaction and restore stock.
     */
    public function cancelTransaction($id)
    {
        $user = Auth::user();
        $trx = Transaction::findOrFail($id);

        // Check permission: only the customer who made it or the KopDes manager can cancel
        if ($user->id_role == 3 && $trx->id_user != $user->id_user) {
            abort(403, 'Unauthorized.');
        }

        if ($user->id_role == 2 && $trx->id_kopdes != $user->id_kopdes) {
            abort(403, 'Unauthorized.');
        }

        if ($trx->status_transaksi != 'menunggu_pembayaran' && $trx->status_transaksi != 'menunggu_verifikasi') {
            return back()->withErrors(['error' => 'Pesanan tidak dapat dibatalkan pada status ini.']);
        }

        DB::transaction(function () use ($trx) {
            // Restore inventory stock for each item in the transaction
            $details = TransactionDetail::where('id_transaction', $trx->id_transaction)->get();
            foreach ($details as $detail) {
                Product::where('id_product', $detail->id_product)->increment('stok', $detail->quantity);
            }

            // Update status
            $trx->update(['status_transaksi' => 'dibatalkan']);

            // Reject payment if exists
            $payment = Payment::where('id_transaction', $trx->id_transaction)->first();
            if ($payment) {
                $payment->update(['status_pembayaran' => 'ditolak']);
            }
        });

        return back()->with('success', 'Pesanan "' . $trx->kode_transaksi . '" berhasil dibatalkan dan stok dikembalikan.');
    }

    /**
     * Verify payment receipt (Manager permission).
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
                    'status_pembayaran' => 'diverifikasi',
                    'diverifikasi_oleh' => $manager->id_user,
                    'tanggal_verifikasi' => now(),
                ]);

                $trx->update(['status_transaksi' => 'diproses']);
            });

            return back()->with('success', 'Pembayaran untuk transaksi "' . $trx->kode_transaksi . '" berhasil diverifikasi dan pesanan kini diproses!');
        } else {
            DB::transaction(function () use ($payment, $trx) {
                $payment->update([
                    'status_pembayaran' => 'ditolak',
                ]);

                // Reset transaction status back to pending payment so user can re-upload proof
                $trx->update(['status_transaksi' => 'menunggu_pembayaran']);
            });

            return back()->with('success', 'Pembayaran ditolak. Transaksi dikembalikan ke status Menunggu Pembayaran.');
        }
    }

    /**
     * Update client transaction status (for Managers).
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

                // If cancelled, restore product stock
                if ($newStatus === 'dibatalkan' && $oldStatus !== 'dibatalkan') {
                    foreach ($trx->details as $detail) {
                        $product = Product::lockForUpdate()->find($detail->id_product);
                        if ($product) {
                            $product->increment('stok', $detail->quantity);
                        }
                    }
                }
                // If moving away from cancelled, deduct product stock again
                elseif ($oldStatus === 'dibatalkan' && $newStatus !== 'dibatalkan') {
                    foreach ($trx->details as $detail) {
                        $product = Product::lockForUpdate()->find($detail->id_product);
                        if ($product) {
                            if ($product->stok < $detail->quantity) {
                                throw new \Exception('Stok produk ' . $product->nama_produk . ' tidak mencukupi.');
                            }
                            $product->decrement('stok', $detail->quantity);
                        }
                    }
                }
            });

            return back()->with('success', 'Status transaksi ' . $trx->kode_transaksi . ' berhasil diperbarui menjadi: ' . strtoupper($newStatus));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Store or update a product review (1 per user per product, editable within 30 days).
     */
    public function storeReview(Request $request)
    {
        $request->validate([
            'id_transaction_detail' => ['required', 'exists:transaction_detail,id_transaction_detail'],
            'rating'                => ['required', 'integer', 'min:1', 'max:5'],
            'komentar'              => ['nullable', 'string', 'max:1000'],
        ]);

        $user   = Auth::user();
        $detail = TransactionDetail::with('transaction')->findOrFail($request->id_transaction_detail);

        // Verify ownership: this order line must belong to a completed transaction of this user
        if ($detail->transaction->id_user !== $user->id_user
            || $detail->transaction->status_transaksi !== 'selesai') {
            return back()->withErrors(['error' => 'Anda hanya dapat mengulas produk dari transaksi yang sudah selesai.']);
        }

        // 1 review per user per product — check if existing review can still be edited
        $existing = \App\Models\Review::where('id_user', $user->id_user)
            ->where('id_product', $detail->id_product)
            ->first();

        if ($existing) {
            $daysSince = $existing->reviewed_at
                ? now()->diffInDays($existing->reviewed_at)
                : 0;

            if ($daysSince > 30) {
                return back()->withErrors(['error' => 'Batas waktu edit ulasan (30 hari) telah habis.']);
            }

            $existing->update([
                'rating'   => $request->rating,
                'komentar' => $request->komentar,
            ]);
        } else {
            \App\Models\Review::create([
                'id_user'               => $user->id_user,
                'id_product'            => $detail->id_product,
                'id_transaction_detail' => $detail->id_transaction_detail,
                'rating'                => $request->rating,
                'komentar'              => $request->komentar,
                'reviewed_at'           => now(),
            ]);
        }

        return back()->with('success', 'Ulasan Anda berhasil disimpan!');
    }

    /**
     * Manager reply to a review.
     */
    public function replyReview(Request $request, $id)
    {
        $request->validate([
            'tanggapan_manager' => ['required', 'string', 'max:1000'],
        ]);

        $manager = Auth::user();
        if ($manager->id_role != 2) {
            abort(403, 'Unauthorized.');
        }

        $review = \App\Models\Review::findOrFail($id);

        // Verify this review belongs to this manager's KopDes
        if ($review->product->id_kopdes !== $manager->id_kopdes) {
            abort(403, 'Unauthorized.');
        }

        $review->update(['tanggapan_manager' => $request->tanggapan_manager]);

        return back()->with('success', 'Tanggapan berhasil disimpan.');
    }
}
