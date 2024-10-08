<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\SubCategory;

class PostController extends Controller
{
    //

    public function index()
    {
        $posts = Post::with(['category', 'sub_category'])->orderBy('created_at', 'desc')->get();
        if ($posts->isEmpty()) {
            $posts = collect(); 
        }
        // $SubCategory = SubCategory::all(); 
        // $categories = Category::all(); 
        $SubCategory = SubCategory::orderBy('created_at', 'desc')->get(); 
        $categories = Category::orderBy('created_at', 'desc')->get(); 
        return view('posts.index', compact('posts', 'categories', 'SubCategory')); 
    }

    public function create()
    {
        $subcategories = SubCategory::with('category')->get()->groupBy('category_id');
        $categories = Category::all(); 
        return view('posts.create', compact('categories', 'subcategories')); 
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'link' => 'nullable|url',
    //         'post_image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
    //         'post_pdf' => 'nullable|file|mimes:pdf|max:2048',
    //         'post_title' => 'required|max:255',
    //         'post_explanation' => 'required',
    //         'category_id' => 'required|exists:categories,id',
    //         'sub_category_id' => 'required|exists:sub_categories,id',
    //         'header_footer_access' => 'required|in:1,2,3',
    //     ]);

    //     $postData = $request->all();

    //     if ($request->hasFile('post_image')) {
    //         // $postData['post_image'] = $request->file('post_image')->store('post_images', 'public');
    //         $postData['post_image'] = $request->file('post_image')->store('app/public/post_images', 'public');

    //     }

    //     if ($request->hasFile('post_pdf')) {
    //         $postData['post_pdf'] = $request->file('post_pdf')->store('post_pdfs', 'public'); // Store PDFs
    //     }

    //     Post::create($postData);

    //     return redirect()->route('posts.index')->with('success', 'Post added successfully.');
    // }

    public function store(Request $request)
    {
        $request->validate([
            // 'link' => 'nullable|url',
            // 'link' => 'required|string|max:500',
            'link' => [
                    'required',
                    'regex:/^(http|https):\/\/[^\s$.?#].[^\s]*$/',
                    'max:500',
                ],
            'post_image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'post_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'post_title' => 'required|max:255',
            'post_explanation' => 'required',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'header_footer_access' => 'required|in:1,2,3',
            'border_color' => 'required',
        ]);
    
        $postData = $request->all();

        if ($request->hasFile('post_image')) {
            $postData['post_image'] = $this->processImage($request->file('post_image'), $postData);
        }
        
    
        if ($request->hasFile('post_pdf')) {
            $postData['post_pdf'] = $request->file('post_pdf')->store('post_pdfs', 'public'); // Store PDFs
        }
    
        Post::create($postData);
    
        return redirect()->route('posts.index')->with('success', 'Post added successfully.');
    }
    
    


    public function edit(Post $post)
    {
        $categories = Category::all(); 
        return view('posts.edit', compact('post', 'categories')); // Pass categories to the view
    }

    public function update(Request $request, Post $post)
    {
        try {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'sub_category_id' => 'required|exists:sub_categories,id',
            ]);
            
            $postData = $request->except('post_image', 'post_pdf'); 
    
            if ($request->hasFile('post_image')) {
                if ($post->post_image) {
                    Storage::disk('public')->delete($post->post_image);
                }
    
                $postData['post_image'] = $this->processImage($request->file('post_image'), $postData);
            } else {
                $postData['post_image'] = $post->post_image;
            }
    
            if ($request->hasFile('post_pdf')) {
                if ($post->post_pdf) {
                    Storage::disk('public')->delete($post->post_pdf);
                }
                $postData['post_pdf'] = $request->file('post_pdf')->store('post_pdfs', 'public');
            } else {
                $postData['post_pdf'] = $post->post_pdf;
            }
        
            $post->update($postData);
        
            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }
    
