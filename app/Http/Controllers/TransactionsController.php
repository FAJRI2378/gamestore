<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionsController extends Controller
{
    public function index()
    {
        $transactions = Transactions::where('user_id', Auth::id())->latest()->get();
        return view('transactions.index', compact('transactions'));
    }

    public function print($id)
    {
        $transactions = Transactions::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $pdf = Pdf::loadView('transactions.receipt', compact('transactions'));
        return $pdf->stream('receipt.pdf');
    }

   public function history()
{
    // Ambil semua transaksi tanpa filter user_id
    $transactions = Transactions::orderBy('created_at', 'desc')->get();

    // Kirim data transaksi ke view
    return view('transactions.history', compact('transactions'));


}

public function updateStatus(Request $request, $id)
{
    $transaction = Transactions::findOrFail($id);

    // Pastikan hanya admin atau user yang sesuai yang bisa mengubah status
    if ($transaction->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
        return redirect()->route('transactions.history')->with('error', 'You do not have permission to change this transaction status.');
    }

    // Validasi status baru
    $newStatus = $request->input('status');
    if (!in_array($newStatus, ['success', 'cancelled'])) {
        return redirect()->route('transactions.history')->with('error', 'Invalid status.');
    }

    // Update status transaksi
    $transaction->status = $newStatus;
    $transaction->save();

    // Set flash message dan redirect
    return redirect()->route('transactions.history')->with('status', 'Transaction status updated to ' . ucfirst($newStatus) . '.');
}



}
