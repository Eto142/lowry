<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin;
use App\Models\Balance;
use App\Models\Deposit;
use App\Models\Exhibition;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminController extends Controller
{


    public function index()
    {
        // User Statistics
        $newUsersCount = User::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $totalUsersCount = User::count();

        // Exhibition Statistics
        $newExhibitionsCount = Exhibition::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $totalExhibitionsCount = Exhibition::count();

        // Exhibition Status Breakdown
        $exhibitionStatusData = [
            Exhibition::where('exhibition_status', 'available')->count(),
            Exhibition::where('exhibition_status', 'sold')->count(),
            Exhibition::where('exhibition_status', 'reserved')->count(),
        ];

        // User Analytics (Default: Last Month)
        $userAnalytics = $this->getUserAnalytics('month');

        // Recent Activity
        $recentUsers = User::latest()->take(5)->get();
        $recentWithdrawals = Exhibition::with('user')->latest()->take(5)->get();

        return view('admin.home', compact(
            'newUsersCount',
            'totalUsersCount',
            'newExhibitionsCount',
            'totalExhibitionsCount',
            'exhibitionStatusData',
            'userAnalytics',
            'recentUsers',
            'recentWithdrawals'
        ));
    }

    public function getAnalytics(Request $request)
    {
        $period = $request->period ?? 'month';
        $analytics = $this->getUserAnalytics($period);

        return response()->json([
            'labels' => $analytics['labels'],
            'data' => $analytics['data']
        ]);
    }

    private function getUserAnalytics($period)
    {
        $endDate = Carbon::now();
        $labels = [];
        $data = [];

        switch ($period) {
            case 'week':
                $startDate = Carbon::now()->subWeek();
                for ($date = $startDate; $date <= $endDate; $date->addDay()) {
                    $labels[] = $date->format('D');
                    $data[] = User::whereDate('created_at', $date)->count();
                }
                break;

            case 'month':
                $startDate = Carbon::now()->subMonth();
                for ($date = $startDate; $date <= $endDate; $date->addDay()) {
                    $labels[] = $date->format('M d');
                    $data[] = User::whereDate('created_at', $date)->count();
                }
                break;

            case 'year':
                $startDate = Carbon::now()->subYear();
                for ($date = $startDate; $date <= $endDate; $date->addMonth()) {
                    $labels[] = $date->format('M Y');
                    $data[] = User::whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->count();
                }
                break;
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }


    public function impersonate(User $user)
    {
        // Store the original user's ID in the session (if not already stored)
        if (!session()->has('impersonate')) {
            session()->put('impersonate', Auth::id());
        }
        $data['user'] = $user;

        // Impersonate the specified user
        Auth::loginUsingId($user->id);

        // Get deposits and sums for the impersonated user
        $data['holdingBalance'] = Balance::where('user_id', $user->id)->sum('amount') ?? 0;

        // Redirect to the user's home page with the relevant data
        return view('user.home', $data)->with('message', 'You are logged in as ' . $user->first_name . ' ' . $user->last_name);
    }


    public function leaveImpersonate(User $user)
    {
        // Check if the session has an 'impersonate' value
        if (session()->has('impersonate')) {
            // Retrieve the original user's ID from the session
            $originalUserId = session()->get('impersonate');

            // Log in as the original user
            Auth::loginUsingId($originalUserId);

            // Forget the impersonation session data
            session()->forget('impersonate');


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


            // Redirect to the original user's dashboard or home page
            return redirect()->route('admin.home', $data)->with('message', 'You have returned to your original account.');
        }

        // If no impersonation is happening, redirect to home
        return redirect()->route('admin.home')->with('message', 'No impersonation found.');
    }


    public function showChangePasswordForm()
    {
        return view('admin.change_password'); // This should match your blade file name
    }

    public function changePassword(Request $request, Admin $admin)
    {
        try {
            // Verify the authenticated admin is changing their own password
            if (auth('admin')->id() !== $admin->id) {
                throw new \Exception('Unauthorized action. You can only change your own password.');
            }

            $request->validate([
                'current_password' => ['required', 'current_password:admin'],
                'new_password' => [
                    'required',
                    'confirmed',
                    Password::min(4)

                ],
            ]);

            // Update the password
            $admin->update([
                'password' => Hash::make($request->new_password)
            ]);

            Log::info("Admin ID {$admin->id} changed their password successfully.");

            return response()->json([
                'type' => 'success',
                'message' => 'Password changed successfully!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $firstError = collect($errors)->first()[0];

            Log::error('Password change validation failed: ' . $firstError);

            return response()->json([
                'type' => 'validation_error',
                'message' => $firstError,
                'errors' => $errors
            ], 422);
        } catch (\Exception $e) {
            Log::error('Password change error for Admin ID ' . $admin->id . ': ' . $e->getMessage());

            return response()->json([
                'type' => 'server_error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
