<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        try {
            // Validate email input
            $validator = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ], 422);
                } else {
                    return back()->withErrors($validator)->withInput();
                }
            }

            // Check if the user exists
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                $error = ['email' => ['We couldn’t find a user with that email address.']];
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $error
                    ], 422);
                } else {
                    return back()->withErrors($error)->withInput();
                }
            }

            // Send password reset link
            $response = Password::broker()->sendResetLink(
                $request->only('email')
            );

            if ($response === Password::RESET_LINK_SENT) {
                $successMessage = 'Password reset link sent to your email!';
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => $successMessage
                    ]);
                } else {
                    return back()->with('status', $successMessage);
                }
            }

            // If the reset link was not sent
            $error = ['email' => ['Unable to send reset link. Please try again later.']];
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $error
                ], 422);
            } else {
                return back()->withErrors($error)->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Password reset error: ' . $e->getMessage(), [
                'exception' => $e,
                'email' => $request->email,
                'ip' => $request->ip()
            ]);

            $errorMessage = 'A system error occurred. Please try again later.';
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            } else {
                return back()->withErrors(['email' => $errorMessage])->withInput();
            }
        }
    }
}