            return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Post update failed: ' . $e->getMessage());
    
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to update post.'], 500);
            }
    
            return redirect()->back()->withErrors(['error' => 'Failed to update post.'])->withInput();
        }
    }
    
    

    public function destroy(Post $post)
    {
        try {
            if ($post->post_image) {
                $imagePath = public_path('storage/' . $post->post_image); // Adjust the path as needed
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Delete the image file
                }
            }
    
            if ($post->post_pdf) { // Assuming you have a 'post_pdf' field in your Post model
                $pdfPath = public_path('storage/' . $post->post_pdf); // Adjust the path as needed
                if (file_exists($pdfPath)) {
                    unlink($pdfPath); // Delete the PDF file
                }
            }

            $post->delete();
            return response()->json(['success' => true, 'message' => 'Post deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting post.'], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'posts' => 'required|array',
                'posts.*' => 'exists:posts,id'
            ]);
            $posts = Post::whereIn('id', $request->input('posts'))->get(); 

            foreach ($posts as $post) {
                if ($post->post_image) {
                    $imagePath = public_path('storage/' . $post->post_image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath); 
                    }
                }
    
                if ($post->post_pdf) {
                    $pdfPath = public_path('storage/' . $post->post_pdf); 
                    if (file_exists($pdfPath)) {
                        unlink($pdfPath); 
                    }
                }
            }
    
            Post::destroy($request->input('posts'));
    
            return response()->json(['success' => true, 'message' => 'Selected posts deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function toggleStatus(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required|boolean',
        ]);
    
        try {
            $post = Post::findOrFail($id);
            $post->status = $request->status;
            $post->save();
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the status.'], 500);
        }
    }   

    public function bulkToggleStatus(Request $request)
    {
        $this->validate($request, [
            'posts' => 'required|array',
            'status' => 'required|boolean',
        ]);

        try {
            $status = $request->status;
            Post::whereIn('id', $request->posts)->update(['status' => $status]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred while updating the status.'], 500);
        }
    }

    private function processImage($image, $postData)
    {
        $img = imagecreatefromstring(file_get_contents($image->getRealPath()));
        
        $canvasWidth = 750; // Desired width
        $canvasHeight = 800; // Total desired height
        $headerHeight = 120; // Header height
        $footerHeight = 120; // Footer height
    
        // Create a blank canvas
        $canvas = imagecreatetruecolor($canvasWidth, $canvasHeight);
    
        function hexToRgb($hex) {
            $hex = ltrim($hex, '#'); // Remove the '#' if it exists
            if (strlen($hex) === 3) {
                $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2); // Convert to 6 digits
            }
            return [
                hexdec(substr($hex, 0, 2)),
                hexdec(substr($hex, 2, 2)),
                hexdec(substr($hex, 4, 2)),
            ];
        }

        $white = imagecolorallocate($canvas, 255, 255, 255);
        $headerColor = $white; // Header color
        $footerColor = $white; // Footer color set to white
        $headerColorHex = $postData['header_color'];
        list($r, $g, $b) = hexToRgb($headerColorHex);
        $headerColor = imagecolorallocate($canvas, $r, $g, $b);
        // $borderColor = imagecolorallocate($canvas, 0, 76, 114); // Border color from hex #004c72

        $borderColorHex = $postData['border_color'];
        list($r, $g, $b) = hexToRgb($borderColorHex);
        $borderColor = imagecolorallocate($canvas, $r, $g, $b);
    
        imagefill($canvas, 0, 0, $white);
        $headerFooterAccess = $postData['header_footer_access'];
        
    
        // Draw header if selected
        if ($headerFooterAccess == '1' || $headerFooterAccess == '2') {
            imagefilledrectangle($canvas, 0, 0, $canvasWidth, $headerHeight, $headerColor);
        
            // Load the font file
            $fontFile = public_path('fonts/NotoSans-MediumItalic.ttf');
            $headerTitle = $postData['header_title'];
            $fontSize = 20; 
            $textColorHex = $postData['text_color'];
            list($r, $g, $b) = hexToRgb($textColorHex);
            $textColor = imagecolorallocate($canvas, $r, $g, $b);
            // Calculate text dimensions
            $boundingBox = imagettfbbox($fontSize, 0, $fontFile, $headerTitle);
            $textWidth = abs($boundingBox[2] - $boundingBox[0]); // Width of the text
            $textX = ($canvasWidth - $textWidth) / 2; // Center the text horizontally
            $textY = ($headerHeight / 2) + ($fontSize / 2); // Center the text vertically
        
            // Draw the title on the canvas
            imagettftext($canvas, $fontSize, 0, $textX, $textY, $textColor, $fontFile, $headerTitle);
        }
    
        // Calculate the height for the image area
        $imageHeight = $canvasHeight - ($headerFooterAccess == '3' ? $footerHeight : ($headerFooterAccess == '2' ? $headerHeight : $headerHeight + $footerHeight));
    
        // Draw the image on the canvas
        imagecopyresampled($canvas, $img, 0, ($headerFooterAccess == '1' || $headerFooterAccess == '2') ? $headerHeight : 0, 0, 0, $canvasWidth, $imageHeight, imagesx($img), imagesy($img));
    
        // Draw footer if selected
        if ($headerFooterAccess == '1' || $headerFooterAccess == '3') {
            imagefilledrectangle($canvas, 0, $canvasHeight - $footerHeight, $canvasWidth, $canvasHeight, $footerColor);
        }
    
        // Set border thickness
        $borderThickness = 10; // Set thickness to 10 pixels
    
        // Function to draw borders
        function drawBorder($canvas, $x1, $y1, $x2, $y2, $borderColor, $thickness) {
            for ($i = 0; $i < $thickness; $i++) {
                imageline($canvas, $x1 + $i, $y1 + $i, $x2 - $i, $y1 + $i, $borderColor); // Top
                imageline($canvas, $x1 + $i, $y2 - $i, $x2 - $i, $y2 - $i, $borderColor); // Bottom
                imageline($canvas, $x1 + $i, $y1 + $i, $x1 + $i, $y2 - $i, $borderColor); // Left
                imageline($canvas, $x2 - $i, $y1 + $i, $x2 - $i, $y2 - $i, $borderColor); // Right
            }
        }
    
        // Add borders based on header_footer_access
        if ($headerFooterAccess == '1') {
            // Border for header, footer, and image
            drawBorder($canvas, 0, 0, $canvasWidth, $headerHeight , $borderColor, $borderThickness); // Header border
            drawBorder($canvas, 0, $canvasHeight - $footerHeight, $canvasWidth, $canvasHeight, $borderColor, $borderThickness); // Footer border
            drawBorder($canvas, 0, 0, $canvasWidth, $canvasHeight , $borderColor, $borderThickness); // Image border
        } elseif ($headerFooterAccess == '2') {
            // Border for header and image
            drawBorder($canvas, 0, 0, $canvasWidth, $headerHeight, $borderColor, $borderThickness); // Header border
            drawBorder($canvas, 0, 0, $canvasWidth, $canvasHeight , $borderColor, $borderThickness); // Image border
        } elseif ($headerFooterAccess == '3') {
            // Border for image and footer
            drawBorder($canvas, 0, $canvasHeight - $footerHeight, $canvasWidth, $canvasHeight, $borderColor, $borderThickness); // Footer border
            drawBorder($canvas, 0, 0, $canvasWidth, $canvasHeight + $footerHeight, $borderColor, $borderThickness); // Image border
        }

        if ($headerFooterAccess == '1' || $headerFooterAccess == '3') {
            $lineX = 100; // 100 pixels from the left
            $lineY1 = $canvasHeight - $footerHeight; // Start from the top of the footer
            $lineY2 = $canvasHeight; // End at the bottom of the footer
            $lineWidth = 10; // Line width
    
            // Draw the vertical line
            for ($i = 0; $i < $lineWidth; $i++) {
                imageline($canvas, $lineX + $i, $lineY1, $lineX + $i, $lineY2, $borderColor);
            }

            // $lineX = $canvasWidth - 100; // 100 pixels from the right
            // $lineY1 = $canvasHeight - $footerHeight; // Start from the top of the footer
            // $lineY2 = $canvasHeight; // End at the bottom of the footer
            // $lineWidth = 10; 
            // $rectangleWidth = 100; // Width of the rectangle to fill
            // $rectangleX = $canvasWidth - $rectangleWidth;
            // // imagefilledrectangle($canvas, $lineX, $lineY1, $lineX + $lineWidth, $lineY2, $borderColor);
            // imagefilledrectangle($canvas, $rectangleX, $canvasHeight - $footerHeight, $canvasWidth, $canvasHeight, $borderColor);
            // for ($i = 0; $i < $lineWidth; $i++) {
            //     imageline($canvas, $lineX + $i, $lineY1, $lineX + $i, $lineY2, $borderColor);
            // }

            $logoPath = public_path('storage/images/surreta_logo.png');
            // $logoImg = imagecreatefrompng($logoPath); // Adjust if it's a different format
            
            $errorReportingLevel = error_reporting(E_ALL & ~E_WARNING);
            $logoImg = @imagecreatefrompng($logoPath);
            error_reporting($errorReportingLevel);

            // Resize logo if needed
            $logoWidth = 90; // Desired logo width
            $logoHeight = 100; // Desired logo height (adjust as needed)
            $logoResized = imagecreatetruecolor($logoWidth, $logoHeight);
            $white = imagecolorallocate($logoResized, 255, 255, 255);
            $white_light = imagecolorallocate($logoResized, 223, 236, 255);
            imagefilledrectangle($logoResized, 0, 0, $logoWidth, $logoHeight, $white_light);
            imagecopyresampled($logoResized, $logoImg, 0, 0, 0, 0, $logoWidth, $logoHeight, imagesx($logoImg), imagesy($logoImg));

            $logoX = 10; 
            $logoY = $canvasHeight - $footerHeight + 10; // 10 pixels from the top of the footer

            // Draw logo on the canvas
            imagecopy($canvas, $logoResized, $logoX, $logoY, 0, 0, $logoWidth, $logoHeight);

            // Clean up logo image resources
            imagedestroy($logoImg);
            imagedestroy($logoResized);
            // $fontFile = public_path('fonts/NotoSans-MediumItalic.ttf');
            // $categoryName = Category::where('id', $postData['category_id'])->value('name');
            // $textX = $lineX + 10; // Offset from the line
            // $textY = ($lineY1 + $lineY2) / 2 - 10;
            // $fontSize = 10; 
            // imagettftext($canvas, $fontSize, 0, $textX, $textY, $white, $fontFile, $categoryName);
        }
        // $fontFile = public_path('fonts/NotoSans-MediumItalic.ttf');
        // $categoryName = "KARANJKAR ONLINE & CSC CENTER ✆ 7387017005 | | ✉ karanjkaronline1@gmail.com"; 
        
        // $watermarkFontSize = 10; 
        
        // $watermarkColor = imagecolorallocatealpha($canvas, 0, 0, 0, 75); 
        // $backgroundColor = imagecolorallocatealpha($canvas, 255, 255, 255, 50); 
        
        // $boundingBox = imagettfbbox($watermarkFontSize, 0, $fontFile, $categoryName);
        // $watermarkWidth = abs($boundingBox[2] - $boundingBox[0]);
        // $watermarkHeight = abs($boundingBox[5] - $boundingBox[1]);
        
        // $centerX = ($canvasWidth - $watermarkWidth) / 2;
        // $centerY = ($canvasHeight - $watermarkHeight) / 2 + $watermarkHeight;
        
        // imagefilledrectangle($canvas, $centerX - 5, $centerY - $watermarkHeight - 5, 
        //                      $centerX + $watermarkWidth + 5, $centerY + 5, $backgroundColor);
        
        // imagettftext($canvas, $watermarkFontSize, 0, $centerX, $centerY, $watermarkColor, $fontFile, $categoryName);

        $fontFile = public_path('fonts/NotoSans-MediumItalic.ttf');
        // $categoryName = "KARANJKAR ONLINE & CSC CENTER";
        // $contactInfo = "✆ 7387017005 | | ✉ karanjkaronline1@gmail.com"; 
        $categoryName = "";
        $contactInfo = ""; 

        $watermarkFontSize = 10; 
        $watermarkColor = imagecolorallocatealpha($canvas, 0, 0, 0, 0); 
        $backgroundColor = imagecolorallocatealpha($canvas, 255, 255, 255, 50); 

        // Calculate the bounding box for both lines
        $boundingBox1 = imagettfbbox($watermarkFontSize, 0, $fontFile, $categoryName);
        $boundingBox2 = imagettfbbox($watermarkFontSize, 0, $fontFile, $contactInfo);

        $watermarkWidth1 = abs($boundingBox1[2] - $boundingBox1[0]);
        $watermarkHeight1 = abs($boundingBox1[5] - $boundingBox1[1]);
        $watermarkWidth2 = abs($boundingBox2[2] - $boundingBox2[0]);
        $watermarkHeight2 = abs($boundingBox2[5] - $boundingBox2[1]);

        // Calculate the center positions for each line
        $centerX1 = ($canvasWidth - $watermarkWidth1) / 2;
        $centerY1 = ($canvasHeight - $watermarkHeight1) / 2;

        $centerX2 = ($canvasWidth - $watermarkWidth2) / 2;
        $centerY2 = $centerY1 + $watermarkHeight1; // Position second line below the first

        // Create the background rectangle for the first line
        imagefilledrectangle($canvas, $centerX1 - 5, $centerY1 - $watermarkHeight1 - 5, 
                            $centerX1 + $watermarkWidth1 + 5, $centerY1 + 5, $backgroundColor);

        // Create the background rectangle for the second line
        imagefilledrectangle($canvas, $centerX2 - 5, $centerY2 - $watermarkHeight2 - 5, 
                            $centerX2 + $watermarkWidth2 + 5, $centerY2 + 5, $backgroundColor);

        // Apply the text with a 45-degree angle
        $angle = 45;

        // Draw the first line
        imagettftext($canvas, $watermarkFontSize, $angle, $centerX1, $centerY1, $watermarkColor, $fontFile, $categoryName);

        // Draw the second line
        imagettftext($canvas, $watermarkFontSize, $angle, $centerX2, $centerY2, $watermarkColor, $fontFile, $contactInfo);

        ob_start();
        imagepng($canvas);
        $imageData = ob_get_contents();
        ob_end_clean();
    
        // Save the processed image using Storage
        $processedImagePath = 'post_images/' . uniqid() . '.png';
        Storage::disk('public')->put($processedImagePath, $imageData); // Store the image in 'public' disk
    
        // Clean up
        imagedestroy($canvas);
        imagedestroy($img);

        return $processedImagePath;
    }


}
