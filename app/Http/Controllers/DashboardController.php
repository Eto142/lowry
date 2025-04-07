<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('user.home'); // Load the dashboard view
    }

    public function ShowDeposit()
    {
        return view('user.deposit.home'); // Load the deposit view
    }

    public function ShowWithdrawal()
    {
        return view('user.withdrawal.home'); // Load the withdrawal view
    }

    public function Addexhibition()
    {
        return view('user.create-exhibition'); // Load the exhibition view
    }


    
     // update password
    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if old password matches
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'The old password is incorrect.']);
        }

        // Update the password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

// update user profile
    public function profileUpdate(Request $request)
    {
        //validation rules

        $request->validate([
            'first_name' => 'string',
            'last_name' => 'string',
            'phone_number' => 'string',
            'country' => 'string',
            'address' => 'string'

        ]);
        $user = Auth::user();
        $user->first_name = $request['first_name'];
        $user->last_name = $request['last_name'];
        $user->phone_number = $request['phone_number'];
        $user->country = $request['country'];
        $user->address = $request['address'];


        $user->update();
        return back()->with('status', 'Profile Updated');
    }

   // update kyc details
    public function submitKYC(Request $request)
{
    $request->validate([
        'id_type' => 'required|string',
        'front_id' => 'required|image|mimes:jpeg,png,jpg',
        'back_id' => 'required|image|mimes:jpeg,png,jpg',
    ]);

    $user = Auth::user(); // Get logged-in user

    // Store uploaded files
    $frontPath = $request->file('front_id')->store('kyc_documents', 'public');
    $backPath = $request->file('back_id')->store('kyc_documents', 'public');

    // Update user KYC data
    $user->update([
        'id_type' => $request->id_type,
        'front_id' => $frontPath,
        'back_id' => $backPath,
        'kyc_status' => 0, // Mark as verified
    ]);

    return redirect()->back()->with('success', 'KYC submitted successfully');
}


}


