<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Subscription;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $plan = request()->input('plan', 'free');
        // Retrieve the Subscriber role
        $subscriberRole = Role::where('name', 'Subscriber')->first();
    
        // Create the user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $subscriberRole ? $subscriberRole->id : null,
        ]);
    
        // Determine the expiry date based on the plan
        $expiryDate = null;
        if ($plan === 'standard') {
            // $expiryDate = now()->addMonth();
            return redirect()->route('create-checkout-session', ['plan' => $plan]);
        } elseif ($plan === 'premium') {
            // $expiryDate = now()->addMonth();
            return redirect()->route('create-checkout-session', ['plan' => $plan]);
        }
    
        // Create the subscription record
        if ($plan == 'free') { // Assuming free plan does not require a subscription record
            Subscription::create([
                'user_id' => $user->id,
                'subscription_type' => $plan,
                'download_limit' => '2',
            ]);
        }
      
        if ($plan !== 'free') {
            return redirect()->route('create-checkout-session', ['plan' => $plan]);
        }
    
        return $user;
    }
}
