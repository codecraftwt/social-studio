<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        // Check if the user's status is inactive
        if ($user->status == 0) { // Assuming 0 means inactive
            Auth::logout(); // Log out the user
            throw ValidationException::withMessages([
                'email' => ['Your account is inactive. Please contact support.'],
            ]);
        }
        session()->flash('success', 'Login successful! Welcome back, ' . $user->name . '!');
        $remember = $request->has('remember');
        Auth::login($user, $remember);
        if ($user->isAdmin()) { // Assuming you have an isAdmin method or a role field
            return redirect()->route('dashboard'); // Adjust the route name as necessary
        }
        return redirect()->route('home');
    }
}
