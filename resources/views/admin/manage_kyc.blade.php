@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            @if(session('message'))
            <div class="alert alert-success mb-2">{{session('message')}}</div>
            @endif
            <div class="mt-2 mb-4">
                <h1 class="title1 text-dark">KYC Verification Requests</h1>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-secondary filter-btn" data-status="all">All</button>
                            <button type="button" class="btn btn-warning filter-btn"
                                data-status="pending">Pending</button>
                            <button type="button" class="btn btn-success filter-btn"
                                data-status="approved">Approved</button>
                            <button type="button" class="btn btn-danger filter-btn"
                                data-status="rejected">Rejected</button>
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
                                    <input type="text" id="searchInput"
                                        placeholder="Search by user, ID number or country"
                                        class="float-right mb-2 mr-sm-2 form-control bg-light text-dark">
                                    <small id="errorsearch"></small>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover text-dark" id="kycTable">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>User</th>
                                    <th>ID Type</th>
                                    <th>ID Number</th>
                                    <th>Country</th>
                                    <th>Documents</th>
                                    <th>Status</th>
                                    <th>Submitted At</th>
                                    <th>Verified At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="kyclisttbl">
                                @foreach($kycs as $index => $kyc)
                                <tr id="kyc-row-{{ $kyc->id }}" data-status="{{ strtolower($kyc->status) }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center;">
                                            <div
                                                style="width: 30px; height: 30px; border-radius: 50%; background: #007bff; color: white; display: flex; justify-content: center; align-items: center; font-weight: bold; margin-right: 10px;">
                                                {{ strtoupper(substr($kyc->user->first_name ?? '', 0, 1)) }}{{
                                                strtoupper(substr($kyc->user->last_name ?? '', 0, 1)) }}
                                            </div>
                                            <div>
                                                {{ $kyc->user->name ?? 'N/A' }}<br>
                                                <small>{{ $kyc->user->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $kyc->id_type }}</td>
                                    <td>{{ $kyc->id_number }}</td>
                                    <td>{{ $kyc->country }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info view-documents"
                                            data-front="{{ $kyc->getFrontDocumentUrl() }}"
                                            data-back="{{ $kyc->getBackDocumentUrl() }}"
                                            data-selfie="{{ $kyc->getSelfieDocumentUrl() }}">
                                            View Documents
                                        </button>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($kyc->status == 'approved') badge-success 
                                            @elseif($kyc->status == 'pending') badge-warning 
                                            @else badge-danger @endif">
                                            {{ ucfirst($kyc->status) }}
                                        </span>
                                        @if($kyc->status == 'rejected' && $kyc->rejection_reason)
                                        <br><small class="text-danger">{{ $kyc->rejection_reason }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $kyc->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @if($kyc->verified_at)
                                        {{ $kyc->verified_at->format('Y-m-d H:i') }}
                                        @if($kyc->verifier)
                                        <br><small>by {{ $kyc->verifier->name }}</small>
                                        @endif
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($kyc->status == 'pending')
                                        <div class="dropdown">
                                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item approve-kyc" href="#"
                                                    data-id="{{ $kyc->id }}">Approve</a>
                                                <a class="dropdown-item reject-kyc" href="#" data-id="{{ $kyc->id }}"
                                                    data-toggle="modal" data-target="#rejectKycModal">Reject</a>
                                            </div>
                                        </div>
                                        @else
                                        <button class="btn btn-sm btn-secondary" disabled>No actions</button>
                                        @endif
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

<!-- Reject KYC Modal -->
<div class="modal fade" id="rejectKycModal" tabindex="-1" role="dialog" aria-labelledby="rejectKycModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-dark" id="rejectKycModalLabel">Reject KYC Verification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <form id="rejectKycForm">
                    @csrf
                    <input type="hidden" id="reject_kyc_id" name="id">

                    <div class="form-group">
                        <label for="rejection_reason" class="text-dark">Reason for Rejection</label>
                        <textarea class="form-control bg-light text-dark" id="rejection_reason" name="rejection_reason"
                            rows="3" required></textarea>
                        <small class="text-muted">This message will be sent to the user.</small>
                    </div>

                    <button type="submit" class="btn btn-danger">Reject KYC</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Documents Modal -->
<div class="modal fade" id="documentsModal" tabindex="-1" role="dialog" aria-labelledby="documentsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-dark" id="documentsModalLabel">KYC Documents</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="text-center text-dark">Front Document</h6>
                        <img id="frontDocumentImg" src="" class="img-fluid rounded border" style="max-height: 300px;">
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-center text-dark">Back Document</h6>
                        <img id="backDocumentImg" src="" class="img-fluid rounded border" style="max-height: 300px;">
                        <p id="noBackDocument" class="text-center text-muted mt-3" style="display: none;">No back
                            document provided</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-center text-dark">Selfie</h6>
                        <img id="selfieDocumentImg" src="" class="img-fluid rounded border" style="max-height: 300px;">
                        <p id="noSelfieDocument" class="text-center text-muted mt-3" style="display: none;">No selfie
                            provided</p>
                    </div>
                </div>
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
        
        // View documents
        $(document).on('click', '.view-documents', function(e) {
            e.preventDefault();
            const frontDoc = $(this).data('front');
            const backDoc = $(this).data('back');
            const selfieDoc = $(this).data('selfie');
            
            $('#frontDocumentImg').attr('src', frontDoc);
            
            if (backDoc) {
                $('#backDocumentImg').attr('src', backDoc).show();
                $('#noBackDocument').hide();
            } else {
                $('#backDocumentImg').hide();
                $('#noBackDocument').show();
            }
            
            if (selfieDoc) {
                $('#selfieDocumentImg').attr('src', selfieDoc).show();
                $('#noSelfieDocument').hide();
            } else {
                $('#selfieDocumentImg').hide();
                $('#noSelfieDocument').show();
            }
            
            $('#documentsModal').modal('show');
        });
        
        // Approve KYC
        $(document).on('click', '.approve-kyc', function(e) {
            e.preventDefault();
            const kycId = $(this).data('id');
            
            if (confirm('Are you sure you want to approve this KYC verification?')) {
                $.ajax({
                    url: "{{ route('admin.kyc.approve') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: kycId
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            updateKycRow(kycId, response.kyc);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred while approving the KYC verification.');
                    }
                });
            }
        });
        
        // Reject KYC - open modal
        $(document).on('click', '.reject-kyc', function(e) {
            e.preventDefault();
            const kycId = $(this).data('id');
            $('#reject_kyc_id').val(kycId);
        });
        
        // Reject KYC - submit form
        $('#rejectKycForm').submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                url: "{{ route('admin.kyc.reject') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        updateKycRow(response.kyc.id, response.kyc);
                        $('#rejectKycModal').modal('hide');
                        $('#rejectKycForm')[0].reset();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('An error occurred while rejecting the KYC verification.');
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
        
        // Function to update KYC row after changes
        function updateKycRow(id, kyc) {
            const row = $(`#kyc-row-${id}`);
            
            // Update status badge
            row.find('.badge').removeClass('badge-success badge-warning badge-danger');
            if (kyc.status === 'approved') {
                row.find('.badge').addClass('badge-success');
            } else if (kyc.status === 'pending') {
                row.find('.badge').addClass('badge-warning');
            } else {
                row.find('.badge').addClass('badge-danger');
            }
            row.find('.badge').text(kyc.status.charAt(0).toUpperCase() + kyc.status.slice(1));
            
            // Update data-status attribute
            row.attr('data-status', kyc.status.toLowerCase());
            
            // Update rejection reason if exists
            if (kyc.status === 'rejected' && kyc.rejection_reason) {
                if (row.find('.text-danger').length === 0) {
                    row.find('td:eq(6)').append('<br><small class="text-danger">' + kyc.rejection_reason + '</small>');
                } else {
                    row.find('.text-danger').text(kyc.rejection_reason);
                }
            } else {
                row.find('.text-danger').remove();
            }
            
            // Update verified at information
            const verifiedAtTd = row.find('td:eq(8)');
            if (kyc.verified_at) {
                verifiedAtTd.html(kyc.verified_at);
                if (kyc.verifier) {
                    verifiedAtTd.append('<br><small>by ' + kyc.verifier.name + '</small>');
                }
            } else {
                verifiedAtTd.text('N/A');
            }
            
            // Update actions
            const actionsTd = row.find('td:eq(9)');
            if (kyc.status === 'pending') {
                actionsTd.html(`
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item approve-kyc" href="#" data-id="${id}">Approve</a>
                            <a class="dropdown-item reject-kyc" href="#" data-id="${id}" data-toggle="modal" data-target="#rejectKycModal">Reject</a>
                        </div>
                    </div>
                `);
            } else {
                actionsTd.html('<button class="btn btn-sm btn-secondary" disabled>No actions</button>');
            }
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