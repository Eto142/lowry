<?php

namespace App\Http\Controllers\Admin;

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
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'seller_name' => 'nullable|string|max:255',
                'buyer_name' => 'nullable|string|max:255',
                'exhibition_status' => 'required|in:available,sold,reserved',
                'amount_sold' => 'nullable|numeric|min:0',
                'date' => 'nullable|date',
                'admin_id' => 'required|exists:admins,id',
            ]);

            // Handle file upload
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move('uploads/exhibitions/', $filename);
                $validated['picture'] = 'uploads/exhibitions/' . $filename;
            }

            $exhibition = Exhibition::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Exhibition added successfully!',
                'data' => $exhibition
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e instanceof \Illuminate\Validation\ValidationException
                    ? $e->errors()
                    : ['general' => [$e->getMessage()]]
            ], $e instanceof \Illuminate\Validation\ValidationException ? 422 : 500);
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_url' => 'nullable|url|max:255',
            'seller_name' => 'nullable|string|max:255',
            'buyer_name' => 'nullable|string|max:255',
            'exhibition_status' => 'required|in:pending,available,sold,reserved',
            'amount_sold' => 'nullable|numeric|min:0',
            'date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'venue' => 'nullable|string|max:255',
            'artist_email' => 'nullable|email|max:255',
            'is_featured' => 'nullable|boolean',
        ]);

        // Handle file upload if new image is provided
        if ($request->hasFile('picture')) {
            // Delete old image if it exists
            if ($exhibition->picture && file_exists(public_path($exhibition->picture))) {
                unlink(public_path($exhibition->picture));
            }

            $file = $request->file('picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('uploads/exhibitions/', $filename);
            $validated['picture'] = 'uploads/exhibitions/' . $filename;
        }

        // Convert checkbox value to boolean
        $validated['is_featured'] = $request->has('is_featured');

        // if ($request->hasFile('picture')) {
        //     $file = $request->file('picture');
        //     $filename = time() . '_' . $file->getClientOriginalName();
        //     $file->move('uploads/exhibitions/', $filename);
        //     $validated['picture'] = 'uploads/exhibitions/' . $filename;
        // }

        // If image_url is provided and picture is not being uploaded, clear the picture field
        if ($request->filled('image_url') && !$request->hasFile('picture')) {
            if ($exhibition->picture && file_exists(public_path($exhibition->picture))) {
                unlink(public_path($exhibition->picture));
            }
            $validated['picture'] = null;
        }

        $exhibition->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Exhibition item updated successfully!'
        ]);
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
