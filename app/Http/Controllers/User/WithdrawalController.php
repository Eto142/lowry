<?php

namespace App\Http\Controllers\User;

use App\Models\Balance;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Console\View\Components\Alert;

class WithdrawalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $balance = Balance::firstOrCreate(['user_id' => $user->id], ['amount' => 0]);

        return view('user.withdrawal.home', [
            'balance' => $balance->amount,
            'withdrawals' => Withdrawal::where('user_id', $user->id)->latest()->get()
        ]);
    }

    public function requestWithdrawal(Request $request)
    {
        $request->validate([
            'method' => ['required', Rule::in(['bank', 'cashapp', 'crypto'])],
            'amount' => ['required', 'numeric', 'min:10'],
            'crypto_type' => ['nullable', 'required_if:method,crypto', Rule::in(['bitcoin', 'ethereum', 'usdt', 'usdc'])]
        ]);

        $user = Auth::user();
        $balance = Balance::firstOrCreate(['user_id' => $user->id], ['amount' => 0]);

        // Check sufficient balance
        if ($balance->amount < $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance for withdrawal'
            ], 422);
        }

        // Verify account is linked
        if (!$this->isAccountLinked($user, $request->method)) {
            return response()->json([
                'success' => false,
                'message' => ucfirst($request->method) . ' account is not linked'
            ], 422);
        }

        // Create withdrawal record
        $withdrawal = Withdrawal::create([
            'user_id' => $user->id,
            'method' => $request->method,
            'amount' => $request->amount,
            'account_details' => $this->getAccountDetails($user, $request->method, $request->crypto_type),
            'is_linked' => true,
            'status' => 'pending'
        ]);

        // Deduct from balance (uncomment when ready)
        // $balance->decrement('amount', $request->amount);

        return response()->json([
            'success' => true,
            'message' => 'Withdrawal request submitted successfully!',
            'new_balance' => number_format($balance->amount, 2)
        ]);
    }

    public function linkAccount(Request $request)
    {
        $validated = $request->validate([
            'method' => ['required', Rule::in(['bank', 'cashapp', 'crypto'])],
            'bank_name' => ['required_if:method,bank', 'nullable', 'string', 'max:255'],
            'account_number' => ['required_if:method,bank', 'nullable', 'string', 'max:50'],
            'account_name' => ['required_if:method,bank', 'nullable', 'string', 'max:255'],
            'cashapp_id' => ['required_if:method,cashapp', 'nullable', 'string', 'max:50'],
            'wallet_address' => ['required_if:method,crypto', 'nullable', 'string', 'max:255'],
            'network' => ['required_if:method,crypto', 'nullable', 'string', 'max:50']
        ]);

        $user = Auth::user();
        $updateData = [];

        switch ($validated['method']) {
            case 'bank':
                $updateData = [
                    'bank_account_linked' => true,
                    'bank_name' => $validated['bank_name'],
                    'bank_account_number' => $validated['account_number'],
                    'bank_account_name' => $validated['account_name']
                ];
                break;

            case 'cashapp':
                $updateData = [
                    'cashapp_linked' => true,
                    'cashapp_id' => $validated['cashapp_id']
                ];
                break;

            case 'crypto':
                $updateData = [
                    'crypto_wallet_linked' => true,
                    'crypto_wallet_address' => $validated['wallet_address'],
                    'crypto_network' => $validated['network']
                ];
                break;
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => ucfirst($validated['method']) . ' account linked successfully!'
        ]);
    }

    private function isAccountLinked($user, $method)
    {
        return match ($method) {
            'bank' => $user->bank_account_linked,
            'cashapp' => $user->cashapp_linked,
            'crypto' => $user->crypto_wallet_linked,
            default => false,
        };
    }

    private function getAccountDetails($user, $method, $cryptoType = null)
    {
        switch ($method) {
            case 'bank':
                return json_encode([
                    'bank_name' => $user->bank_name,
                    'account_number' => $user->bank_account_number,
                    'account_name' => $user->bank_account_name
                ]);
            case 'cashapp':
                return json_encode([
                    'cashapp_id' => $user->cashapp_id
                ]);
            case 'crypto':
                return json_encode([
                    'wallet_address' => $user->crypto_wallet_address,
                    'network' => $user->crypto_network,
                    'type' => $cryptoType
                ]);
            default:
                return null;
        }
    }
}
