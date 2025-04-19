<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ziirielcontemporaryartgallery Account</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

    .wallet-info {
      display: none;
      background: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      margin-top: 15px;
      border: 1px solid #dee2e6;
    }

    .wallet-address-container {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 10px;
    }

    .wallet-address {
      word-break: break-all;
      font-family: monospace;
      background: #e9ecef;
      padding: 8px;
      border-radius: 3px;
      flex-grow: 1;
      margin-bottom: 0;
    }

    .copy-btn {
      cursor: pointer;
      background: #343a40;
      color: white;
      border: none;
      border-radius: 3px;
      padding: 8px 12px;
      transition: background 0.2s;
    }

    .copy-btn:hover {
      background: #23272b;
    }

    .copy-btn:active {
      transform: scale(0.98);
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-light bg-white border-bottom">
    <div class="container d-flex justify-content-between">
      <a class="navbar-brand fw-bold fs-3" href="{{route('home')}}"><img class="sticky-logo"
          src="{{asset('images/logo.png')}}" width="100" alt="Ziirielcontemporaryartgallery"></a>
      <div class="d-flex align-items-center">
        <span class="me-3 fw-bold">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</span>
        <img src="user-icon.png" alt="User" width="30">
      </div>
    </div>
  </nav>

  <div class="container">
    <form action="{{ route('deposit.store') }}" method="POST" id="depositForm">
      @csrf
      <div class="row mt-5">
        <h5 class="text-center mb-4 text-uppercase fw-bolder">Deposit</h5>
        <div class="col-lg-6 col-md-10 mx-auto px-3">
          <div class="mb-3">
            <label for="amount" class="form-label fw-bold">Amount (USD)</label>
            <input type="number" id="amount" name="amount" class="form-control" placeholder="Enter Amount" min="1"
              step="0.01" required>
          </div>
          <div class="mb-3">
            <label for="cryptoType" class="form-label fw-bold">Crypto Type</label>
            <select id="cryptoType" name="crypto_type" class="form-control" required>
              <option value="">Select Crypto Type</option>
              <option value="USDT (TRC20)">USDT (TRC20)</option>
              <option value="ETHEREUM (ERC20)">ETHEREUM (ERC20)</option>
              <option value="BTC">BTC</option>
            </select>
          </div>

          <div id="usdtWallet" class="wallet-info">
            <h6 class="fw-bold">USDT (TRC20) Wallet Address:</h6>
            <div class="wallet-address-container">
              <p class="wallet-address" id="usdtAddress">TH8Zgx34Qtv9fwD9yBYSBZ8qDHxWHQWrkK</p>
              <button type="button" class="copy-btn" onclick="copyToClipboard('usdtAddress')">Copy</button>
            </div>
            <p class="text-muted mt-2">Please send only USDT via TRC20 network to this address.</p>
          </div>

          <div id="ethWallet" class="wallet-info">
            <h6 class="fw-bold">ETHEREUM (ERC20) Wallet Address:</h6>
            <div class="wallet-address-container">
              <p class="wallet-address" id="ethAddress">TH8Zgx34Qtv9fwD9yBYSBZ8qDHxWHQWrkK</p>
              <button type="button" class="copy-btn" onclick="copyToClipboard('ethAddress')">Copy</button>
            </div>
            <p class="text-muted mt-2">Please send only ETH via ERC20 network to this address.</p>
          </div>

          <div id="btcWallet" class="wallet-info">
            <h6 class="fw-bold">BTC Wallet Address:</h6>
            <div class="wallet-address-container">
              <p class="wallet-address" id="btcAddress">1BVZQTG5MrAtFzajkB4tT14cNNxAQfeqrw</p>
              <button type="button" class="copy-btn" onclick="copyToClipboard('btcAddress')">Copy</button>
            </div>
            <p class="text-muted mt-2">Please send only BTC to this address.</p>
          </div>

          <div class="mb-3">
            <label for="transactionHash" class="form-label fw-bold">Transaction Hash</label>
            <input type="text" id="transactionHash" name="transaction_hash" class="form-control"
              placeholder="Enter Transaction Hash" required>
            <small class="text-muted">Please provide the transaction hash from your wallet.</small>
          </div>

          <div>
            <button type="submit" class="btn btn-dark w-100">Submit Deposit</button>
          </div>
        </div>
      </div>
    </form>
  </div>

  <script>
    // Initialize toastr
  toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "5000",
    "extendedTimeOut": "2000"
  };

  // Show wallet address based on selection
  document.getElementById('cryptoType').addEventListener('change', function() {
    // Hide all wallet info first
    document.querySelectorAll('.wallet-info').forEach(el => {
      el.style.display = 'none';
    });
    
    // Show the selected wallet info
    const selectedValue = this.value;
    if (selectedValue === 'USDT (TRC20)') {
      document.getElementById('usdtWallet').style.display = 'block';
    } else if (selectedValue === 'ETHEREUM (ERC20)') {
      document.getElementById('ethWallet').style.display = 'block';
    } else if (selectedValue === 'BTC') {
      document.getElementById('btcWallet').style.display = 'block';
    }
  });

  // Copy to clipboard function
  function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    const text = element.innerText;
    
    navigator.clipboard.writeText(text).then(() => {
      toastr.success('Wallet address copied to clipboard');
    }).catch(err => {
      toastr.error('Failed to copy address');
      console.error('Failed to copy: ', err);
    });
  }

  // Handle form submission with toastr
  document.getElementById('depositForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // You can add form validation here if needed
    
    // Submit the form via AJAX for better user experience
    fetch(this.action, {
      method: 'POST',
      body: new FormData(this),
      headers: {
        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        toastr.success(data.message || 'Deposit submitted successfully');
        this.reset();
        document.querySelectorAll('.wallet-info').forEach(el => {
          el.style.display = 'none';
        });
      } else {
        toastr.error(data.message || 'Error submitting deposit');
      }
    })
    .catch(error => {
      toastr.error('An error occurred. Please try again.');
      console.error('Error:', error);
    });
  });
  </script>

  @if(session('success'))
  <script>
    toastr.success('{{ session('success') }}');
  </script>
  @endif

  @if(session('error'))
  <script>
    toastr.error('{{ session('error') }}');
  </script>
  @endif
</body>

</html>