<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    //
    public function subscribe(Request $request)
    {
        $user = Auth::user();
        $subscription = Subscription::findOrFail($request->input('subscription_id'));

        // Check if user already has a subscription
        $userSubscription = UserSubscription::where('user_id', $user->id)->first();

        if ($userSubscription) {
            $userSubscription->update([
                'subscription_id' => $subscription->id,
                'expiry_date' => now()->addMonth(),
            ]);
        } else {
            UserSubscription::create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'expiry_date' => now()->addMonth(),
            ]);
        }

        return response()->json(['message' => 'Subscription updated successfully.']);
    }

    public function upgrade(Request $request)
    {
        $user = Auth::user();
        $subscription = Subscription::where('subscription_type', 'standard')->first();

        if ($subscription) {
            UserSubscription::updateOrCreate(
                ['user_id' => $user->id],
                ['subscription_id' => $subscription->id, 'expiry_date' => now()->addMonth()]
            );

            return response()->json(['message' => 'Subscription upgraded successfully.']);
        }

        return response()->json(['message' => 'Standard subscription not available.'], 404);
    }
}
