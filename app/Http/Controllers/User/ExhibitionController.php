<?php

namespace App\Http\Controllers\User;

use App\Models\Exhibition;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use App\Models\FutureExhibition;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExhibitionController extends Controller
{

    public function create()
    {
        return view('user.create-exhibition');
    }
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'seller_name' => 'required|string|max:255',
                'seller_email' => 'nullable|email|max:255',
                'seller_phone' => 'nullable|string|max:20',
                'date' => 'required|date',
                'amount_sold' => 'nullable|numeric|min:0'
            ]);

            // Handle file upload to Cloudinary
            if ($request->hasFile('picture')) {
                $cloudinary = new Cloudinary();
                $uploadApi = $cloudinary->uploadApi();

                $uploadResult = $uploadApi->upload(
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

                $validated['picture_url'] = $uploadResult['secure_url'] ?? null;
                $validated['picture_public_id'] = $uploadResult['public_id'] ?? null;
            }

            // Set the user ID
            $validated['user_id'] = Auth::id();
            $validated['exhibition_status'] = 'pending'; // Default status

            // Create the exhibition
            $exhibition = Exhibition::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Exhibition created successfully!',
                'data' => $exhibition
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating exhibition: ' . $e->getMessage()
            ], 500);
        }
    }


    public function viewExhibitions()
    {

        $exhibitions = Exhibition::where('exhibition_status', 'available')
            ->orderBy('date', 'desc')
            ->paginate(9);

        return view('user.exhibitions', compact('exhibitions'));
    }

    public function currentExhibitions()
    {

        $exhibitions = FutureExhibition::where('type', 'current')
            ->orderBy('exhibition_date', 'asc')
            ->get();

        return view('user.current_exhibitions', compact('exhibitions'));
    }

    public function futureExhibitions()
    {



        $exhibitions = FutureExhibition::where('type', 'future')
            ->orderBy('exhibition_date', 'asc')
            ->get();

        return view('user.future_exhibitions', compact('exhibitions'));
    }

    public function showExhibition(Exhibition $exhibition)
    {
        return view('user.show_exhibition', compact('exhibition'));
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'exhibition_id' => 'required|exists:exhibitions,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500'
        ]);

        try {
            $exhibition = Exhibition::findOrFail($request->exhibition_id);

            // Update exhibition with buyer info
            $exhibition->update([
                'buyer_name' => $request->name,
                'buyer_email' => $request->email,
                'buyer_phone' => $request->phone,
                'buyer_address' => $request->address,
                'exhibition_status' => 'sold'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Purchase completed successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing purchase: ' . $e->getMessage()
            ], 500);
        }
    }

    public function manageExhibition()
    {
        $exhibitions = Exhibition::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('user.exhibition.manage_exhibitions', compact('exhibitions'));
    }

    public function edit(Exhibition $exhibition)
    {

        return view('exhibitions.form', compact('exhibition'));
    }

    public function update(Request $request, Exhibition $exhibition)
    {
        // $this->authorize('update', $exhibition);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'picture' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date' => 'required|date',
            'amount_sold' => 'required|numeric|min:0',
            'exhibition_type' => 'required|in:past,current,future',
            'exhibition_status' => 'required|in:pending,available,sold,reserved',
            'is_featured' => 'sometimes|boolean',
            'seller_name' => 'required|string|max:255',
            'seller_email' => 'nullable|email|max:255',
            'seller_phone' => 'nullable|string|max:20',
            'seller_address' => 'nullable|string|max:500',
            'show_seller_contact' => 'sometimes|boolean',
        ]);

        try {
            // Handle file upload if new picture provided
            if ($request->hasFile('picture')) {
                // Delete old picture
                if ($exhibition->picture) {
                    $oldPath = str_replace('storage/', '', $exhibition->picture);
                    Storage::disk('public')->delete($oldPath);
                }

                // Store new picture
                $path = $request->file('picture')->store('exhibitions', 'public');
                $validated['picture'] = 'storage/' . $path;
            }

            $exhibition->update($validated);

            return redirect()->route('exhibitions.manage')
                ->with('success', 'Exhibition updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating exhibition: ' . $e->getMessage());
        }
    }

    public function destroy(Exhibition $exhibition)
    {
        // $this->authorize('delete', $exhibition);

        try {
            // Delete picture file
            if ($exhibition->picture) {
                $path = str_replace('storage/', '', $exhibition->picture);
                Storage::disk('public')->delete($path);
            }

            $exhibition->delete();

            return redirect()->route('exhibitions.manage')
                ->with('success', 'Exhibition deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting exhibition: ' . $e->getMessage());
        }
    }
}
