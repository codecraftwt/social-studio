<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

class PostController extends Controller
{
    //

    public function index()
    {
        $posts = Post::with('category')->get();
        $categories = Category::all(); // Fetch all categories
        return view('posts.index', compact('posts', 'categories')); // Pass categories to the view
    }

    public function create()
    {
        $categories = Category::all(); // Fetch all categories
        return view('posts.create', compact('categories')); // Pass categories to the view
    }

    public function store(Request $request)
    {
        $request->validate([
            'link' => 'nullable|url',
            'post_image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'post_title' => 'required|max:255',
            'post_explanation' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        $postData = $request->all();

        if ($request->hasFile('post_image')) {
            // $postData['post_image'] = $request->file('post_image')->store('post_images', 'public');
            $postData['post_image'] = $request->file('post_image')->store('app/public/post_images', 'public');

        }

        Post::create($postData);

        return redirect()->route('posts.index')->with('success', 'Post added successfully.');
    }

    public function edit(Post $post)
    {
        $categories = Category::all(); // Fetch all categories
        return view('posts.edit', compact('post', 'categories')); // Pass categories to the view
    }

    // public function update(Request $request, Post $post)
    // {
    //     $request->validate([
    //         'link' => 'nullable|url',
    //         'post_image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
    //         'post_title' => 'required|max:255',
    //         'post_explanation' => 'required',
    //         'category_id' => 'required|exists:categories,id',
    //     ]);

    //     $postData = $request->all();

    //     if ($request->hasFile('post_image')) {
    //         $postData['post_image'] = $request->file('post_image')->store('post_images', 'public');
    //     }

    //     $post->update($postData);

    //     return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    // }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'post_title' => 'required|max:255',
            'post_explanation' => 'required',
            'link' => 'nullable|url',
            'category_id' => 'required|exists:categories,id',
            'post_image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);
    
        $postData = $request->except('post_image');
    
        if ($request->hasFile('post_image')) {
            $postData['post_image'] = $request->file('post_image')->store('post_images', 'public');
        }
    
        $post->update($postData);
    
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
    
        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }
    

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'posts' => 'required|array',
            'posts.*' => 'exists:posts,id'
        ]);

        Post::destroy($request->input('posts'));

        return redirect()->route('posts.index')->with('success', 'Selected posts deleted successfully.');
    }
}
