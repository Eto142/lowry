<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\DB;
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
        // Check honeypot field
        if (!empty($data['honeypot'])) {
            abort(403, 'Bot detected!');
        }

        // Check timestamp (submission must take at least 3 seconds)
        if (isset($data['timestamp']) && (now()->timestamp - $data['timestamp']) < 3) {
            abort(403, 'Submission too fast!');
        }
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'password' => 'required|string|min:8|confirmed',
            'referral_code' => 'nullable|string|exists:users,referral_code',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Generate verification code
            $verificationCode = rand(1000, 9999);

            // Create new user
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'country' => $request->country,
                'verification_code' => $verificationCode,
                'verification_expiry' => now()->addMinutes(10),
                'password' => Hash::make($request->password),
                'referral_code' => Str::random(8), // Generate a referral code for the new user
            ]);

            // Handle referral if exists
            if ($request->has('referral_code')) {
                $referrer = User::where('referral_code', $request->referral_code)->first();
                if ($referrer) {
                    $user->update(['referred_by' => $referrer->id]);

                    // You can add referral bonus logic here if needed
                    // Example: $referrer->userBalance()->increment('amount', 10);
                }
            }

            // Create related balances for the user
            $user->userBalance()->create(['amount' => 0]);

            // Prepare and send verification email
            $full_name = $request->first_name . ' ' . $request->last_name;
            $vmessage = "
                <p style='line-height: 24px;margin-bottom:15px;'>
                    Hello $full_name,
                </p>
                <br>
                <p>
                We are so happy to have you on board, and thank you for joining us.
                </p>
                <p>
                We just need to verify your email address before you can access our platform.
                </p>
                <br>
                <p>
                Use this code to verify your email: <strong>$verificationCode</strong>
                </p>
                <p style='color: red;'>
                Please note that this code will expire in 10 minutes.
                </p>
                <br>
                <p>
                Don't hesitate to get in touch if you have any questions; we'll always get back to you.
                </p>
            ";

            // Send the email
            Mail::to($user->email)->send(new VerificationEmail($vmessage));

            Auth::login($user);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Registration successful!',
                'redirect_url' => route('email_verify'), // Redirect to verification notice page
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during registration. Please try again.'
            ], 500);
        }
    }
}
