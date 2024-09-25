<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Models\HeaderFooter;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        // Fetch all categories from the database
        $categories = Category::all();

        if (auth()->check()) {
            // return view('home', compact('categories'));
            $totalUsers = User::count();
            $totalCategories = Category::count();
            $totalPosts = Post::count();
        
            return view('home', compact('totalUsers', 'totalCategories', 'categories', 'totalPosts'));
        }else{
            return redirect()->route('login')->with('error', 'You need to log in to access the dashboard.');
        }
    }

    public function index()
    {
        $categories = Category::all();
        $today = now()->startOfDay(); // Get today's start of day

        // Add a 'new' flag to each category
        $categories = $categories->map(function ($category) use ($today) {
            $category->isNew = $category->created_at->isSameDay($today);
            return $category;
        });

        return view('welcome', compact('categories'));
    }

    public function getPostsByCategory(Request $request)
    {
        // $categoryId = $request->input('category_id');
        // $posts = Post::where('category_id', $categoryId)->get();

        // return response()->json($posts);

         $categoryId = $request->input('category_id');
    $userId = auth()->id(); // Get the logged-in user ID

    // Retrieve the posts
    $posts = Post::where('category_id', $categoryId)->get();

    // Retrieve header and footer paths
    $headerFooter = HeaderFooter::where('user_id', $userId)->first();
    

    // Return posts and header/footer paths
    return response()->json([
        'posts' => $posts,
        'headerPath' => $headerFooter ? $headerFooter->header_path : null,
        'footerPath' => $headerFooter ? $headerFooter->footer_path : null,
    ]);
    }

}
