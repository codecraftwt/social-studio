<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    // Update the user's profile
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'address' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:15',
            // Add validation for profile picture if necessary
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->mobile = $request->mobile;

        // Handle profile picture upload if applicable
        if ($request->hasFile('profile_pic')) {
            // Delete the existing profile picture if it exists
            if ($user->profile_pic) {
                Storage::disk('public')->delete($user->profile_pic);
            }
            
            // Store the new profile picture
            $path = $request->file('profile_pic')->store('profile_pics', 'public');
            $user->profile_pic = $path;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
