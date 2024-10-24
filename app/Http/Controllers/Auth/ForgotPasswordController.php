<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    protected function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email|exists:users,email']);
    
        // Here you can use the original sendResetLinkEmail logic.
        $response = $this->broker()->sendResetLink($request->only('email'));
    
        if ($response == Password::RESET_LINK_SENT) {
            // Set a success message
            Session::flash('status', __('A password reset link has been sent to your email.'));
        }
    
        return back()->withInput($request->only('email'));
    }
    use SendsPasswordResetEmails;
}
