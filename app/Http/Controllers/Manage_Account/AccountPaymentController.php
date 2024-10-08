<?php

namespace App\Http\Controllers\Manage_Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccountDetail;
use App\Models\ScannerDetail;
use Illuminate\Support\Facades\DB;

class AccountPaymentController extends Controller
{
    //
    public function storeAccount(Request $request)
    {
        // Validate the request
        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:20',
            'ifsc_code' => 'required|string|max:11',
            'bank_name' => 'required|string|max:255',
        ]);

        // Create a new account
        $account = new AccountDetail();
        $account->account_name = $request->account_name;
        $account->account_number = $request->account_number;
        $account->ifsc_code = $request->ifsc_code;
        $account->bank_name = $request->bank_name;
        $account->save();

        return redirect()->back()->with('success', 'Account created successfully!');
    }

    public function create()
    {
        $accountDetails = AccountDetail::all();
        $scannerDetails = ScannerDetail::all();
        return view('account_details.create', compact('accountDetails', 'scannerDetails')); 
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|max:255',
            'OR_code' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $path = $request->file('OR_code')->store('Scanner/or_codes', 'public');
    
        $payment = new ScannerDetail();
        $payment->payment_method = $request->payment_method;
        $payment->OR_code = $path;
        $payment->save();
    
        return redirect()->back()->with('success', 'Payment method created successfully!');
    }

    // public function toggleAccountStatus($id)
    // {
    //     $account = AccountDetail::findOrFail($id);
    //     $account->status = !$account->status; // Toggle the status
    //     $account->save();

    //     return redirect()->back()->with('success', 'Account status updated successfully.');
    // }

    public function toggleAccountStatus($id)
    {
        DB::beginTransaction();
        try {
            $account = AccountDetail::findOrFail($id);

            if ($account->status == 0) {
                AccountDetail::where('id', '!=', $id)->update(['status' => 0]);
                $account->status = 1;
            } else {
                $account->status = 0;
            }

            $account->save();
            
            DB::commit();

            return redirect()->back()->with('success', 'Account status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'An error occurred while updating the account status: ' . $e->getMessage());
        }
    }

    // public function toggleScannerStatus($id)
    // {
    //     $scanner = ScannerDetail::findOrFail($id);
    //     $scanner->status = !$scanner->status; // Toggle the status
    //     $scanner->save();

    //     return redirect()->back()->with('success', 'Scanner status updated successfully.');
    // }

    public function toggleScannerStatus($id)
    {
        DB::beginTransaction();
        try {
            $scanner = ScannerDetail::findOrFail($id);
    
            // If the scanner is currently inactive, activate it
            if ($scanner->status == 0) {
                // Deactivate all other scanners
                ScannerDetail::where('status', 1)->update(['status' => 0]);
                // Activate the selected scanner
                $scanner->status = 1;
            } else {
                // If the scanner is already active, set it to inactive
                $scanner->status = 0;
            }
    
            $scanner->save();
            
            DB::commit();
    
            return redirect()->back()->with('success', 'Scanner status updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()->back()->with('error', 'An error occurred while updating the scanner status: ' . $e->getMessage());
        }
    }
    
}
