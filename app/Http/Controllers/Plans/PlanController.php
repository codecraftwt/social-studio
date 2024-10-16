<?php

namespace App\Http\Controllers\Plans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use App\Models\AccountDetail;
use App\Models\ScannerDetail;
use Carbon\Carbon;
use App\Notifications\UserPaymentConfirmationMail;
use App\Notifications\AdminPaymentNotificationMail;
use App\Models\Role;
use App\Models\User;

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
            $expiryDate = Carbon::parse($subscription->plan_expiry_date);

            \Log::info('Expiry Date: ' . $expiryDate);
    
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
        if (!$accountDetails) {
            $accountDetails = (object)[
                'account_name' => 'N/A',
                'account_number' => 'N/A',
                'ifsc_code' => 'N/A',
                'bank_name' => 'N/A',
            ];
        }
        return view('plan.scanner', compact('accountDetails', 'scannerDetail')); 
    }

    public function uploadPayment(Request $request)
    {
        $isFreeSubscription = $request->subscription_type === 'free';
 
        $rules = [
            'subscription_type' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
        ];
 
        if (!$isFreeSubscription) {
            $rules['payment_screenshot'] = 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:2048';
            $rules['transaction_id'] = 'required|string|max:255';
        }

        $request->validate($rules);

        $existingSubscription = TransactionDetail::where('user_id', auth()->id())
            ->where('status', 1) // Assuming 1 means active
            ->orderBy('payment_date', 'desc')
            ->first();

        if ($existingSubscription) {
            
            $expiryDate = Carbon::parse($existingSubscription->plan_expiry_date);
            
            if ($expiryDate->isFuture()) {
                return redirect()->back()->with('success', 'You have already subscribed to this plan.');
            }
        }

        $path = null;
 
        if (!$isFreeSubscription && $request->hasFile('payment_screenshot')) {
            $file = $request->file('payment_screenshot');
            $path = $file->store('payments', 'public'); 
        }

        try { 
            TransactionDetail::create([
                'user_id' => auth()->id(), 
                'transaction_id' => $isFreeSubscription ? null : $request->transaction_id,
                'subscription_type' => $request->subscription_type,
                'amount' => $request->amount,
                'payment_screenshot' => $isFreeSubscription ? null : $path, 
                'payment_date' => Carbon::parse($request->payment_date),
                'status' =>  $isFreeSubscription ? 1 : 0, // Assuming 1 means active for free subscriptions
            ]);

            $user = auth()->user();
            $admin = User::where('role_id', 1)->first();
            if ($admin) {
                // Send welcome email to the user
                $user->notify(new UserPaymentConfirmationMail($request->amount, $request->subscription_type));
            
                // Send notification to the admin
                $admin->notify(new AdminPaymentNotificationMail($admin->email, $user->name, $request->amount, $request->subscription_type));
            }

            if ($request->ajax()) {
                return response()->json(['message' => 'Payment details uploaded successfully!'], 201);
            } else {
                return redirect('/')->with('success', 'Payment details uploaded successfully!');
            }

        } catch (\Exception $e) {
            \Log::error('Payment upload failed: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json(['message' => 'Failed to upload payment details. Please try again.'], 500);
            } else {
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
