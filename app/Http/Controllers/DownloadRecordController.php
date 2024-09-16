<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DownloadRecord;
use App\Models\Subscription;
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
        $subscription = Subscription::where('user_id', $userId)->first();
        
        if (!$subscription) {
            return response()->json([
                'exceededLimit' => true,
                'redirectUrl' => url('/choose-plan')
            ]);
        }
        
        // Determine the access duration based on the subscription type
        $now = Carbon::now();
        $expiryDate = Carbon::parse($subscription->expiry_date);
        
        // Check if the subscription is expired
        if ($now->gt($expiryDate)) {
            return response()->json([
                'exceededLimit' => true,
                'redirectUrl' => url('/choose-plan')
            ]);
        }
        
        // Check the download limit
        $downloadLimit = $subscription->subscription_type === 'free' ? 2 : PHP_INT_MAX;
        $downloadCount = DownloadRecord::where('user_id', $userId)->count();
        
        $exceededLimit = $downloadCount >= $downloadLimit;

        return response()->json([
            'exceededLimit' => $exceededLimit,
            'redirectUrl' => $exceededLimit ? url('/choose-plan') : null
        ]);
    }
}
