@include("auth.header")

<!-- Password Reset Form -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="200" height="80" viewBox="0 0 200 80">
                            <text x="10" y="50" font-family="Arial, sans-serif" font-size="40" font-weight="bold"
                                fill="#333"><img src="{{asset('images/logo.png')}}" alt="ziiriel-arthouse"
                                    width="150px"></text>
                        </svg>
                        <h3 class="mt-3">Create New Password</h3>
                    </div>

                    <form id="reset-password-form" action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control py-2" value="{{ $email ?? old('email') }}" readonly>
                            <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
                        </div>

                        <div class="mb-3">
                            <label for="password">New Password</label>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control py-2" id="password"
                                    placeholder="New password" required>
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            </div>
                            <div class="invalid-feedback password-error"></div>
                        </div>

                        <div class="mb-3">
                            <label for="password-confirm">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" class="form-control py-2"
                                    id="password-confirm" placeholder="Confirm password" required>
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-2">
                            Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include("auth.footer")

<!-- Toastr and jQuery -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<style>
    .is-invalid {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
    }

    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875em;
    }
</style>

<script>
    $(document).ready(function() {
    toastr.options = {
      "closeButton": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": true
    };

    // Password validation
    function validatePassword() {
      const password = $('#password').val();
      const confirm = $('#password-confirm').val();
      
      if (password.length < 8) {
        $('#password').addClass('is-invalid');
        $('.password-error').text('Password must be at least 8 characters').show();
        return false;
      }
      
      if (password !== confirm) {
        $('#password-confirm').addClass('is-invalid');
        $('.password-error').text('Passwords do not match').show();
        return false;
      }
      
      return true;
    }

    // Form submission
    $('#reset-password-form').submit(function(e) {
      e.preventDefault();
      
      if (!validatePassword()) return;
      
      const form = $(this);
      const submitBtn = form.find('[type="submit"]');
      
      // Loading state
      submitBtn.prop('disabled', true).html(`
        <span class="spinner-border spinner-border-sm" role="status"></span> Resetting...
      `);
      
      // AJAX request
      $.ajax({
        url: form.attr('action'),
        type: "POST",
        data: form.serialize(),
        success: function(response) {
          toastr.success('Password reset successfully!');
          setTimeout(() => {
            window.location.href = "{{ route('login') }}";
          }, 2000);
        },
        error: function(xhr) {
          submitBtn.prop('disabled', false).text('Reset Password');
          
          if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors;
            $.each(errors, function(key, value) {
              $(`#${key}`).addClass('is-invalid');
              $(`.${key}-error`).text(value[0]).show();
            });
          } else {
            toastr.error(xhr.responseJSON.message || 'Failed to reset password');
          }
        }
      });
    });
    
    // Real-time password validation
    $('#password, #password-confirm').on('input', function() {
      validatePassword();
    });
  });
</script>