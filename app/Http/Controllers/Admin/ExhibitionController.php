<?php

namespace App\Http\Controllers\Admin;

use Log;
use App\Models\Exhibition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

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

    // Store new exhibition
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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


            // Handle file upload
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move('uploads/exhibitions/', $filename);
                $validated['picture'] = 'uploads/exhibitions/' . $filename;
            }

            // Set default values for checkboxes if not provided
            $validated['show_seller_contact'] = $request->has('show_seller_contact');
            $validated['show_buyer_contact'] = $request->has('show_buyer_contact');
            $validated['is_featured'] = $request->has('is_featured');

            // Create the exhibition
            $exhibition = Exhibition::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Exhibition created successfully!',
                'data' => $exhibition
            ], 201); // 201 Created status code

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating exhibition: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating exhibition. Please try again.'
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
                'current_picture' => 'nullable|string', // Hidden field with current picture path
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

            // Handle file upload
            if ($request->hasFile('picture')) {
                // Delete old picture if it exists
                if ($exhibition->picture && file_exists(public_path($exhibition->picture))) {
                    unlink(public_path($exhibition->picture));
                }

                $file = $request->file('picture');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move('uploads/exhibitions/', $filename);
                $validated['picture'] = 'uploads/exhibitions/' . $filename;
            } else {
                // Keep the current picture if no new one is uploaded
                $validated['picture'] = $request->input('current_picture');
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
        // Delete associated image
        if ($exhibition->picture) {
            Storage::delete('public/' . $exhibition->picture);
        }

        $exhibition->delete();

        return response()->json([
            'success' => true,
            'message' => 'Exhibition item deleted successfully!'
        ]);
    }
}
