<?php

namespace App\Http\Controllers\User;

use App\Models\Deposit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{

    public function ShowDeposit()
    {
        return view('user.deposit.home'); // Load the deposit view
    }
    public function create()
    {
        return view('user.deposit.home');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'crypto_type' => 'required|string|in:USDT (TRC20),ETHEREUM (ERC20),BTC',
            'transaction_hash' => 'required|string|max:255'
        ]);

        try {
            // Get the appropriate wallet address based on crypto type
            $walletAddresses = [
                'USDT (TRC20)' => 'TH8Zgx34Qtv9fwD9yBYSBZ8qDHxWHQWrkK',
                'ETHEREUM (ERC20)' => 'TH8Zgx34Qtv9fwD9yBYSBZ8qDHxWHQWrkK',
                'BTC' => '1BVZQTG5MrAtFzajkB4tT14cNNxAQfeqrw'
            ];

            $deposit = Deposit::create([
                'user_id' => Auth::id(),
                'amount' => $request->amount,
                'crypto_type' => $request->crypto_type,
                'wallet_address' => $walletAddresses[$request->crypto_type],
                'transaction_hash' => $request->transaction_hash,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Deposit submitted successfully. We will review it shortly.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting deposit. Please try again.'
            ], 500);
        }
    }

    private function getWalletAddress($cryptoType)
    {
        $wallets = [
            'USDT (TRC20)' => 'TH8Zgx34Qtv9fwD9yBYSBZ8qDHxWHQWrkK',
            'ETHEREUM (ERC20)' => 'TH8Zgx34Qtv9fwD9yBYSBZ8qDHxWHQWrkK',
            'BTC' => '1BVZQTG5MrAtFzajkB4tT14cNNxAQfeqrw'
        ];

        return $wallets[$cryptoType] ?? '';
    }
}
