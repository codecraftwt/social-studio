<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Models\HeaderFooter;
use App\Models\SubCategory;
use Carbon\Carbon;

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
            $totalUsers = User::where('role_id', '!=', 1)->count();
            $totalCategories = Category::count();
            $totalPosts = Post::count();
            $SubCategory = SubCategory::count(); 
            return view('home', compact('totalUsers', 'totalCategories', 'categories', 'totalPosts', 'SubCategory'));
        }else{
            return redirect()->route('login')->with('error', 'You need to log in to access the dashboard.');
        }
    }

    public function index()
    {
        // $categories = Category::all();
        $categories = Category::where('status', 1)->get();
        // $SubCategory = SubCategory::where('status', 1)->get();
        $SubCategory = SubCategory::whereHas('category', function ($query) {
            $query->where('status', 1); // Only include active parent categories
        })
        ->where('status', 1)
        ->get();
        $today = now()->startOfDay(); // Get today's start of day

        // Add a 'new' flag to each category
        $categories = $categories->map(function ($subcategory) use ($today) {
            $subcategory->isNew = $subcategory->created_at->isSameDay($today);
            return $subcategory;
        });

        return view('welcome', compact('categories','SubCategory'));
    }

    public function getSubCategories($categoryId)
    {
        try {
            // $subcategories = SubCategory::where('category_id', $categoryId)->get();
            $subcategories = SubCategory::where('category_id', $categoryId)
                ->where('status', 1) // Only include active subcategories
                ->get();
            
            // Check if subcategories exist
            if ($subcategories->isEmpty()) {
                return response()->json(['message' => 'No subcategories found for this category.'], 404);
            }
    
            return response()->json($subcategories);
        } catch (\Exception $e) {
            \Log::error('Error fetching subcategories: ' . $e->getMessage());
    
            return response()->json(['error' => 'An error occurred while fetching subcategories.'], 500);
        }
    }
    

    public function getPostsByCategory(Request $request)
    {
        try {
            $categoryId = $request->input('category_id');
            $userId = auth()->id(); // Get the logged-in user ID
    
            // $posts = Post::where('sub_category_id', $categoryId)->get();
            $posts = Post::where('sub_category_id', $categoryId)
             ->where('status', 1) 
             ->get();
    
            $headerFooter = HeaderFooter::where('user_id', $userId)->first();
            // foreach ($posts as $post) {
            //     $imagePath = storage_path('app/public/' . $post->post_image);
            //     if (file_exists($imagePath)) {
            //         $imageData = base64_encode(file_get_contents($imagePath));
            //         $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
            //         $post->base64_image = "data:image/{$imageType};base64,{$imageData}";
            //     } else {
            //         $post->base64_image = null; // Handle missing image
            //     }
            // }
            return response()->json([
                'posts' => $posts,
                'headerPath' => $headerFooter ? $headerFooter->header_path : null,
                'footerPath' => $headerFooter ? $headerFooter->footer_path : null,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching posts by category: ' . $e->getMessage());
    
            return response()->json(['error' => 'An error occurred while fetching posts.'], 500);
        }
    }    
}
