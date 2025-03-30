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
            'seller_name' => 'nullable|string|max:255',
            'buyer_name' => 'nullable|string|max:255',
            'exhibition_status' => 'required|in:available,sold,reserved',
            'amount_sold' => 'nullable|numeric|min:0',
            'date' => 'nullable|date',
        ]);

        // Handle file upload if new image is provided

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('uploads/exhibitions/', $filename);
            $validated['picture'] = 'uploads/exhibitions/' . $filename;
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
