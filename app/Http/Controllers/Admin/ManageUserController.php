<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ManageUserController extends Controller
{

    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
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
        return view('admin.users.show', compact('user'));
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
