@include('admin.header')

<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            @if(session('message'))
            <div class="alert alert-success mb-2">{{ session('message') }}</div>
            @endif
            <div class="mt-2 mb-4">
                <h1 class="title1 text-dark">Pending Payment Methods</h1>
            </div>

            <div class="mb-5 row">
                <div class="col-md-12 shadow card p-4 bg-light">
                    <div class="table-responsive">
                        <table class="table table-hover text-dark">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Method</th>
                                    <th>Account Info</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($methods as $method)
                                <tr>
                                    <td>{{ $method->id }}</td>
                                    <td>
                                        {{ $method->user->name }}<br>
                                        <small>{{ $method->user->email }}</small>
                                    </td>
                                    <td>{{ ucfirst($method->method) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info view-details"
                                            data-details="{{ $method->account_info }}">
                                            View Details
                                        </button>
                                    </td>
                                    <td>{{ $method->created_at->diffForHumans() }}</td>
                                    <td>
                                        <form method="POST"
                                            action="{{ route('admin.payment-methods.verify', $method) }}"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Verify</button>
                                        </form>
                                        <form method="POST"
                                            action="{{ route('admin.payment-methods.reject', $method) }}"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $methods->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Account Details</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <pre id="detailsContent" class="p-3 bg-white rounded"></pre>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.view-details').click(function() {
            const details = $(this).data('details');
            $('#detailsContent').text(JSON.stringify(JSON.parse(details), null, 2);
            $('#detailsModal').modal('show');
        });
    });
</script>

@include('admin.footer')