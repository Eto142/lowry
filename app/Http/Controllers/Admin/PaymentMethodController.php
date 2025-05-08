<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;

class PaymentMethodController extends Controller
{

    public function pending()
    {
        $methods = PaymentMethod::with('user')
            ->where('is_verified', false)
            ->latest()
            ->paginate(10);

        return view('admin.manage_payment', compact('methods'));
    }

    public function verify(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update(['is_verified' => true]);

        // Notify user
        // $paymentMethod->user->notify(new PaymentMethodVerified($paymentMethod));

        return redirect()->route('admin.payment-methods.pending')
            ->with('message', 'Payment method verified successfully');
    }

    public function reject(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        // Notify user
        // $paymentMethod->user->notify(new PaymentMethodRejected($paymentMethod));

        return redirect()->route('admin.payment-methods.pending')
            ->with('message', 'Payment method rejected and deleted');
    }
}
