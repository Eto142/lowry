<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\KycVerification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManageKycController extends Controller
{
    /**
     * Display a listing of KYC verifications.
     */
    public function index()
    {
        $kycs = KycVerification::with(['user', 'verifier'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.manage_kyc', compact('kycs'));
    }

    /**
     * Approve a KYC verification.
     */
    public function approve(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:kyc_verifications,id'
        ]);

        try {
            $kyc = KycVerification::findOrFail($request->id);

            // Only process if KYC is pending
            if (!$kyc->isPending()) {
                return response()->json([
                    'success' => false,
                    'message' => 'KYC verification is not in pending status.'
                ], 400);
            }

            // Update KYC status
            $kyc->status = 'approved';
            $kyc->verified_at = now();
            $kyc->verified_by = Auth::id();
            $kyc->rejection_reason = null;
            $kyc->save();

            // Update user's KYC status
            $user = $kyc->user;
            $user->kyc_verified = true;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'KYC verification approved successfully.',
                'kyc' => $kyc->load('verifier')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve KYC verification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a KYC verification.
     */
    public function reject(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:kyc_verifications,id',
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            $kyc = KycVerification::findOrFail($request->id);

            // Only process if KYC is pending
            if (!$kyc->isPending()) {
                return response()->json([
                    'success' => false,
                    'message' => 'KYC verification is not in pending status.'
                ], 400);
            }

            // Update KYC status
            $kyc->status = 'rejected';
            $kyc->verified_at = now();
            $kyc->verified_by = Auth::id();
            $kyc->rejection_reason = $request->rejection_reason;
            $kyc->save();

            // Update user's KYC status
            $user = $kyc->user;
            $user->kyc_verified = false;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'KYC verification rejected successfully.',
                'kyc' => $kyc->load('verifier')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject KYC verification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get KYC verification details.
     */
    public function get(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:kyc_verifications,id'
        ]);

        try {
            $kyc = KycVerification::with(['user', 'verifier'])
                ->findOrFail($request->id);

            return response()->json([
                'success' => true,
                'kyc' => $kyc
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch KYC verification: ' . $e->getMessage()
            ], 500);
        }
    }
}
