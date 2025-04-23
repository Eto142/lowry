<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Balance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransactionNotificationMail;

class BalanceController extends Controller
{
    public function updateTotalBalance(Request $request)
    {
        $holdingBalance = Balance::firstOrCreate(['user_id' => $request->user_id]);

        // Determine transaction type
        $transactionType = $request->type === 'credit' ? 'Credit' : 'Debit';

        // Update balance
        if ($request->type === 'credit') {
            $holdingBalance->increment('amount', $request->amount);
        } else {
            $holdingBalance->decrement('amount', $request->amount);
        }

        // Send transaction email
        $this->sendTransactionEmail(
            $request->user_id, // User ID
            $transactionType,  // Transaction type (Credit/Debit)
            $request->amount,  // Amount
            'Total Balance' // Transaction category
        );

        return redirect()->back()->with('success', 'Total balance updated successfully.');
    }



    // Send transaction email
    protected function sendTransactionEmail($userId, $transactionType, $amount, $transactionCategory)
    {
        // Find the user
        $user = User::find($userId);

        if ($user) {
            // Prepare the email details
            $name = $user->name;
            $date = now()->toDateTimeString();

            // Send the email with individual arguments
            Mail::to($user->email)->send(new TransactionNotificationMail(
                $name, // User's name
                $amount, // Transaction amount
                $transactionCategory, // Transaction category (e.g., Holding Balance)
                $transactionType, // Transaction type (Credit/Debit)
                $date // Transaction date
            ));
        }
    }
}
