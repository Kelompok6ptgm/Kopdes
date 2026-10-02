<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions for Admin/Manager.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->id_role == 1) {
            // Admin: Tampilkan semua transaksi
            $transactions = Transaction::with(['user', 'kopdes', 'details.product'])->latest()->get();
        } elseif ($user->id_role == 2) {
            // Manager: Tampilkan transaksi khusus KopDes miliknya
            $transactions = Transaction::where('id_kopdes', $user->id_kopdes)
                ->with(['user', 'details.product'])
                ->latest()
                ->get();
        } else {
            abort(403, 'Unauthorized.');
        }

        return view('admin.transaksi.index', compact('transactions'));
    }

    /**
     * Display a listing of payments for Admin/Manager.
     */
    public function payments()
    {
        $user = Auth::user();

        if ($user->id_role == 1) {
            $payments = Payment::with(['transaction.user', 'transaction.kopdes'])->latest()->get();
        } elseif ($user->id_role == 2) {
            $payments = Payment::whereHas('transaction', function ($q) use ($user) {
                $q->where('id_kopdes', $user->id_kopdes);
            })->with(['transaction.user'])->latest()->get();
        } else {
            abort(403, 'Unauthorized.');
        }

        return view('admin.pembayaran.index', compact('payments'));
    }

    /**
     * Display transaction reports for Admin/Manager.
     */
    public function reports()
    {
        $user = Auth::user();

        if ($user->id_role == 1) {
            $transactions = Transaction::where('status_transaksi', 'selesai')
                ->with(['user', 'kopdes'])
                ->latest()
                ->get();
        } elseif ($user->id_role == 2) {
            $transactions = Transaction::where('id_kopdes', $user->id_kopdes)
                ->where('status_transaksi', 'selesai')
                ->with(['user'])
                ->latest()
                ->get();
        } else {
            abort(403, 'Unauthorized.');
        }

        return view('admin.laporan.index', compact('transactions'));
    }

    /**
     * Update transaction status (Manager only).
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

        $trx = Transaction::with('details')->findOrFail($id);

        if ($trx->id_kopdes != $manager->id_kopdes) {
            abort(403, 'Unauthorized.');
        }

        $newStatus = $request->status;
        $oldStatus = $trx->status_transaksi;

        if ($oldStatus === $newStatus) {
            return back()->with('success', 'Status tidak berubah.');
        }

        try {
            DB::transaction(function () use ($trx, $oldStatus, $newStatus) {
                $trx->status_transaksi = $newStatus;
                $trx->save();

                // Restore stock when cancelling
                if ($newStatus === 'dibatalkan' && $oldStatus !== 'dibatalkan') {
                    foreach ($trx->details as $detail) {
                        \App\Models\Product::lockForUpdate()->find($detail->id_product)?->increment('stok', $detail->quantity);
                    }
                    // Also update payment status if any
                    Payment::where('id_transaction', $trx->id_transaction)
                        ->update(['status_pembayaran' => 'ditolak']);
                }
            });

            return back()->with('success', 'Status transaksi "' . $trx->kode_transaksi . '" diperbarui menjadi: ' . strtoupper($newStatus));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Verify payment (Manager only).
     */
    public function verifyPayment(Request $request, $id)
    {
        $manager = Auth::user();
        if ($manager->id_role != 2) {
            abort(403, 'Unauthorized.');
        }

        $payment = Payment::with('transaction')->findOrFail($id);

        if ($payment->transaction->id_kopdes != $manager->id_kopdes) {
            abort(403, 'Unauthorized.');
        }

        $payment->status_pembayaran = 'diterima';
        $payment->save();

        // Update transaction status to diproses / waiting processing
        $payment->transaction->status_transaksi = 'diproses';
        $payment->transaction->save();

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * Reply to user review (Manager/Admin).
     */
    public function replyReview(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string|max:500',
        ]);

        $review = Review::findOrFail($id);
        
        $review->update([
            'reply' => $request->reply,
        ]);

        return redirect()->back()->with('success', 'Balasan ulasan berhasil dikirim.');
    }
}