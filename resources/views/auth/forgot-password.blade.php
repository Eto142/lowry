@include("auth.header")

<!-- Forgot Password Form -->
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
                        <h3 class="mt-3">Reset Your Password</h3>
                    </div>

                    <form id="forgot-password-form" action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email">Email address</label>
                            <div class="input-group">
                                <input type="email" name="email" class="form-control py-2" id="email"
                                    placeholder="Enter your email" required>
                                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                            </div>
                            <div class="invalid-feedback email-error"></div>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-2">
                            Send Reset Link
                        </button>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                <i class="bi bi-arrow-left"></i> Back to Login
                            </a>
                        </div>
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

    // Form submission
    $('#forgot-password-form').submit(function(e) {
      e.preventDefault();
      const form = $(this);
      const submitBtn = form.find('[type="submit"]');
      
      // Reset validation
      form.find('.is-invalid').removeClass('is-invalid');
      form.find('.invalid-feedback').text('').hide();
      
      // Validate email
      const email = $('#email').val();
      if (!email) {
        $('#email').addClass('is-invalid');
        $('.email-error').text('Email is required').show();
        return;
      }
      
      // Email format validation
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(email)) {
        $('#email').addClass('is-invalid');
        $('.email-error').text('Invalid email format').show();
        return;
      }
      
      // Loading state
      submitBtn.prop('disabled', true).html(`
        <span class="spinner-border spinner-border-sm" role="status"></span> Sending...
      `);
      
      // AJAX request
      $.ajax({
        url: form.attr('action'),
        type: "POST",
        data: form.serialize(),
        success: function(response) {
          toastr.success('Password reset link sent to your email');
          submitBtn.prop('disabled', false).text('Send Reset Link');
        },
        error: function(xhr) {
          submitBtn.prop('disabled', false).text('Send Reset Link');
          
          if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors;
            $.each(errors, function(key, value) {
              $(`#${key}`).addClass('is-invalid');
              $(`.${key}-error`).text(value[0]).show();
            });
          } else {
            toastr.error(xhr.responseJSON.message || 'An error occurred');
          }
        }
      });
    });
  });
</script>