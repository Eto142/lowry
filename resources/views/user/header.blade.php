<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ziiriel contemporary art gallery Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Add these CDNs to your layout -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>

    <style>
        .sidebar-button {
            width: 100%;
            text-align: left;
            font-weight: bold;
            border-radius: 4px;
        }

        body {
            background-color: #ffffff;
        }

        .sidebar-button {
            width: 100%;
            text-align: left;
            font-weight: bold;
        }

        .profile-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
        }

        .navbar {
            padding: 15px;
        }

        .border-bottom {
            border-bottom: 2px solid black !important;
        }

        .btn-dark {
            background-color: black;
            color: white;
        }

        .btn-outline-secondary {
            border: 1px solid black;
            color: black;
        }

        .balance-display {
            background-color: #f8f9fa;
            padding: 5px 10px;
            border-radius: 4px;
            margin-right: 15px;
        }
    </style>
    <!-- Smartsupp Live Chat script -->
    <script type="text/javascript">
        var _smartsupp = _smartsupp || {};
    _smartsupp.key = 'e62c3314375e86409ccbe28d9d4cd2ca935584b7';
    window.smartsupp||(function(d) {
      var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
      s=d.getElementsByTagName('script')[0];c=d.createElement('script');
      c.type='text/javascript';c.charset='utf-8';c.async=true;
      c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
    })(document);
    </script>
    <noscript> Powered by <a href=“https://www.smartsupp.com” target=“_blank”>Smartsupp</a></noscript>

</head>

