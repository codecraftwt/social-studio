<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\HeaderFooter;

class HeaderController extends Controller
{
    //
    public function create()
    {
        return view('header&footer.create');
    }
    // public function save(Request $request)
    // {
    //     try {
    //         // Validate the request data
    //         $validated = $request->validate([
    //             'image' => 'required|string',
    //         ]);
    
    //         // Extract and decode the base64 image data
    //         $data = $validated['image'];
    //         $data = str_replace('data:image/png;base64,', '', $data);
    //         $data = base64_decode($data);
    
    //         // Define the file path
    //         $filePath = 'headers/' . uniqid() . '.png';
    
    //         // Ensure the storage path exists
    //         if (!Storage::exists('headers')) {
    //             Storage::makeDirectory('headers');
    //         }
    
    //         // Save the image to the specified path
    //         Storage::put($filePath, $data);
    
    //         // Check if a record already exists for the authenticated user
    //         $headerFooter = HeaderFooter::where('user_id', auth()->id())->first();
    
    //         if ($headerFooter) {
    //             // If a record exists, update it
    //             $headerFooter->header_path = $filePath;
    //             // You can also update other fields here if needed
    //         } else {
    //             // If no record exists, create a new one
    //             $headerFooter = new HeaderFooter();
    //             $headerFooter->user_id = auth()->id();
    //             $headerFooter->header_path = $filePath;
    //             // You can also set other fields here if needed
    //         }
    
    //         // Save the record to the database
    //         $headerFooter->save();
    
    //         // Return a successful response
    //         return response()->json(['message' => 'Header saved successfully!']);
    //     } catch (\Exception $e) {
    //         // Log the detailed error message
    //         \Log::error('Error saving header:', ['exception' => $e]);
    
    //         // Return a failure response with detailed error message
    //         return response()->json(['message' => 'Failed to save header.', 'error' => $e->getMessage()], 500);
    //     }
    // }

    // public function saveFooter(Request $request)
    // {
    //     try {
    //         // Validate the request data
    //         $validated = $request->validate([
    //             'image' => 'required|string',
    //         ]);

    //         // Extract and decode the base64 image data
    //         $data = $validated['image'];
    //         $data = str_replace('data:image/png;base64,', '', $data);
    //         $data = base64_decode($data);

    //         // Define the file path
    //         $filePath = 'footers/' . uniqid() . '.png';

    //         // Ensure the storage path exists
    //         if (!Storage::exists('footers')) {
    //             Storage::makeDirectory('footers');
    //         }

    //         // Save the image to the specified path
    //         Storage::put($filePath, $data);

    //         // Check if a record already exists for the authenticated user
    //         $headerFooter = HeaderFooter::where('user_id', auth()->id())->first();

    //         if ($headerFooter) {
    //             // If a record exists, update it
    //             $headerFooter->footer_path = $filePath;
    //         } else {
    //             // If no record exists, create a new one
    //             $headerFooter = new HeaderFooter();
    //             $headerFooter->user_id = auth()->id();
    //             $headerFooter->footer_path = $filePath;
    //             // Optionally handle header_path if needed
    //         }

    //         // Save the record to the database
    //         $headerFooter->save();

    //         // Return a successful response
    //         return response()->json(['message' => 'Footer saved successfully!']);
    //     } catch (\Exception $e) {
    //         // Log the detailed error message
    //         \Log::error('Error saving footer:', ['exception' => $e]);

    //         // Return a failure response with detailed error message
    //         return response()->json(['message' => 'Failed to save footer.', 'error' => $e->getMessage()], 500);
    //     }
    // }

    public function save(Request $request)
{
    try {
        // Validate the request data
        $validated = $request->validate([
            'image' => 'required|string',
        ]);

        // Extract and decode the base64 image data
        $data = $validated['image'];
        $data = str_replace('data:image/png;base64,', '', $data);
        $data = base64_decode($data);

        // Define the file path in the storage directory
        $filePath = 'headers/' . uniqid() . '.png';

        // Ensure the storage path exists
        $storagePath = storage_path('app/public/headers');
        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        // Save the image to the specified path
        file_put_contents($storagePath . '/' . basename($filePath), $data);

        // Check if a record already exists for the authenticated user
        $headerFooter = HeaderFooter::where('user_id', auth()->id())->first();

        if ($headerFooter) {
            // If a record exists, update it
            $headerFooter->header_path = $filePath;
        } else {
            // If no record exists, create a new one
            $headerFooter = new HeaderFooter();
            $headerFooter->user_id = auth()->id();
            $headerFooter->header_path = $filePath;
        }

        // Save the record to the database
        $headerFooter->save();

        // Return a successful response
        return response()->json(['message' => 'Header saved successfully!']);
    } catch (\Exception $e) {
        // Log the detailed error message
        \Log::error('Error saving header:', ['exception' => $e]);

        // Return a failure response with detailed error message
        return response()->json(['message' => 'Failed to save header.', 'error' => $e->getMessage()], 500);
    }
}

public function saveFooter(Request $request)
{
    try {
        // Validate the request data
        $validated = $request->validate([
            'image' => 'required|string',
        ]);

        // Extract and decode the base64 image data
        $data = $validated['image'];
        $data = str_replace('data:image/png;base64,', '', $data);
        $data = base64_decode($data);

        // Define the file path in the storage directory
        $filePath = 'footers/' . uniqid() . '.png';

        // Ensure the storage path exists
        $storagePath = storage_path('app/public/footers');
        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        // Save the image to the specified path
        file_put_contents($storagePath . '/' . basename($filePath), $data);

        // Check if a record already exists for the authenticated user
        $headerFooter = HeaderFooter::where('user_id', auth()->id())->first();

        if ($headerFooter) {
            // If a record exists, update it
            $headerFooter->footer_path = $filePath;
        } else {
            // If no record exists, create a new one
            $headerFooter = new HeaderFooter();
            $headerFooter->user_id = auth()->id();
            $headerFooter->footer_path = $filePath;
        }

        // Save the record to the database
        $headerFooter->save();

        // Return a successful response
        return response()->json(['message' => 'Footer saved successfully!']);
    } catch (\Exception $e) {
        // Log the detailed error message
        \Log::error('Error saving footer:', ['exception' => $e]);

        // Return a failure response with detailed error message
        return response()->json(['message' => 'Failed to save footer.', 'error' => $e->getMessage()], 500);
    }
}

    
}
