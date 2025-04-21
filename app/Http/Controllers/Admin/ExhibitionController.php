<?php

namespace App\Http\Controllers\Admin;


use App\Models\Exhibition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class ExhibitionController extends Controller
{
    // List all exhibitions
    public function index()
    {
        $exhibitions = Exhibition::latest()->paginate(10);
        return view('admin.manage_exhibitions', compact('exhibitions'));
    }

    public function view(Exhibition $exhibition)
    {
        return view('admin.view_exhibition', compact('exhibition'));
    }

    // Show create form
    public function create()
    {
        return view('admin.create_exhibition');
    }


    public function store(Request $request)
    {
        try {
            // Validate the request data with custom messages
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'seller_name' => 'required|string|max:255',
                'seller_email' => 'nullable|email|max:255',
                'seller_phone' => 'nullable|string|max:20',
                'seller_address' => 'nullable|string|max:500',
                'show_seller_contact' => 'sometimes|boolean',
                'buyer_name' => 'nullable|required_if:exhibition_status,sold|string|max:255',
                'buyer_email' => 'nullable|required_if:exhibition_status,sold|email|max:255',
                'buyer_phone' => 'nullable|required_if:exhibition_status,sold|string|max:20',
                'buyer_address' => 'nullable|required_if:exhibition_status,sold|string|max:500',
                'show_buyer_contact' => 'sometimes|boolean',
                'exhibition_status' => 'required|in:pending,available,sold,reserved',
                'exhibition_type' => 'required|in:past,current,future',
                'amount_sold' => 'nullable|required_if:exhibition_status,sold|numeric|min:0',
                'date' => 'required|date',
                'is_featured' => 'sometimes|boolean',
                'admin_id' => 'required|exists:admins,id'
            ]);

            if ($request->hasFile('picture')) {
                $uploadedFile = Cloudinary::upload(
                    $request->file('picture')->getRealPath(),
                    [
                        'folder' => 'exhibitions',
                        'transformation' => [
                            'width' => 800,
                            'height' => 600,
                            'crop' => 'limit',
                        ],
                    ]
                );

                $validated['picture_url'] = $uploadedFile->getSecurePath();
                $validated['picture_public_id'] = $uploadedFile->getPublicId();
            }


            // Set default values for checkboxes
            $validated['show_seller_contact'] = $request->has('show_seller_contact');
            $validated['show_buyer_contact'] = $request->has('show_buyer_contact');
            $validated['is_featured'] = $request->has('is_featured');

            // Create the exhibition
            $exhibition = Exhibition::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Exhibition created successfully!',
                'data' => $exhibition
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Exhibition Creation Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->except('picture') // Don't log file data
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error creating exhibition: ' . $e->getMessage()
            ], 500);
        }
    }

    // Show single exhibition
    public function show(Exhibition $exhibition)
    {
        return view('admin.edit_exhibition', compact('exhibition'));
    }

    // Show edit form
    public function edit(Exhibition $exhibition)
    {
        return view('admin.update_exhibition', compact('exhibition'));
    }

    // Update exhibition (AJAX)
    public function update(Request $request, Exhibition $exhibition)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'seller_name' => 'required|string|max:255',
                'seller_email' => 'nullable|email|max:255',
                'seller_phone' => 'nullable|string|max:20',
                'seller_address' => 'nullable|string|max:500',
                'show_seller_contact' => 'sometimes|boolean',
                'buyer_name' => 'nullable|string|max:255',
                'buyer_email' => 'nullable|email|max:255',
                'buyer_phone' => 'nullable|string|max:20',
                'buyer_address' => 'nullable|string|max:500',
                'show_buyer_contact' => 'sometimes|boolean',
                'exhibition_status' => 'required|in:pending,available,sold,reserved',
                'exhibition_type' => 'required|in:past,current,future',
                'amount_sold' => 'nullable|numeric|min:0',
                'date' => 'required|date',
                'is_featured' => 'sometimes|boolean',
                'admin_id' => 'required|exists:admins,id'
            ]);

            // Handle file upload to Cloudinary
            if ($request->hasFile('picture')) {
                // Delete old picture from Cloudinary if it exists
                if ($exhibition->picture_public_id) {
                    Cloudinary::destroy($exhibition->picture_public_id);
                }

                // Upload new picture
                $cloudinaryImage = $request->file('picture')->storeOnCloudinary('exhibitions');
                $validated['picture_url'] = $cloudinaryImage->getSecurePath();
                $validated['picture_public_id'] = $cloudinaryImage->getPublicId();
            }

            // Set default values for checkboxes if not provided
            $validated['show_seller_contact'] = $request->has('show_seller_contact');
            $validated['show_buyer_contact'] = $request->has('show_buyer_contact');
            $validated['is_featured'] = $request->has('is_featured');

            // Update the exhibition
            $exhibition->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Exhibition updated successfully!',
                'data' => $exhibition
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating exhibition: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating exhibition. Please try again.'
            ], 500);
        }
    }

    // Update status only (AJAX)
    public function updateStatus(Request $request, Exhibition $exhibition)
    {
        $request->validate([
            'exhibition_status' => 'required|in:available,sold,reserved',
        ]);

        $exhibition->update(['exhibition_status' => $request->exhibition_status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'new_status' => $exhibition->exhibition_status
        ]);
    }

    // Delete exhibition (AJAX)
    public function destroy(Exhibition $exhibition)
    {
        try {
            // Delete associated image from Cloudinary if it exists
            if ($exhibition->picture_public_id) {
                Cloudinary::destroy($exhibition->picture_public_id);
            }

            $exhibition->delete();

            return response()->json([
                'success' => true,
                'message' => 'Exhibition item deleted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting exhibition: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting exhibition. Please try again.'
            ], 500);
        }
    }
}