<body>
    <nav class="navbar navbar-light bg-white border-bottom">
        <div class="container d-flex justify-content-between">
            <a class="navbar-brand fw-bold fs-3" href="#">
                <img class="sticky-logo" src="{{ asset('images/logo.png') }}" width="100" alt="ziiriel-arthouse">
            </a>
            <div class="d-flex align-items-center">
                <span class="me-3 fw-bold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                <span class="balance-display me-3 fw-bold">
                    Balance: ${{ number_format(Auth::user()->balance->amount ?? 0, 2) }}
                </span>
                @php
                $kyc = \App\Models\KycVerification::where('user_id', auth()->id())->first();
                @endphp

                @if(!$kyc)
                {{-- No KYC submission exists --}}
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kycModal">
                    <i class="fas fa-id-card"></i> KYC Not Verified
                </button>
                @else
                @switch($kyc->status)
                @case('pending')
                {{-- KYC is under review --}}
                <button class="btn btn-warning">
                    <i class="fas fa-hourglass-half"></i> KYC Under Review
                </button>
                @break

                @case('approved')
                {{-- KYC is approved --}}
                <button class="btn btn-success" disabled>
                    <i class="fas fa-check-circle"></i> KYC Verified
                    <small class="d-block">Approved on {{ $kyc->updated_at->format('M d, Y') }}</small>
                </button>
                @break

                @case('rejected')
                {{-- KYC was rejected --}}
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#kycModal">
                    <i class="fas fa-exclamation-circle"></i> KYC Rejected
                    <small class="d-block">Click to resubmit</small>
                </button>
                @if($kyc->rejection_reason)
                <div class="mt-2 small text-danger">
                    Reason: {{ $kyc->rejection_reason }}
                </div>
                @endif
                @break
                @endswitch
                @endif
            </div>
        </div>
    </nav>

    <!-- KYC Modal -->
    <!-- KYC Modal -->
    <div class="modal fade" id="kycModal" tabindex="-1" aria-labelledby="kycModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kycModalLabel">KYC Verification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="kycForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="idType" class="form-label">ID Card Type</label>
                            <select class="form-select" name="id_type" id="idType" required>
                                <option value="">Select ID Type</option>
                                <option value="passport">Passport</option>
                                <option value="driver_license">Driver's License</option>
                                <option value="national_id">National ID</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="frontId" class="form-label">Upload Front of ID Document</label>
                            <input type="file" class="form-control" name="front_id" id="frontId" accept="image/*,.pdf"
                                required>
                            <div class="invalid-feedback"></div>
                            <small class="text-muted">Accepted formats: JPG, PNG, PDF (Max: 2MB)</small>
                        </div>
                        <div class="mb-3">
                            <label for="backId" class="form-label">Upload Back of ID Document</label>
                            <input type="file" class="form-control" name="back_id" id="backId" accept="image/*,.pdf">
                            <div class="invalid-feedback"></div>
                            <small class="text-muted">Optional for some ID types (Max: 2MB)</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submitKycBtn" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Toggle (visible only on phones) -->
    <div class="d-md-none text-end p-3 border-bottom">
        <button class="btn btn-outline-dark" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSidebar"
            aria-expanded="false" aria-controls="mobileSidebar">
            ☰ Menu
        </button>
    </div>

    <div class="container mt-4">
        <div class="row">
            <!-- Desktop Sidebar -->
            <div class="col-md-3 d-none d-md-block">
                <div class="d-grid gap-2">
                    <button class="btn btn-dark sidebar-button mb-2">Welcome ></button>
                    <a class="btn btn-light sidebar-button mb-2" href="/">Home</a>
                    <a class="btn btn-dark sidebar-button mb-2" href="{{ route('home') }}">Dashboard</a>
                    <a class="btn btn-light sidebar-button mb-2" href="{{ route('deposit.create') }}">Deposit</a>
                    <a class="btn btn-dark sidebar-button mb-2" href="{{ route('withdrawals.index') }}">Withdrawal</a>
                    <a class="btn btn-light sidebar-button mb-2" href="{{ route('user.create.exhibition') }}">Uploade
                        Artwork For Exhibition</a>
                    <a class="btn btn-dark sidebar-button mb-2" href="{{ route('user.manage.exhibitions') }}"> Manage
                        Exhibition</a>

                </div>
            </div>

            <!-- Mobile Sidebar (collapsible) -->
            <div class="collapse d-md-none mb-3" id="mobileSidebar">
                <div class="d-grid gap-2">
                    <button class="btn btn-dark sidebar-button mb-2">Welcome ></button>
                    <a class="btn btn-light sidebar-button mb-2" href="/">Home</a>
                    <a class="btn btn-dark sidebar-button mb-2" href="{{ route('home') }}">Dashboard</a>
                    <a class="btn btn-light sidebar-button mb-2" href="{{ route('deposit.create') }}">Deposit</a>
                    <a class="btn btn-dark sidebar-button mb-2" href="{{ route('withdrawals.index') }}">Withdrawal</a>
                    <a class="btn btn-light sidebar-button mb-2" href="{{ route('user.create.exhibition') }}">Uploade
                        Artwork For Exhibition</a>
                    <a class="btn btn-dark sidebar-button mb-2" href="{{ route('user.manage.exhibitions') }}"> Manage
                        Exhibition</a>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    // Initialize toastr
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": true,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000"
                    };
                
                    // Load KYC status on page load
                    loadKycStatus();
                
                    // Validate inputs on blur
                    $('input, select').blur(function() {
                        validateField($(this));
                    });
                
                    // Handle KYC form submission
                    $('#submitKycBtn').click(function() {
                        submitKycForm();
                    });
                
                    // Open modal with proper state
                    $('[data-bs-target="#kycModal"]').click(function() {
                        resetKycForm();
                    });
                
                    function loadKycStatus() {
                        $.ajax({
                            url: "{{ route('kyc.status') }}",
                            type: "GET",
                            dataType: "html",
                            success: function(response) {
                                $('#kycStatusContainer').html(response);
                            },
                            error: function(xhr) {
                                // toastr.error('Failed to load KYC status');
                            }
                        });
                    }
                
                    function validateField(field) {
                        if (field.prop('required') && !field.val()) {
                            field.addClass('is-invalid').removeClass('is-valid');
                            return false;
                        } else {
                            field.removeClass('is-invalid').addClass('is-valid');
                            return true;
                        }
                    }
                
                    function resetKycForm() {
                        $('#kycForm')[0].reset();
                        $('#kycForm .is-invalid').removeClass('is-invalid');
                        $('#kycForm .invalid-feedback').text('');
                    }
                
                    function submitKycForm() {
                        const form = $('#kycForm');
                        let isValid = true;
                
                        // Validate all required fields
                        form.find('[required]').each(function() {
                            if (!validateField($(this))) {
                                isValid = false;
                            }
                        });
                
                        if (!isValid) {
                            toastr.error('Please fill in all required fields');
                            return;
                        }
                
                        const submitBtn = $('#submitKycBtn');
                        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...');
                
                        // Prepare form data for file upload
                        const formData = new FormData(form[0]);
                
                        $.ajax({
                            url: "{{ route('kyc.submit') }}",
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            dataType: "json",
                            success: function(response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    $('#kycModal').modal('hide');
                                    loadKycStatus();
                                } else {
                                    toastr.error(response.message);
                                }
                            },
                            error: function(xhr) {
                                if (xhr.status === 422) {
                                    const errors = xhr.responseJSON.errors;
                                    $.each(errors, function(key, value) {
                                        const input = form.find('[name="' + key + '"]');
                                        input.addClass('is-invalid')
                                            .next('.invalid-feedback').text(value[0]);
                                    });
                                    toastr.error('Please correct the errors in the form');
                                } else {
                                    toastr.error(xhr.responseJSON?.message || 'An error occurred. Please try again.');
                                }
                            },
                            complete: function() {
                                submitBtn.prop('disabled', false).text('Submit');
                            }
                        });
                    }
                });
            </script>