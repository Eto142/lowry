<?php

namespace App\Http\Controllers\User;


use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\KycVerification;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{
    public function showForm()
    {
        return view('kyc.form');
    }

    public function getStatus()
    {
        $kyc = Auth::user()->kycVerification;
        return view('user.status', compact('kyc'))->render();
    }

    public function submit(Request $request)
    {
        $request->validate([
            'id_type' => 'required|in:passport,driver_license,national_id',
            'front_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'back_id' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $user = Auth::user();

        // Check KYC status
        $existingKyc = KycVerification::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingKyc) {
            $message = $existingKyc->status === 'approved'
                ? 'Your KYC has already been approved.'
                : 'You already have a KYC submission under review.';

            return response()->json([
                'success' => false,
                'message' => $message
            ], 422);
        }

        try {
            // Store documents
            // Handle front ID upload
            $frontPhotoPath = null;
            if ($request->hasFile('front_id')) {
                $frontPhoto = $request->file('front_id');
                $frontFilename = 'front_' . time() . '.' . $frontPhoto->getClientOriginalExtension();
                $destinationPath = public_path('uploads/kyc_documents/');
                $frontPhoto->move($destinationPath, $frontFilename);
                $frontPhotoPath = 'uploads/kyc_documents/' . $frontFilename;
            }

            // Handle back ID upload
            $backPhotoPath = null;
            if ($request->hasFile('back_id')) {
                $backPhoto = $request->file('back_id');
                $backFilename = 'back_' . time() . '.' . $backPhoto->getClientOriginalExtension();
                $destinationPath = public_path('uploads/kyc_documents/');
                $backPhoto->move($destinationPath, $backFilename);
                $backPhotoPath = 'uploads/kyc_documents/' . $backFilename;
            }

            // Create new KYC record
            KycVerification::create([
                'user_id' => $user->id,
                'id_type' => $request->id_type,
                'front_document_path' => $frontPhotoPath,
                'back_document_path' => $backPhotoPath,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'KYC documents submitted successfully!'
            ]);
        } catch (\Exception $e) {
            // Clean up uploaded files if error occurs
            if (isset($frontPhotoPath) && file_exists(public_path($frontPhotoPath))) {
                @unlink(public_path($frontPhotoPath));
            }
            if (isset($backPhotoPath) && file_exists(public_path($backPhotoPath))) {
                @unlink(public_path($backPhotoPath));
            }

            Log::error('KYC Submission Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit KYC documents. Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Admin methods (unchanged from previous implementation)
    public function index()
    {
        $verifications = KycVerification::with('user')->latest()->paginate(10);
        return view('admin.kyc.index', compact('verifications'));
    }

    public function approve($id)
    {
        $verification = KycVerification::findOrFail($id);
        $verification->update(['status' => 'approved', 'rejection_reason' => null]);

        return response()->json([
            'success' => true,
            'message' => 'KYC approved successfully!'
        ]);
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:255']);

        $verification = KycVerification::findOrFail($id);
        $verification->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason
        ]);

        return response()->json([
            'success' => true,
            'message' => 'KYC rejected successfully!'
        ]);
    }

    private function storeDocument($file, $userId)
    {
        $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $path = "kyc_docs/user_{$userId}/{$fileName}";

        Storage::put($path, file_get_contents($file));

        return $path;
    }
}
