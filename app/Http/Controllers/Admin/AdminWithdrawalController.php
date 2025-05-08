<?php

namespace App\Http\Controllers\Admin;

use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminWithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $query = Withdrawal::with(['user', 'paymentMethod'])
            ->latest();

        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->paginate(10);

        return view('admin.manage_withdrawal', compact('withdrawals'));
    }

    public function process(Withdrawal $withdrawal)
    {
        if ($withdrawal->status != 'pending') {
            return back()->with('error', 'Withdrawal must be pending to process');
        }

        $withdrawal->update(['status' => 'processing']);

        // Notify user
        // $withdrawal->user->notify(new WithdrawalProcessed($withdrawal));

        return back()->with('message', 'Withdrawal marked as processing');
    }

    public function complete(Withdrawal $withdrawal)
    {
        if ($withdrawal->status != 'processing') {
            return back()->with('error', 'Withdrawal must be processing to complete');
        }

        $withdrawal->update(['status' => 'completed']);

        // Notify user
        // $withdrawal->user->notify(new WithdrawalCompleted($withdrawal));

        return back()->with('message', 'Withdrawal marked as completed');
    }

    public function reject(Withdrawal $withdrawal)
    {
        if ($withdrawal->status == 'failed' || $withdrawal->status == 'completed') {
            return back()->with('error', 'Cannot reject withdrawal in current status');
        }

        // Refund amount
        $withdrawal->user->balance->increment('amount', $withdrawal->amount);
        $withdrawal->update(['status' => 'failed']);

        // Notify user
        // $withdrawal->user->notify(new WithdrawalRejected($withdrawal));

        return back()->with('message', 'Withdrawal rejected and amount refunded');
    }
}
