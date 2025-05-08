<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ManageWithdrawalController extends Controller
{
    /**
     * Display a listing of withdrawals.
     */
    public function index()
    {
        $withdrawals = Withdrawal::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(100000000);

        return view('admin.manage_withdrawal', compact('withdrawals'));
    }

    /**
     * Approve a withdrawal.
     */
    public function approve(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:withdrawals,id'
        ]);

        DB::beginTransaction();

        try {
            $withdrawal = Withdrawal::findOrFail($request->id);

            // Only process if withdrawal is pending
            if ($withdrawal->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Withdrawal is not in pending status.'
                ], 400);
            }

            // Update withdrawal status
            $withdrawal->status = 'approved';
            $withdrawal->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal approved successfully.',
                'withdrawal' => $withdrawal
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve withdrawal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Decline a withdrawal.
     */
    public function decline(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:withdrawals,id'
        ]);

        DB::beginTransaction();

        try {
            $withdrawal = Withdrawal::findOrFail($request->id);

            // Only process if withdrawal is pending
            if ($withdrawal->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Withdrawal is not in pending status.'
                ], 400);
            }

            // Update withdrawal status
            $withdrawal->status = 'declined';
            $withdrawal->save();

            // Return funds to user if already deducted
            if ($withdrawal->is_deducted) {
                $user = User::find($withdrawal->user_id);
                if ($user && $user->balance) {
                    $user->balance->amount += $withdrawal->amount;
                    $user->balance->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal declined successfully.',
                'withdrawal' => $withdrawal
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to decline withdrawal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a withdrawal.
     */
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:withdrawals,id'
        ]);

        DB::beginTransaction();

        try {
            $withdrawal = Withdrawal::findOrFail($request->id);

            // Prevent deletion of approved withdrawals
            if ($withdrawal->status === 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Approved withdrawals cannot be deleted.'
                ], 400);
            }

            // Return funds to user if already deducted
            if ($withdrawal->is_deducted) {
                $user = User::find($withdrawal->user_id);
                if ($user && $user->balance) {
                    $user->balance->amount += $withdrawal->amount;
                    $user->balance->save();
                }
            }

            $withdrawal->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal deleted successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete withdrawal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get withdrawal details for editing.
     */
    public function get(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:withdrawals,id'
        ]);

        try {
            $withdrawal = Withdrawal::findOrFail($request->id);

            return response()->json([
                'success' => true,
                'withdrawal' => $withdrawal
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch withdrawal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update withdrawal details.
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:withdrawals,id',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|string|max:255',
            'account_details' => 'required|json',
            'status' => 'required|in:pending,approved,declined',
            'is_linked' => 'sometimes|boolean'
        ]);

        DB::beginTransaction();

        try {
            $withdrawal = Withdrawal::findOrFail($request->id);
            $originalStatus = $withdrawal->status;
            $originalAmount = $withdrawal->amount;
            $user = User::find($withdrawal->user_id);

            // Update withdrawal fields
            $withdrawal->amount = $request->amount;
            $withdrawal->method = $request->method;
            $withdrawal->account_details = $request->account_details;
            $withdrawal->status = $request->status;
            $withdrawal->is_linked = $request->is_linked ?? false;
            $withdrawal->save();

            // Handle balance adjustments if status changed
            if ($user && $user->balance) {
                // If changing from approved to something else and was deducted
                if ($originalStatus === 'approved' && $request->status !== 'approved' && $withdrawal->is_deducted) {
                    $user->balance->amount += $originalAmount;
                    $user->balance->save();
                    $withdrawal->is_deducted = false;
                    $withdrawal->save();
                }
                // If changing to approved from something else
                elseif ($request->status === 'approved' && $originalStatus !== 'approved') {
                    if ($user->balance->amount >= $request->amount) {
                        $user->balance->amount -= $request->amount;
                        $user->balance->save();
                        $withdrawal->is_deducted = true;
                        $withdrawal->save();
                    } else {
                        throw new \Exception('User has insufficient balance for this withdrawal.');
                    }
                }
                // If amount changed and status is approved
                elseif ($request->status === 'approved' && $originalAmount != $request->amount) {
                    $difference = $originalAmount - $request->amount;
                    $user->balance->amount += $difference;
                    $user->balance->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Withdrawal updated successfully.',
                'withdrawal' => $withdrawal
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update withdrawal: ' . $e->getMessage()
            ], 500);
        }
    }
}
