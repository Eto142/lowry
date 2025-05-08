<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Balance;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Mail\sendUserEmail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ManageUserController extends Controller
{

    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::with('balance')->latest()->paginate(10000000000);
        return view('admin.manage_users', compact('users'));
    }


    /**
     * Get users via AJAX for datatable
     */
    public function getUsers(Request $request)
    {
        $perPage = $request->num ?? 10;
        $search = $request->search ?? '';
        $order = $request->order ?? 'desc';

        $users = User::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        })
            ->orderBy('created_at', $order)
            ->paginate($perPage);

        return response()->json([
            'status' => 200,
            'data' => view('admin.users.partials.table_rows', compact('users'))->render()
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')
            ->with('message', 'User created successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $userId = $user->id;

        $data['user'] = $user;

        // Fetch deposits for the user
        $data['deposits'] = Deposit::where('user_id', $userId)->get();

        // Sum of pending deposits
        $data['pending_deposits_sum'] = Deposit::where('user_id', $userId)
            ->where('status', 'pending')
            ->sum('amount');

        // Sum of successful deposits
        $data['successful_deposits_sum'] = Deposit::where('user_id', $userId)
            ->where('status', 'successful')
            ->sum('amount');

        // Sum of pending withdrawals
        $data['pending_withdrawals_sum'] = Withdrawal::where('user_id', $userId)
            ->where('status', 'pending')
            ->sum('amount');

        // Sum of successful withdrawals
        $data['successful_withdrawals_sum'] = Withdrawal::where('user_id', $userId)
            ->where('status', 'successful')
            ->sum('amount');

        // Sum of holding balance
        $data['total_balance'] = Balance::where('user_id', $userId)
            ->sum('amount');

        return view('admin.user.show', $data);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $referrers = User::whereNotNull('referral_code')->where('id', '!=', $id)->get();
        return view('admin.user.edit', compact('user', 'referrers'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone_number' => 'nullable|string|max:20',
            'country' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:3',
            'referred_by' => 'nullable|exists:users,id',
            'user_status' => 'required|in:active,inactive,banned',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email_verification' => 'boolean',
            'id_verification' => 'boolean',
            'address_verification' => 'boolean',
            'signal_strength' => 'required|integer|between:1,100',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except('profile_photo', 'password');

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }



            $user->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully!',
                'redirect' => route('admin.users.edit', ['user' => $user->id])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Toggle user status.
     */
    public function toggleUserStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|boolean',
        ]);

        $user = User::findOrFail($request->user_id);
        $newStatus = !$request->status;
        $user->update(['user_status' => $newStatus]);

        return response()->json([
            'success' => true,
            'new_status' => $newStatus,
            'message' => 'User status updated successfully!'
        ]);
    }

    /**
     * Toggle email verification status.
     */
    public function toggleEmailStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|boolean',
        ]);

        $user = User::findOrFail($request->user_id);
        $newStatus = !$request->status;
        $user->update(['email_status' => $newStatus]);

        return response()->json([
            'success' => true,
            'new_status' => $newStatus,
            'message' => 'Email status updated successfully!'
        ]);
    }



    public function sendMail(Request $request)
    {
        // Validate the request input
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $message = $request->message;

        // Prepare the data for the email (escaping any HTML tags for safety)
        $data = "<p>" . e($message) . "</p>";

        $subject = $request->subject;

        // Send the email using the SendUserEmail mailable
        Mail::to($request->email)->send(new sendUserEmail($data, $subject));

        // Redirect back with a success message
        return back()->with('status', 'Email successfully sent!');
    }

    /**
     * Send email to all users.
     */
    public function sendMassEmail(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Implementation for sending mass email
        // This would typically use Laravel's Mail facade or a job queue

        return redirect()->back()
            ->with('message', 'Emails are being sent to all users!');
    }
}
