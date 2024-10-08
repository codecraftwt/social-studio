<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;

class SubCategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'sub_category_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        SubCategory::create([
            'sub_category_name' => $request->sub_category_name,
            'category_id' => $request->category_id,
        ]);

        return redirect()->back()->with('success', 'Sub-Category added successfully!');
    }

    // Display subcategories (index method)
    public function index()
    {
        // $subcategories = SubCategory::with('category')->get();
        // $categories = Category::all();
        $subcategories = SubCategory::with('category')->orderBy('created_at', 'desc')->get();
        $categories = Category::orderBy('created_at', 'desc')->get();
        return view('categories.index_sub_category', compact('subcategories','categories'));
    }

    // Update an existing subcategory
    public function update(Request $request, $id)
    {
        $request->validate([
            'sub_category_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $subcategory = SubCategory::findOrFail($id);
        $subcategory->update([
            'sub_category_name' => $request->sub_category_name,
            'category_id' => $request->category_id,
        ]);

        return redirect()->back()->with('success', 'Sub-Category updated successfully!');
    }

    // Delete a subcategory
    public function destroy($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $subcategory->delete();

        return response()->json(['success' => true, 'message' => 'Sub-Category deleted successfully!']);
    }

    public function toggleStatus($id)
    {
        try {
            $subCategory = SubCategory::findOrFail($id);
            $subCategory->status = $subCategory->status == 1 ? 0 : 1; // Toggle between 1 (Active) and 0 (Inactive)
            $subCategory->save();

            return response()->json(['status' => $subCategory->status]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function bulkToggleStatus(Request $request)
    {
        $this->validate($request, [
            'sub_category_ids' => 'required|array',
            'status' => 'required|boolean',
        ]);

        try {
            $status = $request->status;
            SubCategory::whereIn('id', $request->sub_category_ids)->update(['status' => $status]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the status.'], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        $this->validate($request, [
            'sub_category_ids' => 'required|array',
            'sub_category_ids.*' => 'exists:sub_categories,id',
        ]);

        try {
            SubCategory::destroy($request->sub_category_ids);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while deleting sub-categories.'], 500);
        }
    }
}
