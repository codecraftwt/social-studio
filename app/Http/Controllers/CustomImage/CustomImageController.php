<?php

namespace App\Http\Controllers\CustomImage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class CustomImageController extends Controller
{
    public function index(Request $request)
    {
        // Get the logged-in user
        $user = Auth::user();
        $postId = $request->query('id');
        // $post = Post::find($postId);
        $post = Post::findOrFail($postId);
        if (!$post) {
            return redirect()->back()->with('error', 'Post not found.');
        }

        return view('custom_image.cust_img', compact('user', 'post'));
    }
}
