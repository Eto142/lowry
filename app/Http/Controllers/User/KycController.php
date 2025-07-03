<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\KycVerification;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;

class KycController extends Controller
{
    protected $cloudinary;
    protected $uploadApi;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => [
                'secure' => true
            ]
        ]);
        $this->uploadApi = new UploadApi();
    }

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
            // Store documents in Cloudinary
            $frontPhotoUrl = null;
            $frontPhotoPublicId = null;
            $backPhotoUrl = null;
            $backPhotoPublicId = null;

            // Handle front ID upload
            if ($request->hasFile('front_id')) {
                $uploadResult = $this->uploadApi->upload($request->file('front_id')->getRealPath(), [
                    'folder' => 'kyc_documents',
                    'resource_type' => 'auto',
                    'quality' => 'auto:best'
                ]);

                $frontPhotoUrl = $uploadResult['secure_url'];
                $frontPhotoPublicId = $uploadResult['public_id'];
            }

            // Handle back ID upload
            if ($request->hasFile('back_id')) {
                $uploadResult = $this->uploadApi->upload($request->file('back_id')->getRealPath(), [
                    'folder' => 'kyc_documents',
                    'resource_type' => 'auto',
                    'quality' => 'auto:best'
                ]);

                $backPhotoUrl = $uploadResult['secure_url'];
                $backPhotoPublicId = $uploadResult['public_id'];
            }

            // Create new KYC record
            KycVerification::create([
                'user_id' => $user->id,
                'id_type' => $request->id_type,
                'front_document_path' => $frontPhotoUrl,
                'front_document_public_id' => $frontPhotoPublicId,
                'back_document_path' => $backPhotoUrl,
                'back_document_public_id' => $backPhotoPublicId,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'KYC documents submitted successfully!'
            ]);
        } catch (\Exception $e) {
            // Clean up uploaded files if error occurs
            if (isset($frontPhotoPublicId)) {
                try {
                    $this->uploadApi->destroy($frontPhotoPublicId);
                } catch (\Exception $cleanupException) {
                    Log::error('Failed to cleanup front document: ' . $cleanupException->getMessage());
                }
            }

            if (isset($backPhotoPublicId)) {
                try {
                    $this->uploadApi->destroy($backPhotoPublicId);
                } catch (\Exception $cleanupException) {
                    Log::error('Failed to cleanup back document: ' . $cleanupException->getMessage());
                }
            }

            Log::error('KYC Submission Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit KYC documents. Please try again.'
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
}
