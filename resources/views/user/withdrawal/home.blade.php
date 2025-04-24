@include('user.header')

<!-- CDN Links -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>



<div class="col-md-8">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h4><i class="bi bi-wallet2"></i> Withdrawal System</h4>
    </div>
    <div class="card-body">
      <div class="balance-card mb-4 p-3 bg-light rounded">
        <h5 class="text-secondary">Available Balance</h5>
        <h2 class="text-dark">${{ number_format(Auth::user()->balance->amount ?? 0, 2) }}</h2>
      </div>

      <div class="withdrawal-options mb-4">
        <h5 class="mb-3">Select Withdrawal Method</h5>
        <div class="row g-3">
          <div class="col-md-4">
            <button class="btn btn-outline-primary w-100 py-3" data-bs-toggle="modal" data-bs-target="#bankModal">
              <i class="bi bi-bank"></i> Bank Transfer
            </button>
          </div>
          <div class="col-md-4">
            <button class="btn btn-outline-success w-100 py-3" data-bs-toggle="modal" data-bs-target="#cashappModal">
              <i class="bi bi-cash-coin"></i> Cash App
            </button>
          </div>
          <div class="col-md-4">
            <button class="btn btn-outline-warning w-100 py-3" data-bs-toggle="modal" data-bs-target="#cryptoModal">
              <i class="bi bi-currency-bitcoin"></i> Crypto
            </button>
          </div>
        </div>
      </div>

      <div class="withdrawal-history mt-5">
        <h5 class="mb-3">Withdrawal History</h5>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Date</th>
                <th>Method</th>
                <th>Amount</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($withdrawals as $withdrawal)
              <tr>
                <td>{{ $withdrawal->created_at->format('M d, Y') }}</td>
                <td>{{ ucfirst($withdrawal->method) }}</td>
                <td>${{ number_format($withdrawal->amount, 2) }}</td>
                <td>
                  <span class="badge bg-{{ 
                                                $withdrawal->status == 'completed' ? 'success' : 
                                                ($withdrawal->status == 'failed' ? 'danger' : 'warning') 
                                            }}">
                    {{ ucfirst($withdrawal->status) }}
                  </span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- <div class="col-md-4">
  <div class="card shadow">
    <div class="card-header bg-info text-white">
      <h5><i class="bi bi-info-circle"></i> Withdrawal Information</h5>
    </div>
    <div class="card-body">
      <ul class="list-group list-group-flush">
        <li class="list-group-item">
          <strong>Minimum Withdrawal:</strong> $10.00
        </li>
        <li class="list-group-item">
          <strong>Processing Time:</strong> 3-5 Business Days
        </li>
        <li class="list-group-item">
          <strong>Fees:</strong> No fees for withdrawals
        </li>
      </ul>
    </div>
  </div>
</div> --}}



<!-- Bank Transfer Modal -->
<div class="modal fade" id="bankModal" tabindex="-1" aria-labelledby="bankModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bankModalLabel"><i class="bi bi-bank"></i> Bank Transfer Withdrawal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @if(!auth()->user()->bank_account_linked)
        <div class="alert alert-warning">
          <h5><i class="bi bi-exclamation-triangle"></i> Account Not Linked</h5>
          <p>To withdraw via bank transfer, you need to link your bank account first. Please contact support to link
            your external bank account to your Gallery account.</p>
          <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#linkBankModal">
            Request Account Linking
          </button>
        </div>
        @else
        <form id="bankWithdrawalForm" class="withdrawal-form" action="{{ route('withdrawals.request') }}" method="POST">
          @csrf
          <input type="hidden" name="method" value="bank">
          <div class="mb-3">
            <label for="bankAmount" class="form-label">Amount ($)</label>
            <input type="number" step="0.01" min="10" class="form-control" id="bankAmount" name="amount" required>
            <div class="invalid-feedback"></div>
            <small class="text-muted">Minimum: $10.00</small>
          </div>
          <div class="mb-3">
            <label class="form-label">Linked Bank Account</label>
            <div class="card p-3 bg-light">
              <strong>Bank Name:</strong> {{ auth()->user()->bank_name }}<br>
              <strong>Account Number:</strong> ****{{ substr(auth()->user()->bank_account_number, -4) }}<br>
              <strong>Account Name:</strong> {{ auth()->user()->bank_account_name }}
            </div>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Request Withdrawal</button>
          </div>
        </form>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Cash App Modal -->
