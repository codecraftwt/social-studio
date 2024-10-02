<?php

namespace App\Http\Controllers\transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = TransactionDetail::all(); // Fetch all transactions
        return view('transactions.index', compact('transactions'));
    }

    public function approve($id)
    {
        $transaction = TransactionDetail::findOrFail($id);
        $transaction->status = 1; // Assuming 1 means approved
        $transaction->save();

        return response()->json(['success' => true]);
    }

    public function reject($id)
    {
        $transaction = TransactionDetail::findOrFail($id);
        $transaction->status = 0; // Assuming 0 means rejected
        $transaction->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $transaction = TransactionDetail::findOrFail($id);
        $transaction->delete();

        return response()->json(['success' => true]);
    }

    public function bulkApprove(Request $request)
    {
        $transactionIds = $request->input('transactions', []);
        TransactionDetail::whereIn('id', $transactionIds)->update(['status' => 1]); // Assuming 1 means approved

        return redirect()->back()->with('success', 'Transactions approved successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $transactionIds = $request->input('transactions', []);
        TransactionDetail::destroy($transactionIds);

        return redirect()->back()->with('success', 'Transactions deleted successfully.');
    }
}
