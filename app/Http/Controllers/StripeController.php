<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use App\Models\Subscription;

class StripeController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $plan = $request->query('plan', 'free');
        $price = $plan === 'premium' ? 2000 : ($plan === 'standard' ? 1000 : 0); // Prices in cents

        if ($price === 0) {
            return redirect('/register')->withErrors('Invalid plan selected.');
        }

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $plan === 'premium' ? 'Premium Plan' : 'Standard Plan',
                    ],
                    'unit_amount' => $price, // Amount in cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
        ]);

        return redirect($session->url);
    }

    public function subscriptionSuccess(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $sessionId = $request->query('session_id');
        $session = StripeSession::retrieve($sessionId);

        if ($session->payment_status === 'paid') {
            $user = auth()->user();
            $plan = $session->line_items->data[0]->description;
            $price = $session->amount_total / 100;

            // Save subscription record
            Subscription::create([
                'user_id' => $user->id,
                'subscription_type' => $plan,
                'price' => $price,
                'expiry_date' => now()->addMonth(), // Adjust based on your subscription logic
            ]);

            return view('subscription.success');
        }

        return view('subscription.failure');
    }

    public function subscriptionCancel()
    {
        return view('subscription.cancel');
    }
}