<div class="modal fade" id="cashappModal" tabindex="-1" aria-labelledby="cashappModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cashappModalLabel"><i class="bi bi-cash-coin"></i> Cash App Withdrawal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @if(!auth()->user()->cashapp_linked)
        <div class="alert alert-warning">
          <h5><i class="bi bi-exclamation-triangle"></i> Cash App Not Linked</h5>
          <p>To withdraw via Cash App, you need to link your Cash App account first. Please contact support to link your
            external Cash App account to your Gallery account.</p>
          <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#linkCashappModal">
            Request Account Linking
          </button>
        </div>
        @else
        <form id="cashappWithdrawalForm" class="withdrawal-form" action="{{ route('withdrawals.request') }}"
          method="POST">
          @csrf
          <input type="hidden" name="method" value="cashapp">
          <div class="mb-3">
            <label for="cashappAmount" class="form-label">Amount ($)</label>
            <input type="number" step="0.01" min="10" class="form-control" id="cashappAmount" name="amount" required>
            <div class="invalid-feedback"></div>
            <small class="text-muted">Minimum: $10.00</small>
          </div>
          <div class="mb-3">
            <label class="form-label">Linked Cash App</label>
            <div class="card p-3 bg-light">
              <strong>Cash App ID:</strong> {{ auth()->user()->cashapp_id }}<br>
            </div>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-success">Request Withdrawal</button>
          </div>
        </form>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Crypto Modal -->
<div class="modal fade" id="cryptoModal" tabindex="-1" aria-labelledby="cryptoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cryptoModalLabel"><i class="bi bi-currency-bitcoin"></i> Crypto Withdrawal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @if(!auth()->user()->crypto_wallet_linked)
        <div class="alert alert-warning">
          <h5><i class="bi bi-exclamation-triangle"></i> Crypto Wallet Not Linked</h5>
          <p>To withdraw via cryptocurrency, you need to link your crypto wallet first. Please contact support to link
            your external crypto wallet to your Gallery account.</p>
          <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#linkCryptoModal">
            Request Account Linking
          </button>
        </div>
        @else
        <form id="cryptoWithdrawalForm" class="withdrawal-form" action="{{ route('withdrawals.request') }}"
          method="POST">
          @csrf
          <input type="hidden" name="method" value="crypto">
          <div class="mb-3">
            <label for="cryptoAmount" class="form-label">Amount ($)</label>
            <input type="number" step="0.01" min="10" class="form-control" id="cryptoAmount" name="amount" required>
            <div class="invalid-feedback"></div>
            <small class="text-muted">Minimum: $10.00</small>
          </div>
          <div class="mb-3">
            <label class="form-label">Linked Crypto Wallet</label>
            <div class="card p-3 bg-light">
              <strong>Wallet Address:</strong> {{ substr(auth()->user()->crypto_wallet_address, 0, 10) }}...{{
              substr(auth()->user()->crypto_wallet_address, -10) }}<br>
              <strong>Network:</strong> {{ auth()->user()->crypto_network }}
            </div>
          </div>
          <div class="mb-3">
            <label for="cryptoType" class="form-label">Cryptocurrency</label>
            <select class="form-select" id="cryptoType" name="crypto_type" required>
              <option value="bitcoin">Bitcoin (BTC)</option>
              <option value="ethereum">Ethereum (ETH)</option>
              <option value="usdt">USDT (ERC20)</option>
              <option value="usdc">USDC (ERC20)</option>
            </select>
            <div class="invalid-feedback"></div>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-warning">Request Withdrawal</button>
          </div>
        </form>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Account Linking Modals -->
@include('user.withdrawal.link-account-modals')

<script>
  $(document).ready(function() {
    // Initialize Toastr
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

    // Handle withdrawal forms
    $('.withdrawal-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('[type="submit"]');
        
        // Clear previous errors
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');
        
        // Validate required fields
        let isValid = true;
        form.find('[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            }
        });
        
        if (!isValid) {
            toastr.error('Please fill in all required fields');
            return;
        }

        // Show loading state
        submitBtn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...
        `);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    form.closest('.modal').modal('hide');
                    form.trigger('reset');
                    
                    // Update balance display
                    $('.balance-card h2').text('$' + response.new_balance);
                    
                    // Reload withdrawal history
                    $('.withdrawal-history').load(location.href + ' .withdrawal-history > *');
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        const input = form.find('[name="' + key + '"]');
                        input.addClass('is-invalid')
                            .next('.invalid-feedback').text(value[0]);
                    });
                } else {
                    toastr.error(xhr.responseJSON?.message || 'An error occurred. Please try again.');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).text('Request Withdrawal');
            }
        });
    });

    // Handle account linking forms
    $('.account-linking-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('[type="submit"]');
        
        submitBtn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...
        `);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    form.closest('.modal').modal('hide');
                    form.trigger('reset');
                    // Reload page to update account status
                    setTimeout(() => location.reload(), 1500);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        const input = form.find('[name="' + key + '"]');
                        input.addClass('is-invalid')
                            .next('.invalid-feedback').text(value[0]);
                    });
                } else {
                    toastr.error(xhr.responseJSON?.message || 'An error occurred. Please try again.');
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).text('Submit Request');
            }
        });
    });

    // Input validation on blur
    $('input, select').on('blur', function() {
        if ($(this).prop('required') && !$(this).val()) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
});
</script>

</div>

</div>
</body>

</html>