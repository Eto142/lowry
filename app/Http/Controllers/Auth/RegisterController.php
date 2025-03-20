<?php

namespace App\Http\Controllers\Auth;

use DB;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\User\ReferralBalance;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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
        $referral_code = $request->query('referral_code'); // Get referral code from URL
        return view('auth.register', compact('referral_code')); // Pass referral code to the view
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
            'currency' => 'required|string|max:10',
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'password' => 'required|string|min:4|confirmed',
            'referral_code' => 'nullable|string|exists:users,referral_code', // Validate referral code
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // Find the referrer if a valid referral code is provided
        $referrer = null;
        if ($request->referral_code) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
        }

        // Create the user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'currency' => $request->currency,
            'country' => $request->country,
            'city' => $request->city,
            'plain' => $request->password, // Encrypt the plain password
            'user_status' => 1, // Assuming 1 means active
            'verification_code' => rand(1000, 9999), // Generate a random verification code
            'verification_expiry' => now()->addMinutes(10), // Set expiry time for verification code
            'password' => Hash::make($request->password), // Hash the password
            'referral_code' => $this->generateReferralCode(), // Generate a unique referral code for the new user
            'referred_by' => $referrer ? $referrer->id : null, // Set referred_by if referrer exists
        ]);

        // Create related balances for the user
        $user->holdingBalance()->create([
            'user_id' => $user->id,
            'amount' => 0,
        ]);

        $user->stakingBalance()->create([
            'user_id' => $user->id,
            'amount' => 0,
        ]);

        $user->tradingBalance()->create([
            'user_id' => $user->id,
            'amount' => 0,
        ]);

        // Add referral bonus to the referrer's balance
        if ($referrer) {
            $referrer->referralBalance()->updateOrCreate(
                ['user_id' => $referrer->id],
                ['amount' => DB::raw('amount + 10')] // Add $10 as referral bonus
            );
        }

        // Log in the user
        auth()->login($user);

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'Registration successful!',
            'redirect' => route('email_verify'), // Redirect to email verification page
        ]);
    }

    /**
     * Generate a unique referral code.
     *
     * @return string
     */
    protected function generateReferralCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8)); // Generate an 8-character code
        } while (User::where('referral_code', $code)->exists()); // Ensure the code is unique

        return $code;
    }
}
