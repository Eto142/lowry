<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ziiriel-arthouse Account</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <style>
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

    .is-invalid {
      border-color: #dc3545;
    }

    .invalid-feedback {
      color: #dc3545;
      display: block;
    }

    .back-button {
      margin-bottom: 20px;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-light bg-white border-bottom">
    <div class="container d-flex justify-content-between">
      <a class="navbar-brand fw-bold fs-3" href="#">
        <img class="sticky-logo" src="{{asset('images/logo.jpeg')}}" width="100" alt="ziiriel-arthouse">
      </a>
      <div class="d-flex align-items-center">
        <span class="me-3 fw-bold">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</span>
        <img src="user-icon.png" alt="User" width="30">
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="back-button">
      <a href="{{ route('home') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
      </a>
    </div>

    <form id="exhibitionForm" enctype="multipart/form-data">
      @csrf

      <div class="row mt-5">
        <h5 class="text-center mb-4 text-uppercase fw-bolder">Add a new exhibition</h5>
        <div class="col-lg-10 col-md-12 mx-auto px-3">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="picture" class="form-label fw-bold">Picture *</label>
                <input type="file" id="picture" name="picture" class="form-control" required>
                <small class="text-muted">Max size: 2MB (JPEG, PNG, JPG)</small>
                <div class="invalid-feedback" id="picture-error"></div>
              </div>
              <div class="mb-3">
                <label for="title" class="form-label fw-bold">Title *</label>
                <input type="text" class="form-control" id="title" name="title" required>
                <div class="invalid-feedback" id="title-error"></div>
              </div>
              <div class="mb-3">
                <label for="description" class="form-label fw-bold">Description *</label>
                <textarea id="description" name="description" class="form-control" required></textarea>
                <div class="invalid-feedback" id="description-error"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="seller_name" class="form-label fw-bold">Seller's Name *</label>
                <input type="text" id="seller_name" name="seller_name" class="form-control" required>
                <div class="invalid-feedback" id="seller_name-error"></div>
              </div>
              <div class="mb-3">
                <label for="seller_email" class="form-label fw-bold">Seller's Email</label>
                <input type="email" id="seller_email" name="seller_email" class="form-control">
                <div class="invalid-feedback" id="seller_email-error"></div>
              </div>
              <div class="mb-3">
                <label for="seller_phone" class="form-label fw-bold">Seller's Phone</label>
                <input type="text" id="seller_phone" name="seller_phone" class="form-control">
                <div class="invalid-feedback" id="seller_phone-error"></div>
              </div>
              <div class="mb-3">
                <label for="date" class="form-label fw-bold">Exhibition Date *</label>
                <input type="date" id="date" name="date" class="form-control" required>
                <div class="invalid-feedback" id="date-error"></div>
              </div>
              <div class="mb-3">
                <label for="amount_sold" class="form-label fw-bold">Amount Sold *</label>
                <input type="number" step="0.01" id="amount_sold" name="amount_sold" class="form-control" required>
                <div class="invalid-feedback" id="amount_sold-error"></div>
              </div>
            </div>
          </div>
          <div>
            <button type="submit" id="submitBtn" class="btn btn-dark w-100">Add exhibition</button>
            <div class="spinner-border text-primary mt-2" id="spinner" role="status" style="display: none;">
              <span class="sr-only">Loading...</span>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

  <script>
    $(document).ready(function() {
      // Initialize toastr
      toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
      };

      $('#exhibitionForm').on('submit', function(e) {
        e.preventDefault();
        
        // Reset error messages
        $('.invalid-feedback').text('');
        $('.form-control').removeClass('is-invalid');
        
        // Validate file type
        const pictureInput = document.getElementById('picture');
        if (pictureInput.files.length > 0) {
          const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
          if (!allowedTypes.includes(pictureInput.files[0].type)) {
            $('#picture-error').text('Invalid file type. Only JPEG, PNG, JPG allowed.');
            $('#picture').addClass('is-invalid');
            return false;
          }
        }
        
        // Disable button and show spinner
        const submitBtn = $('#submitBtn');
        const originalBtnText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating...');
        $('#spinner').show();
        
        // Create FormData object
        let formData = new FormData(this);
        
        $.ajax({
          url: "{{ route('exhibitions.store') }}",
          type: "POST",
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            toastr.success(response.message);
            $('#exhibitionForm')[0].reset();
            setTimeout(() => {
              window.location.href = response.redirect || "{{ route('home') }}";
            }, 1500);
          },
          error: function(xhr) {
            // Re-enable button and restore original text
            submitBtn.prop('disabled', false).html(originalBtnText);
            $('#spinner').hide();
            
            if(xhr.status === 422) {
              // Validation errors
              const errors = xhr.responseJSON.errors;
              $.each(errors, function(key, value) {
                $(`#${key}-error`).text(value[0]);
                $(`#${key}`).addClass('is-invalid');
              });
              toastr.error('Please correct the form errors');
            } else {
              toastr.error(xhr.responseJSON?.message || 'An unexpected error occurred');
            }
          }
        });
      });
    });
  </script>
</body>

</html>