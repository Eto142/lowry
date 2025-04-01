@include("auth.header")

<!-- Login Form Modal (Hidden Initially) -->
<div id="auth-modal" class="modal-overlay" style="display: none;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow">
          <div class="card-body p-4">
            <div class="row">
              <div class="col-md-8 border-end">
                <!-- Lowry Logo -->
                <div class="login-logo text-start mb-4">
                  <svg xmlns="http://www.w3.org/2000/svg" width="200" height="80" viewBox="0 0 200 80">
                    <text x="10" y="50" font-family="Arial, sans-serif" font-size="40" font-weight="bold"
                      fill="#333">LOWRY</text>
                  </svg>
                </div>

                <form id="login-form" class="login-form">
                  @csrf
                  <div class="mb-3">
                    <label for="email">Email address:</label>
                    <div class="input-group">
                      <input type="email" name="email" class="form-control py-1" id="email" placeholder="Email address"
                        required>
                      <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    </div>
                    <div class="invalid-feedback email-error"></div>
                  </div>

                  <div class="mb-3">
                    <label for="password">Password</label>
                    <div class="input-group">
                      <input type="password" name="password" class="form-control py-1" id="password"
                        placeholder="Password" required>
                      <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    </div>
                    <div class="invalid-feedback password-error"></div>
                  </div>

                  <div class="mb-2">
                    <small><a href="" class="password-link">Forgot password?</a></small>
                  </div>

                  <button type="submit" class="btn btn-dark btn-signin rounded-bit bg-white py-1 text-dark my-2">
                    Sign In
                  </button>

                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="rememberPassword">
                    <label class="form-check-label" for="rememberPassword">
                      <small>Remember me</small>
                    </label>
                  </div>
                </form>
              </div>

              <div class="col-md-4 d-flex flex-column justify-content-center align-items-center">
                <div class="new-customer">
                  <p class="text-center mb-3">New customer?</p>
                  <a href="{{ route('register') }}" class="text-decoration-none">
                    <button class="btn btn-outline-dark">Join now</button>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Spacer to push footer down -->
<div class="flex-grow-1"></div>

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

  .is-valid {
    border-color: #28a745 !important;
    box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25) !important;
  }

  .invalid-feedback {
    display: none;
    color: #dc3545;
    font-size: 0.875em;
  }

  .was-validated .form-control:invalid~.invalid-feedback {
    display: block;
  }
</style>

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

    // Validate inputs on blur
    $('input').blur(function() {
      if ($(this).val() === '') {
        $(this).addClass('is-invalid').removeClass('is-valid');
      } else {
        $(this).removeClass('is-invalid').addClass('is-valid');
      }
    });

    // AJAX form submission
    $('#login-form').submit(function(e) {
      e.preventDefault();
      const form = $(this);
      
      // Reset validation
      form.find('.is-invalid').removeClass('is-invalid');
      form.find('.invalid-feedback').text('').hide();
      
      // Validate all required fields
      let isValid = true;
      form.find('[required]').each(function() {
        if ($(this).val() === '') {
          $(this).addClass('is-invalid');
          $(this).next('.invalid-feedback').text('This field is required').show();
          isValid = false;
        }
      });
      
      if (!isValid) {
        toastr.error('Please fill in all required fields');
        return;
      }
      
      // Show loading state
      const submitBtn = form.find('[type="submit"]');
      submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Signing in...');
      
      $.ajax({
        url: form.attr('action') || "{{ route('login') }}",
        type: "POST",
        data: form.serialize(),
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            toastr.success(response.message);
            window.location.href = response.redirect || "{{ route('home') }}";
          } else {
            toastr.error(response.message || 'Login failed. Please try again.');
          }
        },
        error: function(xhr) {
          submitBtn.prop('disabled', false).text('Sign In');
          
          if (xhr.status === 422) {
            // Validation errors
            const errors = xhr.responseJSON.errors;
            $.each(errors, function(key, value) {
              const input = form.find('[name="' + key + '"]');
              const errorField = form.find('.' + key + '-error');
              input.addClass('is-invalid');
              errorField.text(value[0]).show();
            });
          } else if (xhr.responseJSON && xhr.responseJSON.message) {
            toastr.error(xhr.responseJSON.message);
          } else {
            toastr.error('An error occurred. Please try again.');
          }
        }
      });
    });
  });
</script>