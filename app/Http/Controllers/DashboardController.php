<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('user.home'); // Load the dashboard view
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



}


