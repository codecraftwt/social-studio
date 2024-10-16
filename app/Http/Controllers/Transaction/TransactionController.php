<?php

namespace App\Http\Controllers\transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use App\Notifications\TransactionApprovedNotification;
use App\Notifications\TransactionRejectedNotification;

class TransactionController extends Controller
{
    public function index()
    {
        TransactionDetail::where('is_read', 0)->update(['is_read' => 1]);
        $transactions = TransactionDetail::orderBy('created_at', 'desc')->get(); 
        return view('transactions.index', compact('transactions'));
    }

    public function usertransactions()
    {
        $transactions = TransactionDetail::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get(); 
        return view('transactions.user_index', compact('transactions'));
    }

    public function approve($id)
    {
        $transaction = TransactionDetail::findOrFail($id);
        $transaction->status = 1;

        switch ($transaction->subscription_type) {
            case 'free':
                $transaction->plan_expiry_date = Carbon::now()->addMonths(1);
                break;
            case 'three_months':
                $transaction->plan_expiry_date = Carbon::now()->addMonths(3);
                break;
            case 'six_months':
                $transaction->plan_expiry_date = Carbon::now()->addMonths(6);
                break;
            case 'one_year':
                $transaction->plan_expiry_date = Carbon::now()->addYear();
                break;
            default:
                $transaction->plan_expiry_date = null;
        }

        $transaction->save();
        $user = $transaction->user;
        if ($user) {
            $user->notify((new TransactionApprovedNotification($transaction))->delay(now()->addSeconds(5)));
        }
        return response()->json(['success' => true]);
    }

    public function reject($id)
    {
        $transaction = TransactionDetail::findOrFail($id);
        $transaction->status = 0;
        $transaction->save();
        $user = $transaction->user;

        if ($user) {
            $user->notify(new TransactionRejectedNotification($transaction));
        }
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

        // Fetch the transactions
        $transactions = TransactionDetail::whereIn('id', $transactionIds)->get();

        // Update the status
        TransactionDetail::whereIn('id', $transactionIds)->update(['status' => 1]); // Assuming 1 means approved

        foreach ($transactions as $transaction) {
            $user = $transaction->user; // Assuming the relationship exists
            if ($user) {
                $user->notify(new TransactionApprovedNotification($transaction));
            }
        }

        return redirect()->back()->with('success', 'Transactions approved successfully.');
    }

    public function bulkDeactivate(Request $request)
    {
        $transactionIds = $request->input('transactions', []);
        
        try {
            if (empty($transactionIds)) {
                return response()->json(['message' => 'No transactions selected for deactivation.'], 400);
            }

            TransactionDetail::whereIn('id', $transactionIds)->update(['status' => 0]);

            return response()->json(['message' => 'Transactions deactivated successfully.']);
        } catch (\Exception $e) {
            \Log::error('Error deactivating transactions: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while deactivating transactions.'], 500);
        }
    }


    public function bulkDelete(Request $request)
    {
        $transactionIds = $request->input('transactions', []);
        
        try {
            if (empty($transactionIds)) {
                return response()->json(['message' => 'No transactions selected for deletion.'], 400);
            }
    
            $deletedCount = TransactionDetail::destroy($transactionIds);
    
            if ($deletedCount > 0) {
                return response()->json(['message' => 'Transactions deleted successfully.']);
            } else {
                return response()->json(['message' => 'No transactions were deleted. Please check the IDs.'], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting transactions: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while deleting transactions: ' . $e->getMessage()], 500);
        }
    }
}
