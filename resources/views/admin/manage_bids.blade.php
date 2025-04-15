@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            @if(session('message'))
            <div class="alert alert-success mb-2">{{session('message')}}</div>
            @endif
            <div class="mt-2 mb-4">
                <h1 class="title1 text-dark">Bid Management</h1>
            </div>

            <div class="row">
                <div class="col-12">
                    <a href="{{route('admin.bids.create')}}" class="float-right btn btn-primary" id="createBidBtn">
                        <i class='fas fa-plus-circle'></i> Add New Bid
                    </a>
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
                                <div class="">
                                    <select class="form-control bg-light text-dark" id="order">
                                        <option value="desc">Descending</option>
                                        <option value="asc">Ascending</option>
                                    </select>
                                </div>
                                <div>
                                    <input type="text" id="searchInput" placeholder="Search by amount or exhibition"
                                        class="float-right mb-2 mr-sm-2 form-control bg-light text-dark">
                                    <small id="errorsearch"></small>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive" data-example-id="hoverable-table">
                        <table class="table table-hover text-dark" id="bidTable">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Exhibition</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="bidlisttbl">
                                @foreach($bids as $bid)
                                <tr id="bid-row-{{ $bid->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $bid->exhibition->title }}</strong><br>
                                        <small>ID: {{ $bid->exhibition_id }}</small>
                                    </td>
                                    <td>
                                        {{ $bid->user->name }}<br>
                                        <small>{{ $bid->user->email }}</small>
                                    </td>
                                    <td>${{ number_format($bid->amount, 2) }}</td>
                                    <td>
                                        <span
                                            class="badge badge-{{ $bid->status == 'approved' ? 'success' : ($bid->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($bid->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $bid->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-success approve-bid" data-id="{{ $bid->id }}"
                                                title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-info edit-bid" data-id="{{ $bid->id }}"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete-bid" data-id="{{ $bid->id }}"
                                                title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls -->
                    <div id="pagination" class="mt-3"></div>

                    <!-- Edit Bid Modal -->
                    <div class="modal fade" id="editBidModal" tabindex="-1" role="dialog"
                        aria-labelledby="editBidModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editBidModalLabel">Edit Bid</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="editBidForm">
                                        @csrf
                                        <input type="hidden" id="edit_bid_id" name="id">
                                        <div class="form-group">
                                            <label for="edit_amount">Amount</label>
                                            <input type="number" step="0.01" class="form-control" id="edit_amount"
                                                name="amount" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_status">Status</label>
                                            <select class="form-control" id="edit_status" name="status" required>
                                                <option value="pending">Pending</option>
                                                <option value="approved">Approved</option>
                                                <option value="rejected">Rejected</option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="updateBidBtn">Update Bid</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Create Bid Modal -->
                    <div class="modal fade" id="createBidModal" tabindex="-1" role="dialog"
                        aria-labelledby="createBidModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createBidModalLabel">Create New Bid</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="createBidForm">
                                        @csrf
                                        <div class="form-group">
                                            <label for="exhibition_id">Exhibition</label>
                                            <select class="form-control" id="exhibition_id" name="exhibition_id"
                                                required>
                                                @foreach($exhibitions as $exhibition)
                                                <option value="{{ $exhibition->id }}">{{ $exhibition->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="user_id">User</label>
                                            <select class="form-control" id="user_id" name="user_id" required>
                                                @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="amount">Amount</label>
                                            <input type="number" step="0.01" class="form-control" id="amount"
                                                name="amount" required>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" id="submitCreateBidBtn">Create
                                        Bid</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const searchInput = document.getElementById("searchInput");
                            const table = document.getElementById("bidTable");
                            const tbody = document.getElementById("bidlisttbl");
                            const rows = Array.from(tbody.getElementsByTagName("tr"));
                            const paginationDiv = document.getElementById("pagination");

                            let currentPage = 1;
                            let rowsPerPage = 10;

                            // Function to display rows for the current page
                            function displayTablePage(filteredRows, page) {
                                const start = (page - 1) * rowsPerPage;
                                const end = start + rowsPerPage;

                                rows.forEach(row => row.style.display = "none"); // Hide all rows
                                filteredRows.slice(start, end).forEach(row => row.style.display = "table-row"); // Show rows for the current page

                                generatePagination(filteredRows.length);
                            }

                            // Function to generate pagination buttons
                            function generatePagination(totalRows) {
                                paginationDiv.innerHTML = "";
                                const pageCount = Math.ceil(totalRows / rowsPerPage);

                                // Previous Button
                                const prevButton = document.createElement("button");
                                prevButton.innerText = "Previous";
                                prevButton.className = `btn btn-sm btn-outline-primary`;
                                prevButton.style.margin = "2px";
                                prevButton.disabled = currentPage === 1;
                                prevButton.addEventListener("click", () => {
                                    if (currentPage > 1) {
                                        currentPage--;
                                        filterTable();
                                    }
                                });
                                paginationDiv.appendChild(prevButton);

                                // Page Buttons
                                for (let i = 1; i <= pageCount; i++) {
                                    const btn = document.createElement("button");
                                    btn.innerText = i;
                                    btn.className = `btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'}`;
                                    btn.style.margin = "2px";
                                    btn.addEventListener("click", () => {
                                        currentPage = i;
                                        filterTable();
                                    });
                                    paginationDiv.appendChild(btn);
                                }

                                // Next Button
                                const nextButton = document.createElement("button");
                                nextButton.innerText = "Next";
                                nextButton.className = `btn btn-sm btn-outline-primary`;
                                nextButton.style.margin = "2px";
                                nextButton.disabled = currentPage === pageCount;
                                nextButton.addEventListener("click", () => {
                                    if (currentPage < pageCount) {
                                        currentPage++;
                                        filterTable();
                                    }
                                });
                                paginationDiv.appendChild(nextButton);
                            }

                            // Function to filter rows based on search input
                            function filterTable() {
                                const filter = searchInput.value.toLowerCase();
                                const filteredRows = rows.filter(row => row.innerText.toLowerCase().includes(filter));

                                displayTablePage(filteredRows, currentPage);
                            }

                            // Event listener for search input
                            searchInput.addEventListener("input", () => {
                                currentPage = 1; // Reset to the first page when searching
                                filterTable();
                            });

                            // Initial display of the table
                            filterTable();

                            // AJAX functionality for bid operations
                            $(document).ready(function() {
                                // Load toastr
                                toastr.options = {
                                    "closeButton": true,
                                    "progressBar": true,
                                    "positionClass": "toast-top-right",
                                    "timeOut": "5000"
                                };

                                // Approve bid
                                $('.approve-bid').click(function() {
                                    const bidId = $(this).data('id');
                                    const button = $(this);
                                    
                                    button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
                                    
                                    $.ajax({
                                        url: '/admin/bids/' + bidId + '/approve',
                                        type: 'POST',
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            _method: 'PATCH'
                                        },
                                        success: function(response) {
                                            toastr.success(response.message);
                                            // Update the status in the table
                                            const statusBadge = button.closest('tr').find('.badge');
                                            statusBadge.removeClass('badge-warning badge-danger').addClass('badge-success').text('Approved');
                                            button.remove();
                                        },
                                        error: function(xhr) {
                                            toastr.error(xhr.responseJSON.message || 'Error approving bid');
                                        },
                                        complete: function() {
                                            button.prop('disabled', false).html('<i class="fas fa-check"></i>');
                                        }
                                    });
                                });

                                // Edit bid modal
                                $('.edit-bid').click(function() {
                                    const bidId = $(this).data('id');
                                    const row = $('#bid-row-' + bidId);
                                    
                                    // Fetch bid details
                                    $.ajax({
                                        url: '/admin/bids/' + bidId + '/edit',
                                        type: 'GET',
                                        success: function(response) {
                                            $('#edit_bid_id').val(response.id);
                                            $('#edit_amount').val(response.amount);
                                            $('#edit_status').val(response.status);
                                            $('#editBidModal').modal('show');
                                        },
                                        error: function(xhr) {
                                            toastr.error('Error fetching bid details');
                                        }
                                    });
                                });

                                // Update bid
                                $('#updateBidBtn').click(function() {
                                    const bidId = $('#edit_bid_id').val();
                                    const button = $(this);
                                    
                                    button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
                                    
                                    $.ajax({
                                        url: '/admin/bids/' + bidId,
                                        type: 'PUT',
                                        data: $('#editBidForm').serialize(),
                                        success: function(response) {
                                            toastr.success(response.message);
                                            $('#editBidModal').modal('hide');
                                            
                                            // Update the row in the table
                                            const row = $('#bid-row-' + bidId);
                                            row.find('td:eq(3)').text('$' + parseFloat(response.bid.amount).toFixed(2));
                                            
                                            const statusBadge = row.find('.badge');
                                            statusBadge.removeClass('badge-warning badge-success badge-danger')
                                                .addClass(response.bid.status === 'approved' ? 'badge-success' : 
                                                        (response.bid.status === 'pending' ? 'badge-warning' : 'badge-danger'))
                                                .text(response.bid.status.charAt(0).toUpperCase() + response.bid.status.slice(1));
                                        },
                                        error: function(xhr) {
                                            toastr.error(xhr.responseJSON.message || 'Error updating bid');
                                        },
                                        complete: function() {
                                            button.prop('disabled', false).html('Update Bid');
                                        }
                                    });
                                });

                                // Delete bid
                                $('.delete-bid').click(function() {
                                    const bidId = $(this).data('id');
                                    
                                    if (confirm('Are you sure you want to delete this bid?')) {
                                        const button = $(this);
                                        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
                                        
                                        $.ajax({
                                            url: '/admin/bids/' + bidId,
                                            type: 'DELETE',
                                            data: {
                                                _token: '{{ csrf_token() }}'
                                            },
                                            success: function(response) {
                                                toastr.success(response.message);
                                                $('#bid-row-' + bidId).remove();
                                                // Re-filter the table to update pagination
                                                filterTable();
                                            },
                                            error: function(xhr) {
                                                toastr.error(xhr.responseJSON.message || 'Error deleting bid');
                                            },
                                            complete: function() {
                                                button.prop('disabled', false).html('<i class="fas fa-trash"></i>');
                                            }
                                        });
                                    }
                                });

                                // Create bid modal
                                $('#createBidBtn').click(function() {
                                    $('#createBidModal').modal('show');
                                });

                                // Submit create bid
                                $('#submitCreateBidBtn').click(function() {
                                    const button = $(this);
                                    button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating...');
                                    
                                    $.ajax({
                                        url: '/admin/bids',
                                        type: 'POST',
                                        data: $('#createBidForm').serialize(),
                                        success: function(response) {
                                            toastr.success(response.message);
                                            $('#createBidModal').modal('hide');
                                            $('#createBidForm')[0].reset();
                                            
                                            // Add the new bid to the table
                                            const newRow = `
                                                <tr id="bid-row-${response.bid.id}">
                                                    <td>${$('#bidlisttbl tr').length + 1}</td>
                                                    <td>
                                                        <strong>${response.exhibition.title}</strong><br>
                                                        <small>ID: ${response.bid.exhibition_id}</small>
                                                    </td>
                                                    <td>
                                                        ${response.user.name}<br>
                                                        <small>${response.user.email}</small>
                                                    </td>
                                                    <td>$${parseFloat(response.bid.amount).toFixed(2)}</td>
                                                    <td>
                                                        <span class="badge badge-warning">Pending</span>
                                                    </td>
                                                    <td>${new Date(response.bid.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-sm btn-success approve-bid" data-id="${response.bid.id}" title="Approve">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-info edit-bid" data-id="${response.bid.id}" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-danger delete-bid" data-id="${response.bid.id}" title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            `;
                                            $('#bidlisttbl').append(newRow);
                                            
                                            // Rebind events for the new buttons
                                            $('#bid-row-' + response.bid.id + ' .approve-bid').click(function() {
                                                // Same approve functionality as above
                                            });
                                            $('#bid-row-' + response.bid.id + ' .edit-bid').click(function() {
                                                // Same edit functionality as above
                                            });
                                            $('#bid-row-' + response.bid.id + ' .delete-bid').click(function() {
                                                // Same delete functionality as above
                                            });
                                            
                                            // Re-filter the table to update pagination
                                            filterTable();
                                        },
                                        error: function(xhr) {
                                            toastr.error(xhr.responseJSON.message || 'Error creating bid');
                                        },
                                        complete: function() {
                                            button.prop('disabled', false).html('Create Bid');
                                        }
                                    });
                                });
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.footer')