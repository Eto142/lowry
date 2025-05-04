@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            @if(session('message'))
            <div class="alert alert-success mb-2">{{session('message')}}</div>
            @endif
            <div class="mt-2 mb-4">
                <h1 class="title1 text-dark">Withdrawal Management</h1>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-secondary filter-btn" data-status="all">All</button>
                            <button type="button" class="btn btn-success filter-btn"
                                data-status="approved">Approved</button>
                            <button type="button" class="btn btn-warning filter-btn"
                                data-status="pending">Pending</button>
                            <button type="button" class="btn btn-danger filter-btn"
                                data-status="declined">Declined</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-5 row">
                <div class="col-md-12 shadow card p-4 bg-light">
                    <div class="row">
                        <div class="col-12">
                            <form class="form-inline">
                                <div class="">
                                    <select class="form-control bg-light text-dark" id="numofrecord">
                                        <option>10</option>
                                        <option>20</option>
                                        <option>30</option>
                                        <option>40</option>
                                        <option>50</option>
                                        <option>100</option>
                                    </select>
                                </div>
                                <div>
                                    <input type="text" id="searchInput" placeholder="Search by user, amount or method"
                                        class="float-right mb-2 mr-sm-2 form-control bg-light text-dark">
                                    <small id="errorsearch"></small>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover text-dark" id="withdrawalTable">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Account Details</th>
                                    <th>Linked</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="withdrawalslisttbl">
                                @foreach($withdrawals as $index => $withdrawal)
                                <tr id="withdrawal-row-{{ $withdrawal->id }}"
                                    data-status="{{ strtolower($withdrawal->status) }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center;">
                                            <div
                                                style="width: 30px; height: 30px; border-radius: 50%; background: #007bff; color: white; display: flex; justify-content: center; align-items: center; font-weight: bold; margin-right: 10px;">
                                                {{ strtoupper(substr($withdrawal->user->first_name ?? '', 0, 1)) }}{{
                                                strtoupper(substr($withdrawal->user->last_name ?? '', 0, 1)) }}
                                            </div>
                                            <div>
                                                {{ $withdrawal->user->name ?? 'N/A' }}<br>
                                                <small>{{ $withdrawal->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($withdrawal->amount, 2) }}</td>
                                    <td>{{ $withdrawal->method }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info view-details"
                                            data-details="{{ $withdrawal->account_details }}">
                                            View Details
                                        </button>
                                    </td>
                                    <td>
                                        @if($withdrawal->is_linked)
                                        <span class="badge badge-success">Yes</span>
                                        @else
                                        <span class="badge badge-warning">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($withdrawal->status == 'approved') badge-success 
                                            @elseif($withdrawal->status == 'pending') badge-warning 
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
                                                @if($withdrawal->status != 'approved')
                                                <a class="dropdown-item approve-withdrawal" href="#"
                                                    data-id="{{ $withdrawal->id }}">Approve</a>
                                                @endif
                                                @if($withdrawal->status != 'declined')
                                                <a class="dropdown-item decline-withdrawal" href="#"
                                                    data-id="{{ $withdrawal->id }}">Decline</a>
                                                @endif
                                                <a class="dropdown-item edit-withdrawal" href="#"
                                                    data-id="{{ $withdrawal->id }}" data-toggle="modal"
                                                    data-target="#editWithdrawalModal">Edit</a>
                                                <a class="dropdown-item delete-withdrawal" href="#"
                                                    data-id="{{ $withdrawal->id }}">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div id="pagination" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Withdrawal Modal -->
<div class="modal fade" id="editWithdrawalModal" tabindex="-1" role="dialog" aria-labelledby="editWithdrawalModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-dark" id="editWithdrawalModalLabel">Edit Withdrawal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <form id="editWithdrawalForm">
                    @csrf
                    <input type="hidden" id="edit_withdrawal_id" name="id">

                    <div class="form-group">
                        <label for="edit_amount" class="text-dark">Amount</label>
                        <input type="number" step="0.01" class="form-control bg-light text-dark" id="edit_amount"
                            name="amount" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_method" class="text-dark">Method</label>
                        <input type="text" class="form-control bg-light text-dark" id="edit_method" name="method"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="edit_account_details" class="text-dark">Account Details</label>
                        <textarea class="form-control bg-light text-dark" id="edit_account_details"
                            name="account_details" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="edit_status" class="text-dark">Status</label>
                        <select class="form-control bg-light text-dark" id="edit_status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="declined">Declined</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_is_linked" name="is_linked">
                            <label class="form-check-label text-dark" for="edit_is_linked">
                                Is Linked Account
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Withdrawal</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Account Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-dark" id="detailsModalLabel">Account Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <pre id="detailsContent" class="text-dark p-3 bg-white rounded"></pre>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize pagination and filtering
        initializeTable();
        
        // Filter buttons
        $('.filter-btn').click(function() {
            const status = $(this).data('status');
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
            
            if (status === 'all') {
                $('tbody tr').show();
            } else {
                $('tbody tr').hide();
                $(`tbody tr[data-status="${status}"]`).show();
            }
            
            // Reinitialize pagination after filtering
            initializeTable();
        });
        
        // View account details
        $(document).on('click', '.view-details', function(e) {
            e.preventDefault();
            const details = $(this).data('details');
            $('#detailsContent').text(JSON.stringify(JSON.parse(details), null, 2));
            $('#detailsModal').modal('show');
        });
        
        // Approve withdrawal
        $(document).on('click', '.approve-withdrawal', function(e) {
            e.preventDefault();
            const withdrawalId = $(this).data('id');
            
            if (confirm('Are you sure you want to approve this withdrawal?')) {
                $.ajax({
                    url: "{{ route('admin.withdrawals.approve') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: withdrawalId
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            updateWithdrawalRow(withdrawalId, response.withdrawal);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred while approving the withdrawal.');
                    }
                });
            }
        });
        
        // Decline withdrawal
        $(document).on('click', '.decline-withdrawal', function(e) {
            e.preventDefault();
            const withdrawalId = $(this).data('id');
            
            if (confirm('Are you sure you want to decline this withdrawal?')) {
                $.ajax({
                    url: "{{ route('admin.withdrawals.decline') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: withdrawalId
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            updateWithdrawalRow(withdrawalId, response.withdrawal);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred while declining the withdrawal.');
                    }
                });
            }
        });
        
        // Delete withdrawal
        $(document).on('click', '.delete-withdrawal', function(e) {
            e.preventDefault();
            const withdrawalId = $(this).data('id');
            
            if (confirm('Are you sure you want to delete this withdrawal? This action cannot be undone.')) {
                $.ajax({
                    url: "{{ route('admin.withdrawals.delete') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: withdrawalId
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $(`#withdrawal-row-${withdrawalId}`).remove();
                            initializeTable(); // Reinitialize pagination after deletion
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred while deleting the withdrawal.');
                    }
                });
            }
        });
        
        // Edit withdrawal - open modal
        $(document).on('click', '.edit-withdrawal', function(e) {
            e.preventDefault();
            const withdrawalId = $(this).data('id');
            
            $.ajax({
                url: "{{ route('admin.withdrawals.get') }}",
                type: "GET",
                data: {
                    id: withdrawalId
                },
                success: function(response) {
                    if (response.success) {
                        const withdrawal = response.withdrawal;
                        $('#edit_withdrawal_id').val(withdrawal.id);
                        $('#edit_amount').val(withdrawal.amount);
                        $('#edit_method').val(withdrawal.method);
                        $('#edit_account_details').val(withdrawal.account_details);
                        $('#edit_status').val(withdrawal.status);
                        $('#edit_is_linked').prop('checked', withdrawal.is_linked);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while fetching withdrawal details.');
                }
            });
        });
        
        // Edit withdrawal - submit form
        $('#editWithdrawalForm').submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                url: "{{ route('admin.withdrawals.update') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        updateWithdrawalRow(response.withdrawal.id, response.withdrawal);
                        $('#editWithdrawalModal').modal('hide');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while updating the withdrawal.');
                }
            });
        });
        
        // Search functionality
        $('#searchInput').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            
            if (searchTerm.length === 0) {
                $('tbody tr').show();
            } else {
                $('tbody tr').each(function() {
                    const rowText = $(this).text().toLowerCase();
                    $(this).toggle(rowText.indexOf(searchTerm) > -1);
                });
            }
            
            initializeTable(); // Reinitialize pagination after search
        });
        
        // Change number of records
        $('#numofrecord').change(function() {
            initializeTable();
        });
        
        // Function to update withdrawal row after changes
        function updateWithdrawalRow(id, withdrawal) {
            const row = $(`#withdrawal-row-${id}`);
            
            // Update status badge
            row.find('.badge').removeClass('badge-success badge-warning badge-danger');
            if (withdrawal.status === 'approved') {
                row.find('.badge').addClass('badge-success');
            } else if (withdrawal.status === 'pending') {
                row.find('.badge').addClass('badge-warning');
            } else {
                row.find('.badge').addClass('badge-danger');
            }
            row.find('.badge').text(withdrawal.status.charAt(0).toUpperCase() + withdrawal.status.slice(1));
            
            // Update data-status attribute
            row.attr('data-status', withdrawal.status.toLowerCase());
            
            // Update other fields
            row.find('td:eq(2)').text('$' + parseFloat(withdrawal.amount).toFixed(2));
            row.find('td:eq(3)').text(withdrawal.method);
            row.find('.view-details').data('details', withdrawal.account_details);
            
            // Update is_linked badge
            const isLinkedBadge = row.find('td:eq(5) .badge');
            isLinkedBadge.removeClass('badge-success badge-warning');
            if (withdrawal.is_linked) {
                isLinkedBadge.addClass('badge-success').text('Yes');
            } else {
                isLinkedBadge.addClass('badge-warning').text('No');
            }
            
            // Update actions dropdown based on new status
            let dropdownHtml = '';
            if (withdrawal.status !== 'approved') {
                dropdownHtml += '<a class="dropdown-item approve-withdrawal" href="#" data-id="' + id + '">Approve</a>';
            }
            if (withdrawal.status !== 'declined') {
                dropdownHtml += '<a class="dropdown-item decline-withdrawal" href="#" data-id="' + id + '">Decline</a>';
            }
            dropdownHtml += '<a class="dropdown-item edit-withdrawal" href="#" data-id="' + id + '" data-toggle="modal" data-target="#editWithdrawalModal">Edit</a>';
            dropdownHtml += '<a class="dropdown-item delete-withdrawal" href="#" data-id="' + id + '">Delete</a>';
            
            row.find('.dropdown-menu').html(dropdownHtml);
        }
        
        // Initialize table with pagination
        function initializeTable() {
            const rowsPerPage = parseInt($('#numofrecord').val());
            const $rows = $('tbody tr:visible');
            const pageCount = Math.ceil($rows.length / rowsPerPage);
            const currentPage = 1;
            
            // Hide all rows
            $rows.hide();
            
            // Show only rows for current page
            $rows.slice(0, rowsPerPage).show();
            
            // Generate pagination
            generatePagination(pageCount, currentPage);
        }
        
        // Generate pagination buttons
        function generatePagination(pageCount, currentPage) {
            const $pagination = $('#pagination');
            $pagination.empty();
            
            if (pageCount <= 1) return;
            
            // Previous button
            const $prev = $('<button>').addClass('btn btn-sm btn-outline-primary mr-1')
                .text('Previous')
                .prop('disabled', currentPage === 1)
                .click(function() {
                    if (currentPage > 1) {
                        navigateToPage(currentPage - 1);
                    }
                });
            $pagination.append($prev);
            
            // Page buttons
            for (let i = 1; i <= pageCount; i++) {
                const $page = $('<button>').addClass('btn btn-sm mr-1')
                    .text(i)
                    .addClass(i === currentPage ? 'btn-primary' : 'btn-outline-primary')
                    .click(function() {
                        navigateToPage(i);
                    });
                $pagination.append($page);
            }
            
            // Next button
            const $next = $('<button>').addClass('btn btn-sm btn-outline-primary')
                .text('Next')
                .prop('disabled', currentPage === pageCount)
                .click(function() {
                    if (currentPage < pageCount) {
                        navigateToPage(currentPage + 1);
                    }
                });
            $pagination.append($next);
        }
        
        // Navigate to specific page
        function navigateToPage(page) {
            const rowsPerPage = parseInt($('#numofrecord').val());
            const $rows = $('tbody tr:visible');
            
            $rows.hide();
            $rows.slice((page - 1) * rowsPerPage, page * rowsPerPage).show();
            
            // Update pagination buttons
            generatePagination(Math.ceil($rows.length / rowsPerPage), page);
        }
    });
</script>

@include('admin.footer')