<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Update transaction status (Manager only).
     * Status transitions: menunggu_pembayaran → menunggu_verifikasi → diproses → dikirim → selesai
     * Or: any cancellable state → dibatalkan
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
}