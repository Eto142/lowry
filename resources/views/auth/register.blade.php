@include("auth.header")

<div id="auth-modal" class="modal-overlay" style="display: none;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow">
          <div class="card-body p-4">
            <div class="row">
              <div class="col-md-8 border-end">
                <p class="mb-4">Please fill in the following details</p>

                <form id="registration-form" method="POST">
                  @csrf
                  <div class="mb-3">
                    <input type="text" name="first_name" class="form-control name-input py-1" placeholder="First Name"
                      required>
                  </div>

                  <div class="mb-3">
                    <input type="text" name="last_name" class="form-control name-input py-1" placeholder="Last Name"
                      required>
                  </div>

                  <div class="mb-3">
                    <div class="input-group">
                      <input type="email" name="email" class="form-control py-1 no-border-right" placeholder="E-mail"
                        required>
                      <span class="input-group-text">@</span>
                    </div>
                    <small class="text-muted">for sending order confirmation</small>
                  </div>

                  <div class="mb-3">
                    <div class="input-group">
                      <input type="tel" name="phone_number" class="form-control no-border-right py-1"
                        placeholder="Home Phone" required>
                      <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                    </div>
                    <small class="text-muted">for updates in case of schedule changes</small>
                  </div>

                  <div class="mb-3">
                    <div class="input-group">
                      <input type="text" name="country" class="form-control no-border-right py-1"
                        placeholder="Enter Country" required>
                      <span class="input-group-text"><i class="bi bi-globe"></i></span>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="input-group">
                      <input type="password" name="password" class="form-control no-border-right py-1"
                        placeholder="Password" required>
                      <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    </div>
                    <small class="text-muted">password</small>
                  </div>

                  <div class="mb-3">
                    <div class="input-group">
                      <input type="password" name="password_confirmation" class="form-control no-border-right py-1"
                        placeholder="Confirm Password" required>
                      <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    </div>
                    <small class="text-muted">confirm password</small>
                  </div>

                  <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary py-1">Back</button>
                    <div>
                      <button type="button" class="btn btn-outline-secondary me-2 py-1">Skip</button>
                      <button type="submit" class="btn btn-dark py-1 rounded-bit">Continue</button>
                    </div>
                  </div>
                </form>
              </div>

              <div class="col-md-4 d-flex flex-column justify-content-center align-items-center">
                <p class="text-center">Returning client?</p>
                <a href="{{ url('/login') }}" class="text-decoration-none">
                  <button class="btn btn-outline-dark py-1">Sign in now</button>
                </a>
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
</div>

@include("auth.footer")

<style>
  .form-control:invalid {
    border-color: #dc3545 !important;
  }

  .form-control:valid {
    border-color: #28a745 !important;
  }

  .input-group-text {
    background-color: transparent;
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">

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

  // Add red outline to invalid fields on input
  $('input').on('input', function() {
    if ($(this).val() === '') {
      $(this).removeClass('is-valid').addClass('is-invalid');
    } else {
      $(this).removeClass('is-invalid').addClass('is-valid');
    }
  });

  // Handle form submission
  $('#registration-form').on('submit', function(e) {
    e.preventDefault();
    
    // Validate password match
    const password = $('input[name="password"]').val();
    const confirmPassword = $('input[name="password_confirmation"]').val();
    
    if (password !== confirmPassword) {
      $('input[name="password_confirmation"]').addClass('is-invalid');
      toastr.error('Passwords do not match');
      return;
    } else {
      $('input[name="password_confirmation"]').removeClass('is-invalid').addClass('is-valid');
    }

    // Submit form via AJAX
    $.ajax({
      url: "{{ route('register.submit') }}",
      type: "POST",
      data: $(this).serialize(),
      success: function(response) {
        if (response.success) {
          toastr.success(response.message || 'Registration successful!');
          // Redirect or clear form as needed
          // window.location.href = response.redirect_url;
        } else {
          toastr.error(response.message || 'Registration failed. Please try again.');
        }
      },
      error: function(xhr) {
        if (xhr.status === 422) {
          // Validation errors
          const errors = xhr.responseJSON.errors;
          $.each(errors, function(key, value) {
            toastr.error(value[0]);
            $(`input[name="${key}"]`).addClass('is-invalid');
          });
        } else {
          toastr.error('An error occurred. Please try again.');
        }
      }
    });
  });
});
</script>