<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bid;
use App\Models\User;
use App\Models\Exhibition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ManageBidController extends Controller
{
    /**
     * Display a listing of the bids.
     */
    public function index()
    {
        $bids = Bid::with(['exhibition', 'user'])->latest()->get();
        $exhibitions = Exhibition::active()->get();
        $users = User::where('status', 'active')->get();

        return view('admin.bids.index', compact('bids', 'exhibitions', 'users'));
    }

    /**
     * Show the form for creating a new bid.
     */
    public function create()
    {
        // Handled in the index method
    }

    /**
     * Store a newly created bid in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exhibition_id' => 'required|exists:exhibitions,id',
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $bid = Bid::create([
                'exhibition_id' => $request->exhibition_id,
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bid created successfully!',
                'bid' => $bid,
                'exhibition' => $bid->exhibition,
                'user' => $bid->user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create bid: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified bid.
     */
    public function edit(Bid $bid)
    {
        return response()->json([
            'success' => true,
            'bid' => $bid
        ]);
    }

    /**
     * Update the specified bid in storage.
     */
    public function update(Request $request, Bid $bid)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'status' => 'required|in:pending,approved,rejected'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $bid->update([
                'amount' => $request->amount,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bid updated successfully!',
                'bid' => $bid
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update bid: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve the specified bid.
     */
    public function approve(Bid $bid)
    {
        try {
            $bid->update(['status' => 'approved']);

            return response()->json([
                'success' => true,
                'message' => 'Bid approved successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve bid: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified bid from storage.
     */
    public function destroy(Bid $bid)
    {
        try {
            $bid->delete();

            return response()->json([
                'success' => true,
                'message' => 'Bid deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete bid: ' . $e->getMessage()
            ], 500);
        }
    }
}
