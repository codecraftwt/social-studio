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
use App\Notifications\WelcomeEmailNotification;
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
    protected $redirectTo = '/';

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
            'address' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:10'],
            'current_location' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:15'],
            // 'plan' => ['required', 'in:free,standard,premium'],
            'profile_pic' => ['required', 'image', 'max:2048'],
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
        // $plan = request()->input('plan', 'free');
        // Retrieve the Subscriber role
        $subscriberRole = Role::where('name', 'Subscriber')->first();
        $profilePicPath = $data['profile_pic']->store('profile_pics', 'public');
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $subscriberRole ? $subscriberRole->id : null,
            'address' => $data['address'],
            'postal_code' => $data['postal_code'],
            'current_location' => $data['current_location'],
            'mobile' => $data['mobile'],
            'profile_pic' => $profilePicPath,
        ]);
        $user->notify(new WelcomeEmailNotification());
        // $expiryDate = null;
        // if ($plan === 'standard') {
        //     // $expiryDate = now()->addMonth();
        //     return redirect()->route('create-checkout-session', ['plan' => $plan]);
        // } elseif ($plan === 'premium') {
        //     // $expiryDate = now()->addMonth();
        //     return redirect()->route('create-checkout-session', ['plan' => $plan]);
        // }
    
        // // Create the subscription record
        // if ($plan == 'free') { // Assuming free plan does not require a subscription record
        //     Subscription::create([
        //         'user_id' => $user->id,
        //         'subscription_type' => $plan,
        //         'download_limit' => '2',
        //     ]);
        // }
      
        // if ($plan !== 'free') {
        //     return redirect()->route('create-checkout-session', ['plan' => $plan]);
        // }
        session()->flash('success', 'Register successful! Welcome , ' . $user->name . '!');
        return $user;
        // session()->flash('success', 'Registration successful! Welcome!');

        // // Redirect the user
        // return redirect($this->redirectTo);
    }
}
