<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Role;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function create()
    {
        $roles = Role::all(); // Fetch roles from the database
        return view('User.create_user', compact('roles')); // Adjust view path if necessary
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'current_location' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'address' => $validated['address'],
            'postal_code' => $validated['postal_code'],
            'current_location' => $validated['current_location'],
            'mobile' => $validated['mobile'],
            'role_id' => $validated['role_id'],
        ]);

        if ($request->hasFile('profile_pic')) {
            $profilePicPath = $request->file('profile_pic')->store('profile_pics', 'public');
            
            $user->profile_pic = $profilePicPath;
            $user->save();
        }

        return redirect()->route('users.create')->with('success', 'User created successfully!');
    }

    public function index()
    {
        $roles = Role::all();
        $users = User::with('role')->where('role_id', 2)->get();
        return view('User.index', compact('users', 'roles'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('User.edit_user', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'current_location' => 'nullable|string',
            'mobile' => 'nullable|string',
            'role_id' => 'nullable|exists:roles,id',
            'profile_pic' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        try {
            $user = User::findOrFail($id);
            $user->email = $request->input('email');
            $user->name = $request->input('name');
            $user->address = $request->input('address');
            $user->postal_code = $request->input('postal_code');
            $user->current_location = $request->input('current_location');
            $user->mobile = $request->input('mobile');
            $user->role_id = $request->input('role_id');
    
            if ($request->hasFile('profile_pic')) {
                if ($user->profile_pic) {
                    Storage::disk('public')->delete($user->profile_pic);
                }
                $path = $request->file('profile_pic')->store('profile_pic', 'public');
                $user->profile_pic = $path;
            }
    
            $user->save();
    
            // return response()->json(['success' => true]);
            return redirect()->route('users.index')->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            \Log::error('User update failed: ' . $e->getMessage());
            
            // return response()->json(['success' => false, 'errors' => [$e->getMessage()]]);

            return redirect()->back()->withErrors(['error' => 'Failed to update user. Please try again.']);
        }
    }
    
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        try {
            User::destroy($request->user_ids);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Bulk delete failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting users.']);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->status = $user->status == 1 ? 0 : 1; // Toggle between 1 (Active) and 0 (Inactive)
            $user->save();
    
            return response()->json(['status' => $user->status]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function bulkActivate(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        try {
            User::whereIn('id', $request->user_ids)->update(['status' => 1]); // Set status to 1 (Active)
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Bulk activation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while activating users.']);
        }
    }

    public function bulkDeactivate(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        try {
            User::whereIn('id', $request->user_ids)->update(['status' => 0]); // Set status to 0 (Inactive)
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Bulk deactivation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while deactivating users.']);
        }
    }

}
