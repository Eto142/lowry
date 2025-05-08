@include('user.header')

<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>

<div class="col-md-8">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h4><i class="bi bi-wallet2"></i> Withdrawal System</h4>
    </div>
    <div class="card-body">
      <!-- Balance and Withdrawal Form -->
      <div class="balance-card mb-4 p-3 bg-light rounded">
        <h5 class="text-secondary">Available Balance</h5>
        <h2 class="text-dark">${{ number_format(Auth::user()->balance->amount ?? 0, 2) }}</h2>
      </div>

      @if(Auth::user()->verifiedPaymentMethods()->count() > 0)
      <!-- Withdrawal Form -->
      <div class="withdrawal-form mb-4">
        <h5 class="mb-3">Request Withdrawal</h5>
        <form id="withdrawalRequestForm" action="{{ route('user.withdrawals.request') }}" method="POST">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label for="method" class="form-label">Withdrawal Method</label>
              <select class="form-select" id="method" name="method" required>
                <option value="">Select Method</option>
                @foreach(Auth::user()->paymentMethods as $method)
                <option value="{{ $method->method }}">{{ ucfirst($method->method) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label for="amount" class="form-label">Amount ($)</label>
              <input type="number" class="form-control" id="amount" name="amount" min="10"
                max="{{ Auth::user()->balance->amount ?? 0 }}" step="0.01" required>
              <small class="text-muted">Minimum withdrawal: $10.00</small>
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-send"></i> Submit Request
              </button>
            </div>
          </div>
        </form>
      </div>
      @else
      <!-- No Verified Payment Methods Alert -->
      <div class="alert alert-warning">
        <h5><i class="bi bi-exclamation-triangle"></i> No Verified Payment Methods</h5>
        <p>You need to add and verify at least one payment method before you can make withdrawals.</p>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentMethodModal">
          <i class="bi bi-plus-circle"></i> Add Payment Method
        </button>
      </div>
      @endif

      <!-- Payment Methods Section -->
      <div class="payment-methods mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5>Your Payment Methods</h5>
          <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentMethodModal">
            <i class="bi bi-plus"></i> Add New
          </button>
        </div>

        @if(Auth::user()->paymentMethods->count() > 0)
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Method</th>
                <th>Account Info</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach(Auth::user()->paymentMethods as $method)
              <tr>
                <td>{{ ucfirst($method->method) }}</td>
                <td>{{ $method->account_info }}</td>
                <td>
                  <span class="badge bg-{{ $method->is_verified ? 'success' : 'warning' }}">
                    {{ $method->is_verified ? 'Verified' : 'Pending Verification' }}
                  </span>
                </td>
                <td>
                  @if(!$method->is_verified)
                  <button class="btn btn-sm btn-outline-danger delete-method" data-id="{{ $method->id }}">
                    <i class="bi bi-trash">delete</i>
                  </button>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <div class="alert alert-info">
          <i class="bi bi-info-circle"></i> You haven't added any payment methods yet.
        </div>
        @endif
      </div>

      <!-- Withdrawal History -->
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
              @forelse($withdrawals as $withdrawal)
              <tr>
                <td>{{ $withdrawal->created_at->format('M d, Y') }}</td>
                <td>{{ ucfirst($withdrawal->method) }}</td>
                <td>${{ number_format($withdrawal->amount, 2) }}</td>
                <td>
                  <span class="badge bg-{{ 
                      $withdrawal->status == 'completed' ? 'success' : 
                      ($withdrawal->status == 'failed' ? 'danger' : 
                      ($withdrawal->status == 'processing' ? 'primary' : 'warning')) 
                    }}">
                    {{ ucfirst($withdrawal->status) }}
                  </span>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center">No withdrawal history yet</td>
              </tr>
              @endforelse
            </tbody>
          </table>
          {{ $withdrawals->links() }}
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Payment Method Modal -->
<div class="modal fade" id="addPaymentMethodModal" tabindex="-1" aria-labelledby="addPaymentMethodModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="addPaymentMethodModalLabel">Add Payment Method</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addPaymentMethodForm" action="{{ route('payment-methods.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="payment_method" class="form-label">Method</label>
            <select class="form-select" id="payment_method" name="method" required>
              <option value="">Select Method</option>
              <option value="bank">Bank Transfer</option>
              <option value="cashapp">Cash App</option>
              <option value="crypto">Crypto</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="account_info" class="form-label">Account Information</label>
            <textarea class="form-control" id="account_info" name="account_info" rows="3" required></textarea>
            <small class="text-muted">Provide all required details for this payment method</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit for Verification</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    // Initialize Toastr
    toastr.options = {
      closeButton: true,
      progressBar: true,
      positionClass: "toast-top-right",
      preventDuplicates: true
    };

    // Handle payment method form submission
    $('#addPaymentMethodForm').on('submit', function(e) {
      e.preventDefault();
      const form = $(this);
      const submitBtn = form.find('[type="submit"]');
      
      submitBtn.prop('disabled', true).html(`
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...
      `);

      $.ajax({
        url: form.attr('action'),
        type: "POST",
        data: form.serialize(),
        success: function(response) {
          toastr.success(response.message);
          form[0].reset();
          $('#addPaymentMethodModal').modal('hide');
          setTimeout(() => location.reload(), 1500);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors;
            for (const [field, messages] of Object.entries(errors)) {
              toastr.error(messages[0]);
            }
          } else {
            toastr.error(xhr.responseJSON?.message || 'An error occurred');
          }
        },
        complete: function() {
          submitBtn.prop('disabled', false).text('Submit for Verification');
        }
      });
    });

    // Handle withdrawal request form submission
    $('#withdrawalRequestForm').on('submit', function(e) {
      e.preventDefault();
      const form = $(this);
      const submitBtn = form.find('[type="submit"]');
      
      submitBtn.prop('disabled', true).html(`
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...
      `);

      $.ajax({
        url: form.attr('action'),
        type: "POST",
        data: form.serialize(),
        success: function(response) {
          toastr.success(response.message);
          $('.balance-card h2').text('$' + response.new_balance);
          setTimeout(() => location.reload(), 1500);
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            const errors = xhr.responseJSON.errors;
            for (const [field, messages] of Object.entries(errors)) {
              toastr.error(messages[0]);
            }
          } else {
            toastr.error(xhr.responseJSON?.message || 'An error occurred');
          }
        },
        complete: function() {
          submitBtn.prop('disabled', false).html('<i class="bi bi-send"></i> Submit Request');
        }
      });
    });

    // Handle delete payment method
    $('.delete-method').on('click', function() {
      const methodId = $(this).data('id');
      if (confirm('Are you sure you want to delete this payment method?')) {
        $.ajax({
          url: `/payment-methods/${methodId}`,
          type: "DELETE",
          data: {
            _token: "{{ csrf_token() }}"
          },
          success: function(response) {
            toastr.success(response.message);
            setTimeout(() => location.reload(), 1000);
          },
          error: function(xhr) {
            toastr.error(xhr.responseJSON?.message || 'Failed to delete payment method');
          }
        });
      }
    });
  });
</script>

@include('user.footer')