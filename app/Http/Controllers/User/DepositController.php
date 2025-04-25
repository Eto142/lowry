<?php

namespace App\Http\Controllers\User;

use App\Models\Deposit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function create()
    {
        return view('user.deposit.home');
    }

    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'amount' => 'required|numeric|min:1',
                'crypto_type' => 'required|string|in:USDT (TRC20),ETHEREUM (ERC20),BTC',
                'transaction_hash' => 'required|string|max:255'
            ]);

            // Get wallet address based on crypto type
            $walletAddresses = [
                'USDT (TRC20)' => 'TH8Zgx34Qtv9fwD9yBYSBZ8qDHxWHQWrkK',
                'ETHEREUM (ERC20)' => 'TH8Zgx34Qtv9fwD9yBYSBZ8qDHxWHQWrkK',
                'BTC' => '1BVZQTG5MrAtFzajkB4tT14cNNxAQfeqrw'
            ];

            // Create the deposit
            $deposit = Deposit::create([
                'user_id' => Auth::id(),
                'amount' => $validated['amount'],
                'crypto_type' => $validated['crypto_type'],
                'wallet_address' => $walletAddresses[$validated['crypto_type']],
                'transaction_hash' => $validated['transaction_hash'],
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Deposit submitted successfully. We will review it shortly.'
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
                'message' => 'Error submitting deposit: ' . $e->getMessage()
            ], 500);
        }
    }
}
