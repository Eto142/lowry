<?php

namespace App\Http\Controllers\User;

use App\Models\Balance;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Console\View\Components\Alert;

class WithdrawalController extends Controller
{
    public function index()
    {
        $withdrawals = Auth::user()->withdrawals()->latest()->paginate(10);
        return view('user.withdrawal', compact('withdrawals'));
    }

    public function requestWithdrawal(Request $request)
    {
        $request->validate([
            'method' => 'required|string',
            'amount' => 'required|numeric|min:10',
        ]);

        $user = Auth::user();
        $balance = $user->balance;

        // Check if user has verified payment methods for this method
        $verifiedMethod = $user->verifiedPaymentMethods()
            ->where('method', $request->method)
            ->first();

        if (!$verifiedMethod) {
            return response()->json([
                'message' => 'You need a verified payment method for this withdrawal method'
            ], 422);
        }

        // Check sufficient balance
        if ($balance->amount < $request->amount) {
            return response()->json([
                'message' => 'Insufficient balance for this withdrawal'
            ], 422);
        }

        // Deduct from balance
        $balance->decrement('amount', $request->amount);

        // Create withdrawal
        $withdrawal = $user->withdrawals()->create([
            'method' => $request->method,
            'amount' => $request->amount,
            'status' => 'pending',
            'payment_method_id' => $verifiedMethod->id,
        ]);

        return response()->json([
            'message' => 'Withdrawal request submitted successfully. Admin will process it shortly.',
            'new_balance' => number_format($balance->fresh()->amount, 2),
            'withdrawal' => $withdrawal
        ]);
    }
}
