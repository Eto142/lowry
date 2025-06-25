@include('admin.header')

<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            @if(session('message'))
            <div class="alert alert-success mb-2">{{ session('message') }}</div>
            @endif
            <div class="mt-2 mb-4">
                <h1 class="title1 text-dark">Change Admin Password</h1>
            </div>

            <div class="card bg-light">
                <div class="card-body">
                    <form id="changePasswordForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <h5 class="text-dark">Current Password</h5>
                                <input class="form-control text-dark bg-light" type="password" name="current_password"
                                    id="current_password" required>
                                <span class="text-danger" id="current_password_error"></span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <h5 class="text-dark">New Password</h5>
                                <input class="form-control text-dark bg-light" type="password" name="new_password"
                                    id="new_password" required>
                                <span class="text-danger" id="new_password_error"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <h5 class="text-dark">Confirm New Password</h5>
                                <input class="form-control text-dark bg-light" type="password"
                                    name="new_password_confirmation" id="new_password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Change Password</button>
                            <a href="{{ route('admin.home') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Initialize toastr with custom settings if needed
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000"
        };

        const adminId = "{{ Auth::guard('admin')->user()->id }}";
        $('#changePasswordForm').attr('action', `/admin/${adminId}/change-password`);

        $('#changePasswordForm').on('submit', function (e) {
            e.preventDefault();
            const formData = $(this).serialize();
            const url = $(this).attr('action');
            
            // Clear previous errors
            $('#current_password_error').text('');
            $('#new_password_error').text('');

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                success: function (response) {
                    toastr.success(response.message, 'Success');
                    $('#changePasswordForm')[0].reset();
                },
                error: function (response) {
                    const res = response.responseJSON;
                    
                    if (response.status === 422) {
                        // Validation errors
                        toastr.warning(res.message, 'Validation Error');
                        
                        // Display field-specific errors
                        if (res.errors.current_password) {
                            $('#current_password_error').text(res.errors.current_password[0]);
                        }
                        if (res.errors.new_password) {
                            $('#new_password_error').text(res.errors.new_password[0]);
                        }
                    } 
                    else if (response.status === 500) {
                        // Server errors
                        toastr.error(res.message, 'Server Error');
                    }
                    else if (response.status === 403) {
                        // Authorization errors
                        toastr.error(res.message, 'Authorization Error');
                    }
                    else {
                        // Other errors
                        toastr.error('An unexpected error occurred. Please try again.', 'Error');
                    }
                }
            });
        });
    });
</script>

@include('admin.footer')