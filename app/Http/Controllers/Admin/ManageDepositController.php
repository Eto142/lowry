<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ManageDepositController extends Controller
{
    /**
     * Display a listing of deposits.
     */
    public function index()
    {
        $deposits = Deposit::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.manage_deposit', compact('deposits'));
    }

    /**
     * Approve a deposit.
     */
    public function approve(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:deposits,id'
        ]);

        DB::beginTransaction();

        try {
            $deposit = Deposit::findOrFail($request->id);

            // Only process if deposit is pending
            if ($deposit->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Deposit is not in pending status.'
                ], 400);
            }

            // Update deposit status
            $deposit->status = 'approved';
            $deposit->save();

            // Update user balance
            $user = User::find($deposit->user_id);
            $user->balance->amount += $deposit->amount;
            $user->balance->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Deposit approved successfully.',
                'deposit' => $deposit
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve deposit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Decline a deposit.
     */
    public function decline(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:deposits,id'
        ]);

        try {
            $deposit = Deposit::findOrFail($request->id);

            // Only process if deposit is pending
            if ($deposit->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Deposit is not in pending status.'
                ], 400);
            }

            $deposit->status = 'declined';
            $deposit->save();

            return response()->json([
                'success' => true,
                'message' => 'Deposit declined successfully.',
                'deposit' => $deposit
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to decline deposit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a deposit.
     */
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:deposits,id'
        ]);

        try {
            $deposit = Deposit::findOrFail($request->id);

            // Prevent deletion of approved deposits
            if ($deposit->status === 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Approved deposits cannot be deleted.'
                ], 400);
            }

            $deposit->delete();

            return response()->json([
                'success' => true,
                'message' => 'Deposit deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete deposit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get deposit details for editing.
     */
    public function get(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:deposits,id'
        ]);

        try {
            $deposit = Deposit::findOrFail($request->id);

            return response()->json([
                'success' => true,
                'deposit' => $deposit
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch deposit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update deposit details.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:deposits,id',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|max:10',
            'crypto_type' => 'nullable|string|max:50',
            'wallet_address' => 'nullable|string|max:255',
            'transaction_hash' => 'nullable|string|max:255',
            'status' => 'required|in:pending,approved,declined'
        ]);

        DB::beginTransaction();

        try {
            $deposit = Deposit::findOrFail($request->id);
            $originalStatus = $deposit->status;
            $originalAmount = $deposit->amount;
            $user = User::find($deposit->user_id);

            // Update deposit fields
            $deposit->amount = $request->amount;
            $deposit->currency = $request->currency;
            $deposit->crypto_type = $request->crypto_type;
            $deposit->wallet_address = $request->wallet_address;
            $deposit->transaction_hash = $request->transaction_hash;
            $deposit->status = $request->status;
            $deposit->save();

            // Handle balance adjustments if status changed from/to approved
            if ($user && $user->balance) {
                // If changing from approved to something else, deduct the amount
                if ($originalStatus === 'approved' && $request->status !== 'approved') {
                    $user->balance->amount -= $originalAmount;
                    $user->balance->save();
                }
                // If changing to approved from something else, add the amount
                elseif ($request->status === 'approved' && $originalStatus !== 'approved') {
                    $user->balance->amount += $request->amount;
                    $user->balance->save();
                }
                // If amount changed and status is approved, adjust the difference
                elseif ($request->status === 'approved' && $originalAmount != $request->amount) {
                    $difference = $request->amount - $originalAmount;
                    $user->balance->amount += $difference;
                    $user->balance->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Deposit updated successfully.',
                'deposit' => $deposit
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update deposit: ' . $e->getMessage()
            ], 500);
        }
    }
}
