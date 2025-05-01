<?php

namespace App\Http\Controllers\User;

use App\Models\Exhibition;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use App\Models\FutureExhibition;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Cloudinary\Api\Upload\UploadApi;

class ExhibitionController extends Controller
{
    public function create()
    {
        return view('user.create-exhibition');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'seller_name' => 'required|string|max:255',
            'seller_email' => 'nullable|email|max:255',
            'seller_phone' => 'nullable|string|max:20',
            'date' => 'required|date',
            'amount_sold' => 'required|numeric|min:0'
        ]);

        try {
            // Upload to Cloudinary
            $cloudinary = new Cloudinary();
            $uploader = new UploadApi();

            $uploadResult = $uploader->upload($request->file('picture')->getRealPath(), [
                'folder' => 'exhibitions',
                'transformation' => [
                    'width' => 800,
                    'height' => 600,
                    'crop' => 'limit'
                ]
            ]);

            $exhibition = Exhibition::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'description' => $validated['description'],
                'picture_url' => $uploadResult['secure_url'],
                'picture_public_id' => $uploadResult['public_id'],
                'seller_name' => $validated['seller_name'],
                'seller_email' => $validated['seller_email'],
                'seller_phone' => $validated['seller_phone'],
                'date' => $validated['date'],
                'amount_sold' => $validated['amount_sold'],
                'exhibition_status' => 'pending'
            ]);

            return redirect()->route('user.exhibitions.manage')
                ->with('success', 'Exhibition created successfully!');
        } catch (\Exception $e) {
            Log::error('Exhibition creation failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to create exhibition. Please try again.');
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
        //$this->authorize('update', $exhibition);
        return view('user.exhibition.edit_exhibition', compact('exhibition'));
    }

    public function update(Request $request, Exhibition $exhibition)
    {
        //$this->authorize('update', $exhibition);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'picture' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'seller_name' => 'required|string|max:255',
            'seller_email' => 'nullable|email|max:255',
            'seller_phone' => 'nullable|string|max:20',
            'date' => 'required|date',
            'amount_sold' => 'required|numeric|min:0',
            'exhibition_status' => 'required|in:pending,available,sold'
        ]);

        try {
            // Handle new picture upload
            if ($request->hasFile('picture')) {
                $cloudinary = new Cloudinary();
                $uploader = new UploadApi();

                // Delete old image if exists
                if ($exhibition->picture_public_id) {
                    $uploader->destroy($exhibition->picture_public_id);
                }

                // Upload new image
                $uploadResult = $uploader->upload($request->file('picture')->getRealPath(), [
                    'folder' => 'exhibitions',
                    'transformation' => [
                        'width' => 800,
                        'height' => 600,
                        'crop' => 'limit'
                    ]
                ]);

                $validated['picture_url'] = $uploadResult['secure_url'];
                $validated['picture_public_id'] = $uploadResult['public_id'];
            }

            $exhibition->update($validated);

            return redirect()->route('user.manage.exhibitions')
                ->with('success', 'Exhibition updated successfully!');
        } catch (\Exception $e) {
            Log::error('Exhibition update failed: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Failed to update exhibition. Please try again.');
        }
    }

    public function destroy(Exhibition $exhibition)
    {
        // $this->authorize('delete', $exhibition);

        try {
            // Delete image from Cloudinary if exists
            if ($exhibition->picture_public_id) {
                $cloudinary = new Cloudinary();
                $uploader = new UploadApi();
                $uploader->destroy($exhibition->picture_public_id);
            }

            $exhibition->delete();

            return redirect()->route('user.exhibitions.manage')
                ->with('success', 'Exhibition deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Exhibition deletion failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete exhibition. Please try again.');
        }
    }
}
