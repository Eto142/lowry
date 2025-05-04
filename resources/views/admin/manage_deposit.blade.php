@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            @if(session('message'))
            <div class="alert alert-success mb-2">{{session('message')}}</div>
            @endif
            <div class="mt-2 mb-4">
                <h1 class="title1 text-dark">Deposit Management</h1>
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
                                    <input type="text" id="searchInput" placeholder="Search by user, amount or hash"
                                        class="float-right mb-2 mr-sm-2 form-control bg-light text-dark">
                                    <small id="errorsearch"></small>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover text-dark" id="depositTable">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Currency</th>
                                    <th>Crypto Type</th>
                                    <th>Wallet Address</th>
                                    <th>Transaction Hash</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="depositslisttbl">
                                @foreach($deposits as $index => $deposit)
                                <tr id="deposit-row-{{ $deposit->id }}"
                                    data-status="{{ strtolower($deposit->status) }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center;">
                                            <div
                                                style="width: 30px; height: 30px; border-radius: 50%; background: #007bff; color: white; display: flex; justify-content: center; align-items: center; font-weight: bold; margin-right: 10px;">
                                                {{ strtoupper(substr($deposit->user->first_name ?? '', 0, 1)) }}{{
                                                strtoupper(substr($deposit->user->last_name ?? '', 0, 1)) }}
                                            </div>
                                            <div>
                                                {{ $deposit->user->first_name ?? 'N/A' }} {{ $deposit->user->last_name
                                                ?? 'N/A' }}<br>
                                                <small>{{ $deposit->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($deposit->amount, 2) }}</td>
                                    <td>{{ $deposit->currency }}</td>
                                    <td>{{ $deposit->crypto_type }}</td>
                                    <td><small>{{ substr($deposit->wallet_address, 0, 10) }}...</small></td>
                                    <td><small>{{ $deposit->transaction_hash ? substr($deposit->transaction_hash, 0,
                                            10).'...' : 'N/A' }}</small></td>
                                    <td>
                                        <span class="badge 
                                            @if($deposit->status == 'approved') badge-success 
                                            @elseif($deposit->status == 'pending') badge-warning 
                                            @else badge-danger @endif">
                                            {{ ucfirst($deposit->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $deposit->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                @if($deposit->status != 'approved')
                                                <a class="dropdown-item approve-deposit" href="#"
                                                    data-id="{{ $deposit->id }}">Approve</a>
                                                @endif
                                                @if($deposit->status != 'declined')
                                                <a class="dropdown-item decline-deposit" href="#"
                                                    data-id="{{ $deposit->id }}">Decline</a>
                                                @endif
                                                <a class="dropdown-item edit-deposit" href="#"
                                                    data-id="{{ $deposit->id }}" data-toggle="modal"
                                                    data-target="#editDepositModal">Edit</a>
                                                <a class="dropdown-item delete-deposit" href="#"
                                                    data-id="{{ $deposit->id }}">Delete</a>
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

<!-- Edit Deposit Modal -->
<div class="modal fade" id="editDepositModal" tabindex="-1" role="dialog" aria-labelledby="editDepositModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-dark" id="editDepositModalLabel">Edit Deposit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <form id="editDepositForm">
                    @csrf
                    <input type="hidden" id="edit_deposit_id" name="id">

                    <div class="form-group">
                        <label for="edit_amount" class="text-dark">Amount</label>
                        <input type="number" step="0.01" class="form-control bg-light text-dark" id="edit_amount"
                            name="amount" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_currency" class="text-dark">Currency</label>
                        <select class="form-control bg-light text-dark" id="edit_currency" name="currency" required>
                            <option value="USD">USD</option>
                            <option value="BTC">BTC</option>
                            <option value="ETH">ETH</option>
                            <option value="LTC">LTC</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_crypto_type" class="text-dark">Crypto Type</label>
                        <input type="text" class="form-control bg-light text-dark" id="edit_crypto_type"
                            name="crypto_type">
                    </div>

                    <div class="form-group">
                        <label for="edit_wallet_address" class="text-dark">Wallet Address</label>
                        <input type="text" class="form-control bg-light text-dark" id="edit_wallet_address"
                            name="wallet_address">
                    </div>

                    <div class="form-group">
                        <label for="edit_transaction_hash" class="text-dark">Transaction Hash</label>
                        <input type="text" class="form-control bg-light text-dark" id="edit_transaction_hash"
                            name="transaction_hash">
                    </div>

                    <div class="form-group">
                        <label for="edit_status" class="text-dark">Status</label>
                        <select class="form-control bg-light text-dark" id="edit_status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="declined">Declined</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Deposit</button>
                </form>
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
        
        // Approve deposit
        $(document).on('click', '.approve-deposit', function(e) {
            e.preventDefault();
            const depositId = $(this).data('id');
            
            if (confirm('Are you sure you want to approve this deposit?')) {
                $.ajax({
                    url: "{{ route('admin.deposits.approve') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: depositId
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            updateDepositRow(depositId, response.deposit);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred while approving the deposit.');
                    }
                });
            }
        });
        
        // Decline deposit
        $(document).on('click', '.decline-deposit', function(e) {
            e.preventDefault();
            const depositId = $(this).data('id');
            
            if (confirm('Are you sure you want to decline this deposit?')) {
                $.ajax({
                    url: "{{ route('admin.deposits.decline') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: depositId
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            updateDepositRow(depositId, response.deposit);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred while declining the deposit.');
                    }
                });
            }
        });
        
        // Delete deposit
        $(document).on('click', '.delete-deposit', function(e) {
            e.preventDefault();
            const depositId = $(this).data('id');
            
            if (confirm('Are you sure you want to delete this deposit? This action cannot be undone.')) {
                $.ajax({
                    url: "{{ route('admin.deposits.delete') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: depositId
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $(`#deposit-row-${depositId}`).remove();
                            initializeTable(); // Reinitialize pagination after deletion
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred while deleting the deposit.');
                    }
                });
            }
        });
        
        // Edit deposit - open modal
        $(document).on('click', '.edit-deposit', function(e) {
            e.preventDefault();
            const depositId = $(this).data('id');
            
            $.ajax({
                url: "{{ route('admin.deposits.get') }}",
                type: "GET",
                data: {
                    id: depositId
                },
                success: function(response) {
                    if (response.success) {
                        const deposit = response.deposit;
                        $('#edit_deposit_id').val(deposit.id);
                        $('#edit_amount').val(deposit.amount);
                        $('#edit_currency').val(deposit.currency);
                        $('#edit_crypto_type').val(deposit.crypto_type);
                        $('#edit_wallet_address').val(deposit.wallet_address);
                        $('#edit_transaction_hash').val(deposit.transaction_hash);
                        $('#edit_status').val(deposit.status);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while fetching deposit details.');
                }
            });
        });
        
        // Edit deposit - submit form
        $('#editDepositForm').submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                url: "{{ route('admin.deposits.update') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        updateDepositRow(response.deposit.id, response.deposit);
                        $('#editDepositModal').modal('hide');
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while updating the deposit.');
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
        
        // Function to update deposit row after changes
        function updateDepositRow(id, deposit) {
            const row = $(`#deposit-row-${id}`);
            
            // Update status badge
            row.find('.badge').removeClass('badge-success badge-warning badge-danger');
            if (deposit.status === 'approved') {
                row.find('.badge').addClass('badge-success');
            } else if (deposit.status === 'pending') {
                row.find('.badge').addClass('badge-warning');
            } else {
                row.find('.badge').addClass('badge-danger');
            }
            row.find('.badge').text(deposit.status.charAt(0).toUpperCase() + deposit.status.slice(1));
            
            // Update data-status attribute
            row.attr('data-status', deposit.status.toLowerCase());
            
            // Update other fields if needed
            row.find('td:eq(2)').text(deposit.amount.toFixed(2));
            row.find('td:eq(3)').text(deposit.currency);
            row.find('td:eq(4)').text(deposit.crypto_type);
            row.find('td:eq(5) small').text(deposit.wallet_address ? deposit.wallet_address.substring(0, 10) + '...' : 'N/A');
            row.find('td:eq(6) small').text(deposit.transaction_hash ? deposit.transaction_hash.substring(0, 10) + '...' : 'N/A');
            
            // Update actions dropdown based on new status
            let dropdownHtml = '';
            if (deposit.status !== 'approved') {
                dropdownHtml += '<a class="dropdown-item approve-deposit" href="#" data-id="' + id + '">Approve</a>';
            }
            if (deposit.status !== 'declined') {
                dropdownHtml += '<a class="dropdown-item decline-deposit" href="#" data-id="' + id + '">Decline</a>';
            }
            dropdownHtml += '<a class="dropdown-item edit-deposit" href="#" data-id="' + id + '" data-toggle="modal" data-target="#editDepositModal">Edit</a>';
            dropdownHtml += '<a class="dropdown-item delete-deposit" href="#" data-id="' + id + '">Delete</a>';
            
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