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
      <!-- Incomplete Withdrawal Alert -->
      @if($lastIncomplete = Auth::user()->withdrawals()->where('is_completed', false)->latest()->first())
      <div class="alert alert-info">
        <h5><i class="bi bi-info-circle"></i> Continue Pending Withdrawal</h5>
        <p>You have an incomplete {{ ucfirst($lastIncomplete->method) }} withdrawal request from {{
          $lastIncomplete->created_at->format('M d, Y h:i A') }}.</p>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
          data-bs-target="#{{ $lastIncomplete->method }}Modal">
          Continue Withdrawal
        </button>
      </div>
      @endif

      <div class="balance-card mb-4 p-3 bg-light rounded">
        <h5 class="text-secondary">Available Balance</h5>
        <h2 class="text-dark">${{ number_format(Auth::user()->balance->amount ?? 0, 2) }}</h2>
      </div>

      <!-- Withdrawal Method Buttons -->
      <div class="withdrawal-options mb-4">
        <h5 class="mb-3">Select Withdrawal Method</h5>
        <div class="row g-3">
          <div class="col-md-4">

            <button class="btn btn-outline-primary w-100 py-3 withdrawal-method" data-method="bank">
              <i class="bi bi-bank"></i> Bank Transfer
            </button>
          </div>
          <div class="col-md-4">
            <button class="btn btn-outline-success w-100 py-3 withdrawal-method" data-method="cashapp">
              <i class="bi bi-cash-coin"></i> Cash App
            </button>
          </div>
          <div class="col-md-4">
            <button class="btn btn-outline-warning w-100 py-3 withdrawal-method" data-method="crypto">
              <i class="bi bi-currency-bitcoin"></i> Crypto
            </button>
          </div>
        </div>
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
              @foreach($withdrawals as $withdrawal)
              <tr>
                <td>{{ $withdrawal->created_at->format('M d, Y') }}</td>
                <td>{{ ucfirst($withdrawal->method) }}</td>
                <td>${{ number_format($withdrawal->amount, 2) }}</td>
                <td>
                  <span class="badge bg-{{ 
                    $withdrawal->status == 'completed' ? 'success' : 
                    ($withdrawal->status == 'failed' ? 'danger' : 
                    ($withdrawal->is_completed ? 'warning' : 'secondary')) 
                  }}">
                    {{ $withdrawal->is_completed ? ucfirst($withdrawal->status) : 'Incomplete' }}
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

<!-- Withdrawal Modals -->
@include('user.withdrawal.modals')
@include('user.withdrawal.link-account-modals')

<script>
  $(document).ready(function() {
    // Initialize Toastr
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        preventDuplicates: true
    };

    // Handle withdrawal method clicks
    $('.withdrawal-method').click(function() {
        const method = $(this).data('method');
        const modal = $(`#${method}Modal`);
        
        // Check for existing incomplete withdrawal
        $.ajax({
            url: "{{ route('withdrawals.check') }}",
            type: "GET",
            success: function(response) {
                if (response.exists && response.method !== method) {
                    toastr.warning(`Please complete your pending ${response.method} withdrawal first`);
                    return;
                }
                modal.modal('show');
            }
        });
    });

    // Handle modal show event
    $('.withdrawal-modal').on('show.bs.modal', function(e) {
        const method = e.currentTarget.id.replace('Modal', '');
        
        $.ajax({
            url: "{{ route('withdrawals.initiate') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                method: method
            },
            success: function(response) {
                const form = $(`#${method}WithdrawalForm`);
                form.find('[name="withdrawal_id"]').remove();
                form.append(`<input type="hidden" name="withdrawal_id" value="${response.id}">`);
                
                if (!response.is_linked) {
                    $(`#link${method.charAt(0).toUpperCase() + method.slice(1)}Modal`).modal('show');
                }
            }
        });
    });

    // Handle withdrawal form submission
    $('.withdrawal-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('[type="submit"]');
        const formData = new FormData(form[0]);

        submitBtn.prop('disabled', true).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...
        `);

        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    form.closest('.modal').modal('hide');
                    $('.balance-card h2').text('$' + response.new_balance);
                    $('.withdrawal-history').load(location.href + ' .withdrawal-history > *');
                }
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
                submitBtn.prop('disabled', false).text('Request Withdrawal');
            }
        });
    });
});
</script>

@include('user.footer')