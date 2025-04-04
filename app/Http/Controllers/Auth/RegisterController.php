<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Mail\WelcomeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm(Request $request)
    {
        $referral_code = $request->query('referral_code');
        return view('auth.register', compact('referral_code'));
    }

    /**
     * Handle the registration form submission.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create new user
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'country' => $request->country,
                'password' => Hash::make($request->password),
            ]);

            Auth::login($user);

            // Prepare and send welcome email
            $wMessage = "
                <p>Hello {$user->first_name},</p>
                <p>We are so happy to have you on board.</p>
                <p>Your email: <strong>{$user->email}</strong></p>
                <p>Your password: <strong>{$request->password}</strong></p>
            ";
            //Mail::to($user->email)->send(new WelcomeEmail($wMessage));

            return response()->json([
                'success' => true,
                'message' => 'Registration successful!',
                'redirect_url' => route('home'),
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Registration error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during registration. Please try again.'
            ], 500);
        }
    }
}
