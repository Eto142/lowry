<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Exhibition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
}
