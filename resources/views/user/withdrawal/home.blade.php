<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ziirielcontemporaryartgallery Account - Withdrawal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
  </style>
</head>
<body>

  <nav class="navbar navbar-light bg-white border-bottom">
    <div class="container d-flex justify-content-between">
      <a class="navbar-brand fw-bold fs-3" href="#">
        <img class="sticky-logo" src="{{asset('images/logo.png')}}" width="100" alt="Ziirielcontemporaryartgallery">
      </a>
      <div class="d-flex align-items-center">
        <span class="me-3 fw-bold">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</span>
        <img src="user-icon.png" alt="User" width="30">
      </div>
    </div>
  </nav>

  <div class="container">
    <form>
      <div class="row mt-5">
        <h5 class="text-center mb-4 text-uppercase fw-bolder">Withdrawal</h5>
        <div class="col-lg-6 col-md-10 mx-auto px-3">
          <div class="mb-3">
            <label for="withdrawAmount" class="form-label fw-bold">Amount</label>
            <input type="number" id="withdrawAmount" name="withdraw_amount" class="form-control" placeholder="Enter Amount" required>
          </div>
          <div class="mb-3">
            <label for="withdrawMethod" class="form-label fw-bold">Withdrawal Method</label>
            <select id="withdrawMethod" name="withdraw_method" class="form-control" required>
              <option value="">Select Withdrawal Method</option>
              <option value="crypto">Crypto</option>
              <option value="bank">Direct Bank Transfer</option>
              <option value="paypal">PayPal</option>
            </select>
          </div>
          <div>
            <button type="submit" class="btn btn-dark w-100">Withdraw</button>
          </div>
        </div>
      </div>
    </form>
  </div>

</body>
</html>
