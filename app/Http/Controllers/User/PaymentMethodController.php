<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PaymentMethodController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'method' => 'required|in:bank,cashapp,crypto',
            'account_info' => 'required|string|max:500',
        ]);

        $paymentMethod = Auth::user()->paymentMethods()->create([
            'method' => $request->method,
            'account_info' => $request->account_info,
            'is_verified' => false,
        ]);

        return response()->json([
            'message' => 'Payment method submitted for verification. Admin will review it shortly.',
            'paymentMethod' => $paymentMethod
        ]);
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->user_id !== Auth::id()) {
            abort(403);
        }

        if ($paymentMethod->is_verified) {
            return response()->json(['message' => 'Cannot delete verified payment methods'], 422);
        }

        $paymentMethod->delete();

        return response()->json(['message' => 'Payment method deleted successfully']);
    }
}
