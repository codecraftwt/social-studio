<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        return view('categories.create', compact('categories'));
    }

    //
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id . '|max:255',
        ]);

        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ]);

        Category::destroy($request->input('categories'));

        return redirect()->route('categories.index')->with('success', 'Selected categories have been deleted successfully.');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|unique:categories|max:255',
                'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $data = $request->all();
    
            if ($request->hasFile('category_image')) {
                $imagePath = $request->file('category_image')->store('category_images', 'public'); // Store the image in the public disk
                $data['category_image'] = $imagePath; // Add the image path to the data array
            }
    
            Category::create($data);
            return redirect()->route('categories.create')->with('success', 'Category added successfully.');
    
        } catch (\Exception $e) {
            return redirect()->route('categories.create')->withErrors(['error' => 'Failed to add category: ' . $e->getMessage()]);
        }
    }    

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['success' => 'Category deleted successfully.']);
    }

    public function toggleStatus($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->status = !$category->status; // Toggle status
            $category->save();
    
            return response()->json(['success' => true, 'status' => $category->status]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the status.'], 500);
        }
    }

    public function bulkToggleStatus(Request $request)
    {
        $this->validate($request, [
            'categories' => 'required|array',
            'status' => 'required|boolean',
        ]);

        try {
            $status = $request->status;
            Category::whereIn('id', $request->categories)->update(['status' => $status]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the status.'], 500);
        }
    }

}
