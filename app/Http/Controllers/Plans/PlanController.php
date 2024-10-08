<?php

namespace App\Http\Controllers\Plans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use App\Models\AccountDetail;
use App\Models\ScannerDetail;
use Carbon\Carbon;

class PlanController extends Controller
{
    public function showPlans()
    {
        $userId = auth()->id();
        $subscription = TransactionDetail::where('user_id', $userId)
            ->where('status', 1) 
            ->orderBy('payment_date', 'desc')
            ->first();
        if ($subscription) {
            $expiryDate = Carbon::parse($subscription->payment_date)->addMonths($this->getSubscriptionMonths($subscription->subscription_type));
            
            if ($expiryDate->isFuture()) {
                return view('Plan.plans', compact('subscription'));
            }
        }

        return view('Plan.plans'); 
    }

    public function uploadForm()
    {
        return view('Plan.upload'); 
    }

    public function scannerForm()
    {
        // return view('plan.scanner'); 
        $accountDetails = AccountDetail::where('status', 1)->first();
        $scannerDetail = ScannerDetail::where('status', 1)->first();
        return view('plan.scanner', compact('accountDetails', 'scannerDetail')); 
    }

    public function uploadPayment(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'payment_screenshot' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:2048',
            'transaction_id' => 'required|string|max:255', 
            'subscription_type' => 'required|string|max:255', 
            'amount' => 'required|numeric', 
            'payment_date' => 'required|date', 
        ]);

        $existingSubscription = TransactionDetail::where('user_id', auth()->id())
        ->where('status', 1) // Assuming 1 means active
        ->orderBy('payment_date', 'desc')
        ->first();

        if ($existingSubscription) {
            // Check if the existing subscription is still valid
            $expiryDate = Carbon::parse($existingSubscription->payment_date)->addMonths($this->getSubscriptionMonths($existingSubscription->subscription_type));
            
            if ($expiryDate->isFuture()) {
                // return response()->json([
                //     'message' => 'You are already subscribed to a plan.',
                //     'existing_plan' => $existingSubscription->subscription_type,
                //     'confirm' => true
                // ], 200);
                return redirect()->back()->with('success', 'You have already subscribed this plan.');
            }
        }
    
        $path = null;
    
        // Save the uploaded file
        if ($request->hasFile('payment_screenshot')) {
            $file = $request->file('payment_screenshot');
            $path = $file->store('payments', 'public'); 
        }
    
        try {
            // Create a new TransactionDetail record
            TransactionDetail::create([
                'user_id' => auth()->id(), 
                'transaction_id' => $request->transaction_id,
                'subscription_type' => $request->subscription_type,
                'amount' => $request->amount,
                'payment_screenshot' => $path, 
                'payment_date' => Carbon::parse($request->payment_date),
                'status' => 0, 
            ]);
    
            if ($request->ajax()) {
                return response()->json(['message' => 'Payment details uploaded successfully!'], 201);
            } else {
                // Redirect back with a success message for non-AJAX requests
                // return redirect()->back()->with('success', 'Payment details uploaded successfully!');
                return redirect('/')->with('success', 'Payment details uploaded successfully!');
            }
    
        } catch (\Exception $e) {
            \Log::error('Payment upload failed: ' . $e->getMessage());
    
            // Check if the request is an AJAX request
            if ($request->ajax()) {
                return response()->json(['message' => 'Failed to upload payment details. Please try again.'], 500);
            } else {
                // Redirect back with an error message for non-AJAX requests
                return redirect()->back()->with('error', 'Failed to upload payment details. Please try again.');
            }
        }
    }
      
    private function getSubscriptionMonths($subscriptionType)
    {
        switch ($subscriptionType) {
            case 'three_months':
                return 3;
            case 'six_months':
                return 6;
            case 'one_year':
                return 12;
            default:
                return 0;
        }
    }
    
}
