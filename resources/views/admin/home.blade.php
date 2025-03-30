@include('admin.header')

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Square button styling */
    .square-btn {
        height: 100%;
        width: 100%;
        min-height: 120px;
        padding: 15px 10px;
        border-radius: 8px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .square-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .square-btn .btn-label {
        font-size: 0.9rem;
        font-weight: 500;
        text-align: center;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .square-btn {
            min-height: 100px;
            padding: 10px 5px;
        }

        .square-btn i {
            font-size: 1.5rem !important;
            margin-bottom: 5px !important;
        }

        .square-btn .btn-label {
            font-size: 0.8rem;
        }
    }
</style>
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title text-dark"><i class="fas fa-tachometer-alt mr-2"></i>Dashboard Overview</h4>
            </div>

            <!-- Stats Cards Row -->
            <div class="row">
                <!-- New Users Card -->
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-primary card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">New Users</p>
                                        <h4 class="card-title">{{ $newUsersCount }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- New Exhibitions Card -->
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-info card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-paint-brush"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">New Exhibitions</p>
                                        <h4 class="card-title">{{ $newExhibitionsCount }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Exhibitions Card -->
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-success card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-images"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Total Exhibitions</p>
                                        <h4 class="card-title">{{ $totalExhibitionsCount }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Users Card -->
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-secondary card-round">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-user-friends"></i>
                                    </div>
                                </div>
                                <div class="col-7 col-stats">
                                    <div class="numbers">
                                        <p class="card-category">Total Users</p>
                                        <h4 class="card-title">{{ $totalUsersCount }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Quick Actions Row -->
            <div class="row">
                <div class="col-md-12 mb-3">

                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-dark">Quick Actions</h4>
                        </div>
                        <div class="card">
                            <div class="row">
                                <div class="col-sm-6 col-md-3 mb-3">
                                    <a href="{{ route('admin.users.index') }}"
                                        class="btn btn-primary btn-block square-btn">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-users fa-2x mb-2"></i>
                                            <span class="btn-label">Manage Users</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6 col-md-3 mb-3">
                                    <a href="{{ route('admin.exhibitions.index') }}"
                                        class="btn btn-info btn-block square-btn">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-paint-brush fa-2x mb-2"></i>
                                            <span class="btn-label">Manage Exhibitions</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6 col-md-3 mb-3">
                                    <a href="{{ route('admin.withdrawals.index') }}"
                                        class="btn btn-warning btn-block square-btn">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                            <span class="btn-label">Withdrawal Requests</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6 col-md-3 mb-3">
                                    <a href="{{ route('admin.settings') }}"
                                        class="btn btn-secondary btn-block square-btn">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-cog fa-2x mb-2"></i>
                                            <span class="btn-label">System Settings</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Charts Row -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-head-row">
                                <h4 class="card-title text-dark">User Registration Analytics</h4>
                                <div class="card-tools">
                                    <select class="form-control form-control-sm" id="analyticsPeriod">
                                        <option value="week">Last Week</option>
                                        <option value="month" selected>Last Month</option>
                                        <option value="year">Last Year</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="userAnalyticsChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title text-dark">Exhibition Status</h4>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="exhibitionStatusChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Recent Activity Row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-head-row">
                                <h4 class="card-title text-dark">Recent Users</h4>
                                <div class="card-tools">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">View
                                        All</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentUsers as $user)
                                        <tr>
                                            <td>
                                                <div class="avatar-sm float-left mr-3">
                                                    <span class="avatar-title rounded-circle bg-primary text-white">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                                {{ $user->name }}
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if($user->user_status)
                                                <span class="badge badge-success">Active</span>
                                                @else
                                                <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-head-row">
                                <h4 class="card-title text-dark">Recent Withdrawals</h4>
                                <div class="card-tools">
                                    <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-sm btn-warning">View
                                        All</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentWithdrawals as $withdrawal)
                                        <tr>
                                            <td>{{ $withdrawal->user->name ?? 'N/A' }}</td>
                                            <td>${{ number_format($withdrawal->amount, 2) }}</td>
                                            <td>
                                                @if($withdrawal->status == 'completed')
                                                <span class="badge badge-success">Completed</span>
                                                @elseif($withdrawal->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                                @else
                                                <span class="badge badge-danger">Rejected</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
    // User Analytics Chart
    const userCtx = document.getElementById('userAnalyticsChart').getContext('2d');
    const userChart = new Chart(userCtx, {
        type: 'line',
        data: {
            labels: @json($userAnalytics['labels']),
            datasets: [{
                label: 'User Registrations',
                data: @json($userAnalytics['data']),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Exhibition Status Chart
    const exhibitionCtx = document.getElementById('exhibitionStatusChart').getContext('2d');
    const exhibitionChart = new Chart(exhibitionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Available', 'Sold', 'Reserved'],
            datasets: [{
                data: @json($exhibitionStatusData),
                backgroundColor: [
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(255, 206, 86, 0.7)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Period selector change
    $('#analyticsPeriod').change(function() {
        const period = $(this).val();
        
        $.ajax({
            url: "{{ route('admin.dashboard.analytics') }}",
            type: "GET",
            data: { period: period },
            success: function(response) {
                userChart.data.labels = response.labels;
                userChart.data.datasets[0].data = response.data;
                userChart.update();
            }
        });
    });
});
</script>

@include('admin.footer')