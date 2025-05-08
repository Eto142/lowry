@include('admin.header')

<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            @if(session('message'))
            <div class="alert alert-success mb-2">{{ session('message') }}</div>
            @endif
            <div class="mt-2 mb-4">
                <h1 class="title1 text-dark">Withdrawal Management</h1>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <div class="btn-group" role="group">
                            <a href="?status=all"
                                class="btn btn-secondary {{ request('status') == 'all' || !request('status') ? 'active' : '' }}">All</a>
                            <a href="?status=pending"
                                class="btn btn-warning {{ request('status') == 'pending' ? 'active' : '' }}">Pending</a>
                            <a href="?status=processing"
                                class="btn btn-primary {{ request('status') == 'processing' ? 'active' : '' }}">Processing</a>
                            <a href="?status=completed"
                                class="btn btn-success {{ request('status') == 'completed' ? 'active' : '' }}">Completed</a>
                            <a href="?status=failed"
                                class="btn btn-danger {{ request('status') == 'failed' ? 'active' : '' }}">Failed</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-5 row">
                <div class="col-md-12 shadow card p-4 bg-light">
                    <div class="table-responsive">
                        <table class="table table-hover text-dark">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($withdrawals as $withdrawal)
                                <tr>
                                    <td>{{ $withdrawal->id }}</td>
                                    <td>
                                        {{ $withdrawal->user->name }}<br>
                                        <small>{{ $withdrawal->user->email }}</small>
                                    </td>
                                    <td>${{ number_format($withdrawal->amount, 2) }}</td>
                                    <td>{{ ucfirst($withdrawal->method) }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($withdrawal->status == 'completed') badge-success 
                                            @elseif($withdrawal->status == 'pending') badge-warning 
                                            @elseif($withdrawal->status == 'processing') badge-primary
                                            @else badge-danger @endif">
                                            {{ ucfirst($withdrawal->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $withdrawal->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                @if($withdrawal->status == 'pending')
                                                <form method="POST"
                                                    action="{{ route('admin.withdrawals.process', $withdrawal) }}">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">Mark as
                                                        Processing</button>
                                                </form>
                                                @endif
                                                @if($withdrawal->status == 'processing')
                                                <form method="POST"
                                                    action="{{ route('admin.withdrawals.complete', $withdrawal) }}">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">Mark as
                                                        Completed</button>
                                                </form>
                                                @endif
                                                @if($withdrawal->status != 'failed')
                                                <form method="POST"
                                                    action="{{ route('admin.withdrawals.reject', $withdrawal) }}">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">Reject</button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $withdrawals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.footer')