<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DownloadRecord;
use App\Models\Subscription;
use App\Models\TransactionDetail;
use Carbon\Carbon;

class DownloadRecordController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
        ]);

        DownloadRecord::create([
            'user_id' => $request->user_id,
            'post_id' => $request->post_id,
        ]);

        return response()->json(['message' => 'Download record saved successfully.']);
    }

    public function checkDownloadLimit(Request $request)
    {
        $userId = $request->input('user_id');
        
        // Fetch the user's subscription
        $subscription = Subscription::where('user_id', $userId)->latest()->first();
        $isAdmin = auth()->user()->role_id == 1;
        if(!$isAdmin){
            $transaction = TransactionDetail::where('user_id', $userId)->latest()->first();

            // Check if the transaction exists and its status
            if ($transaction) {
                if($transaction->status == '0' && Carbon::now()->gt($transaction->plan_expiry_date)){
                    return response()->json([
                        'subscriptionOver' => true,
                        'redirectUrl' => url('/plans')
                    ]);
                }
                if ($transaction->status == '0') {
                    return response()->json([
                        'transactionLimita' => true,
                        'redirectUrl' => url('/')
                    ]);
                }
                if (Carbon::now()->gt($transaction->plan_expiry_date)) {
                    return response()->json([
                        'subscriptionOver' => true,
                        'redirectUrl' => url('/plans')
                    ]);
                }
            }else{
                return response()->json([
                    'transactionLimitb' => true,
                    'redirectUrl' => url('/plans')
                ]);
            }
            // if (!$subscription) {
            //     return response()->json([
            //         'exceededLimit' => true,
            //         'redirectUrl' => url('/choose-plan')
            //     ]);
            // }
            
            // Determine the access duration based on the subscription type
            // $now = Carbon::now();
            // $expiryDate = Carbon::parse($subscription->expiry_date);
            
            // Check if the subscription is expired
            // if ($now->gt($expiryDate)) {
            //     return response()->json([
            //         'exceededLimit' => true,
            //         'redirectUrl' => url('/choose-plan')
            //     ]);
            // }

            if($transaction->subscription_type === 'free'){
                $downloadLimit = $transaction->subscription_type === 'free' ? 5 : PHP_INT_MAX;
                $downloadCount = DownloadRecord::where('user_id', $userId)->count();
                if($downloadCount == '4'){
                    TransactionDetail::where('user_id', auth()->id())
                    ->where('subscription_type', 'free')
                    ->update([
                        'status' => 0,
                        'plan_expiry_date' => now(),
                    ]);
                }
                if($downloadCount >= $downloadLimit){
                    return response()->json([
                        'subscriptionOver' => true,
                        'redirectUrl' => url('/plans')
                    ]);
                }
            }
        }
    }

    public function getUserDetails(Request $request)
    {
        $user = auth()->user(); // Get the currently authenticated user
        
        return response()->json([
            'name' => $user->name,
            'mobile' => $user->mobile,
            'email' => $user->email,
            'address'=> $user->address,
        ]);
    }

}
