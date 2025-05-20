<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionsController extends Controller
{
    public function index()
{
    $transactions = Transaction::where('user_id', Auth::id())->latest()->get();
    return view('transactions.index', compact('transactions'));
}

    public function print($id)
    {
        $transactions = Transaction::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $pdf = Pdf::loadView('transactions.receipt', compact('transactions'));
        return $pdf->stream('receipt.pdf');
    }

        public function history()
        {
            $transactions = Transaction::orderBy('created_at', 'desc')->get();
            return view('transactions.history', compact('transactions'));
        }

        public function updateStatus(Request $request, $id)
        {
            $transaction = Transaction::findOrFail($id);

            if ($transaction->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
                return redirect()->route('transactions.history')->with('error', 'You do not have permission to change this transaction status.');
            }

            $newStatus = $request->input('status');
            if (!in_array($newStatus, ['success', 'cancelled'])) {
                return redirect()->route('transactions.history')->with('error', 'Invalid status.');
            }

            $transaction->status = $newStatus;
            $transaction->save();

            return redirect()->route('transactions.history')->with('status', 'Transaction status updated to ' . ucfirst($newStatus) . '.');
        }




}
