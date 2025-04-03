<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ziirielcontemporaryartgallery Account</title>
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
      <a class="navbar-brand fw-bold fs-3" href="#"><img class="sticky-logo" src="{{asset('images/logo.png')}}"
          width="100" alt="Ziirielcontemporaryartgallery"></a>
      <div class="d-flex align-items-center">
        <span class="me-3 fw-bold">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</span>
        <img src="user-icon.png" alt="User" width="30">
      </div>
    </div>
  </nav>

  <div class="container">
    <!-- Back Button Added Here -->
    <div class="back-button">
      <a href="{{ route('home') }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
      </a>
    </div>

    <form id="exhibitionForm" enctype="multipart/form-data">
      @csrf
      <div class="row mt-3">
        <!-- Reduced mt-5 to mt-3 to account for back button -->
        <h5 class="text-center mb-4 text-uppercase fw-bolder">Add a new exhibition</h5>
        <div class="col-lg-10 col-md-12 mx-auto px-3">
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="picture" class="form-label fw-bold">Picture *</label>
                <input type="file" id="picture" name="picture"
                  class="form-control @error('picture') is-invalid @enderror" required>
                @error('picture')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="amount_sold" class="form-label fw-bold">Amount</label>
                <input type="number" id="amount_sold" name="amount_sold"
                  class="form-control @error('amount_sold') is-invalid @enderror" placeholder="Enter Amount"
                  value="{{ old('amount_sold') }}">
                @error('amount_sold')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="title" class="form-label fw-bold">Title *</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                  placeholder="Enter Title" value="{{ old('title') }}" required>
                @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="description" class="form-label fw-bold">Description *</label>
                <textarea id="description" name="description"
                  class="form-control @error('description') is-invalid @enderror" placeholder="Enter Description"
                  required>{{ old('description') }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="date" class="form-label fw-bold">Exhibition Date *</label>
                <input type="date" id="date" name="date" class="form-control @error('date') is-invalid @enderror"
                  value="{{ old('date') }}" required>
                @error('date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="seller_name" class="form-label fw-bold">Seller's Name *</label>
                <input type="text" id="seller_name" name="seller_name"
                  class="form-control @error('seller_name') is-invalid @enderror" placeholder="Enter Seller's Name"
                  value="{{ old('seller_name') }}" required>
                @error('seller_name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="seller_email" class="form-label fw-bold">Seller's Email</label>
                <input type="email" id="seller_email" name="seller_email"
                  class="form-control @error('seller_email') is-invalid @enderror" placeholder="Enter Seller's Email"
                  value="{{ old('seller_email') }}">
                @error('seller_email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="seller_phone" class="form-label fw-bold">Seller's Phone</label>
                <input type="text" id="seller_phone" name="seller_phone"
                  class="form-control @error('seller_phone') is-invalid @enderror" placeholder="Enter Seller's Phone"
                  value="{{ old('seller_phone') }}">
                @error('seller_phone')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-3">
                <label for="seller_address" class="form-label fw-bold">Seller's Address</label>
                <input type="text" id="seller_address" name="seller_address"
                  class="form-control @error('seller_address') is-invalid @enderror"
                  placeholder="Enter Seller's Address" value="{{ old('seller_address') }}">
                @error('seller_address')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
          <div>
            <button type="submit" id="submitBtn" class="btn btn-dark w-100">Add exhibition</button>
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
            "preventDuplicates": false,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000"
        };

        // Form submission
        $('#exhibitionForm').on('submit', function(e) {
            e.preventDefault();
            
            var formData = new FormData(this);
            var submitBtn = $('#submitBtn');
            
            // Save original button text
            var originalText = submitBtn.html();
            
            // Show spinner and disable button
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating...');
            
            $.ajax({
                url: "{{ route('exhibitions.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.success) {
                        toastr.success(response.message);
                        $('#exhibitionForm')[0].reset();
                        // Optional: Redirect to dashboard after success
                        window.location.href = "{{ route('home') }}";
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if(xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('An error occurred. Please try again.');
                    }
                },
                complete: function() {
                    // Restore original button text and enable button
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });
    });
  </script>
</body>

</html>