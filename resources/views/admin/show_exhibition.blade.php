@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            <div id="message-container"></div>

            <div class="mt-2 mb-4">
                <a href="{{ route('admin.exhibitions.index') }}" class="btn btn-light"><i class="fas fa-arrow-left"></i>
                    Back to List</a>
                <h1 class="title1 text-dark d-inline ml-3">Exhibition Item Details</h1>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    @if($exhibition->picture)
                                    <img src="{{ asset('storage/'.$exhibition->picture) }}"
                                        alt="{{ $exhibition->title }}" class="img-fluid rounded"
                                        style="max-height: 400px; width: 100%; object-fit: cover;">
                                    @else
                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                        style="height: 300px; width: 100%;">
                                        <i class="fas fa-image fa-5x text-muted"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h2 class="text-dark">{{ $exhibition->title }}</h2>
                                    <p class="text-muted">Added on {{ $exhibition->created_at->format('M d, Y') }}</p>

                                    <div class="mb-3">
                                        <span class="badge 
                                            @if($exhibition->exhibition_status == 'available') badge-success
                                            @elseif($exhibition->exhibition_status == 'sold') badge-danger
                                            @else badge-warning @endif">
                                            {{ ucfirst($exhibition->exhibition_status) }}
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <h5 class="text-dark">Description</h5>
                                        <p>{{ $exhibition->description }}</p>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="text-dark">Seller</h5>
                                            <p>{{ $exhibition->seller_name ?? 'Not specified' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="text-dark">Buyer</h5>
                                            <p>{{ $exhibition->buyer_name ?? 'Not specified' }}</p>
                                        </div>
                                    </div>

                                    @if($exhibition->exhibition_status == 'sold')
                                    <div class="alert alert-success">
                                        <h5 class="text-dark">Sold for</h5>
                                        <h4>${{ number_format($exhibition->amount_sold, 2) }}</h4>
                                        <p class="mb-0">Date: {{ $exhibition->date ?
                                            \Carbon\Carbon::parse($exhibition->date)->format('M d, Y') : 'Not specified'
                                            }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow">
                        <div class="card-header bg-light">
                            <h4 class="card-title text-dark">Item Actions</h4>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('admin.exhibitions.edit', $exhibition->id) }}"
                                class="btn btn-primary btn-block mb-3">
                                <i class="fas fa-edit"></i> Edit Item
                            </a>

                            <form id="statusForm" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="text-dark">Change Status</label>
                                    <select name="exhibition_status" class="form-control">
                                        <option value="available" {{ $exhibition->exhibition_status == 'available' ?
                                            'selected' : '' }}>Available</option>
                                        <option value="sold" {{ $exhibition->exhibition_status == 'sold' ? 'selected' :
                                            '' }}>Sold</option>
                                        <option value="reserved" {{ $exhibition->exhibition_status == 'reserved' ?
                                            'selected' : '' }}>Reserved</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-info btn-block">
                                    <i class="fas fa-sync-alt"></i> Update Status
                                </button>
                            </form>

                            <hr>

                            <form id="deleteForm" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fas fa-trash"></i> Delete Item
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
    // Status Update Form
    $('#statusForm').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('admin.exhibitions.update-status', $exhibition->id) }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                showMessage('success', response.message);
                // Update status badge
                const badge = $('.badge');
                badge.removeClass('badge-success badge-danger badge-warning');
                
                if(response.new_status === 'available') {
                    badge.addClass('badge-success').text('Available');
                } else if(response.new_status === 'sold') {
                    badge.addClass('badge-danger').text('Sold');
                    // Reload to show sold details
                    location.reload();
                } else {
                    badge.addClass('badge-warning').text('Reserved');
                }
            },
            error: function(xhr) {
                showMessage('danger', xhr.responseJSON?.message || 'An error occurred');
            }
        });
    });

    // Delete Form
    $('#deleteForm').submit(function(e) {
        e.preventDefault();
        
        if(!confirm('Are you sure you want to delete this item?')) return;
        
        $.ajax({
            url: "{{ route('admin.exhibitions.destroy', $exhibition->id) }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                window.location.href = "{{ route('admin.exhibitions.index') }}";
            },
            error: function(xhr) {
                showMessage('danger', xhr.responseJSON?.message || 'An error occurred');
            }
        });
    });

    function showMessage(type, message) {
        const container = $('#message-container');
        container.html(`<div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>`);
        
        setTimeout(() => {
            container.find('.alert').alert('close');
        }, 5000);
    }
});
</script>

@include('admin.footer')