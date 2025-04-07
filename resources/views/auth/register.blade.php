@include("auth.header")

<div id="auth-modal" class="modal-overlay">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow">
          <div class="card-body p-4">
            <div class="row">
              <div class="col-md-8 border-end">
                <p class="mb-4">Please fill in the following details</p>

                <form id="registration-form" method="POST" action="{{ route('register.submit') }}">
                  @csrf
                  <div class="mb-3">
                    <input type="text" name="first_name" class="form-control py-1" placeholder="First Name" required>
                  </div>

                  <div class="mb-3">
                    <input type="text" name="last_name" class="form-control py-1" placeholder="Last Name" required>
                  </div>

                  <div class="mb-3">
                    <div class="input-group">
                      <input type="email" name="email" class="form-control py-1" placeholder="E-mail" required>
                      <span class="input-group-text">@</span>
                    </div>
                    <small class="text-muted">For sending order confirmation</small>
                  </div>

                  <div class="mb-3">
                    <div class="input-group">
                      <input type="tel" name="phone_number" class="form-control py-1" placeholder="Home Phone" required>
                      <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                    </div>
                    <small class="text-muted">For updates in case of schedule changes</small>
                  </div>

                  <div class="mb-3">
                    <div class="input-group">
                      <input type="text" name="country" class="form-control py-1" placeholder="Enter Country" required>
                      <span class="input-group-text"><i class="bi bi-globe"></i></span>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="input-group">
                      <input type="password" name="password" class="form-control py-1" placeholder="Password" required>
                      <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    </div>
                  </div>

                  <div class="mb-3">
                    <div class="input-group">
                      <input type="password" name="password_confirmation" class="form-control py-1"
                        placeholder="Confirm Password" required>
                      <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    </div>
                  </div>

                  @if(isset($referral_code))
                  <input type="hidden" name="referral_code" value="{{ $referral_code }}">
                  @endif

                  <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary py-1"
                      onclick="window.history.back();">Back</button>
                    <button type="submit" class="btn btn-dark py-1">Continue</button>
                  </div>
                </form>
              </div>

              <div class="col-md-4 d-flex flex-column justify-content-center align-items-center">
                <p class="text-center">Returning client?</p>
                <a href="{{ route('login') }}" class="text-decoration-none">
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

@include("auth.footer")

<!-- Styles -->
<style>
  .form-control.is-invalid {
    border-color: #dc3545 !important;
  }

  .form-control.is-valid {
    border-color: #28a745 !important;
  }

  .input-group-text {
    background-color: transparent;
  }
</style>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.css">

<script>
  $(document).ready(function() {
    toastr.options = {
      "closeButton": true, "progressBar": true, "positionClass": "toast-top-right",
      "showDuration": "300", "hideDuration": "1000", "timeOut": "5000"
    };

    // Validation for empty inputs
    $('input').on('input', function() {
      $(this).toggleClass('is-valid', $(this).val() !== '');
      $(this).toggleClass('is-invalid', $(this).val() === '');
    });

    $('#registration-form').on('submit', function(e) {
      e.preventDefault();
      
      const password = $('input[name="password"]').val();
      const confirmPassword = $('input[name="password_confirmation"]').val();
      
      if (password !== confirmPassword) {
        toastr.error('Passwords do not match');
        $('input[name="password_confirmation"]').addClass('is-invalid');
        return;
      }

      $.ajax({
        url: $(this).attr('action'),
        type: "POST",
        data: $(this).serialize(),
        success: function(response) {
          if (response.success) {
            toastr.success(response.message);
            window.location.href = response.redirect_url;
          } else {
            toastr.error(response.message || 'Registration failed. Try again.');
          }
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            $.each(xhr.responseJSON.errors, function(key, value) {
              toastr.error(value[0]);
              $(`input[name="${key}"]`).addClass('is-invalid');
            });
          } else {
            toastr.error('An error occurred. Try again.');
          }
        }
      });
    });
  });
</script>