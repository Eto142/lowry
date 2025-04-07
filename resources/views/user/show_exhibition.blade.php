@include("user.header")

<style>
    .exhibition-image {
        width: 50%;
        height: 200px;
        /* Reduced height */
        object-fit: cover;
        margin-bottom: 20px;
        border-radius: 8px;
        /* Added rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Subtle shadow */
    }

    .details-container {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .buy-now-btn {
        background-color: #28a745;
        color: #fff;
        padding: 12px 25px;
        border: none;
        border-radius: 6px;
        font-size: 18px;
        margin-top: 20px;
        transition: 0.3s;
    }

    .buy-now-btn:hover {
        background-color: #218838;
    }

    .seller-info {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-top: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <img src="{{ asset($exhibition->picture) }}" class="exhibition-image" alt="{{ $exhibition->title }}">
        </div>

        <div class="col-md-8">
            <div class="details-container">
                <h2>{{ $exhibition->title }}</h2>
                <p class="text-muted">{{ $exhibition->date->format('F j, Y') }}</p>
                <p class="lead text-success">${{ number_format($exhibition->amount_sold, 2) }}</p>
                <p>{{ $exhibition->description }}</p>

                <button class="btn buy-now-btn" data-bs-toggle="modal" data-bs-target="#buyModal">
                    Buy Now
                </button>
            </div>
        </div>

        @if($exhibition->show_seller_contact)
        <div class="col-md-4">
            <div class="seller-info">
                <h4>Seller Information</h4>
                <p><strong>Name:</strong> {{ $exhibition->seller_name }}</p>
                @if($exhibition->seller_email)
                <p><strong>Email:</strong> {{ $exhibition->seller_email }}</p>
                @endif
                @if($exhibition->seller_phone)
                <p><strong>Phone:</strong> {{ $exhibition->seller_phone }}</p>
                @endif
                @if($exhibition->seller_address)
                <p><strong>Address:</strong> {{ $exhibition->seller_address }}</p>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Buy Modal -->
<div class="modal fade" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buyModalLabel">Purchase {{ $exhibition->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="purchaseForm">
                    @csrf
                    <input type="hidden" name="exhibition_id" value="{{ $exhibition->id }}">
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Your Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Your Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Your Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="submitPurchase()">Complete Purchase</button>
            </div>
        </div>
    </div>
</div>

<script>
    function submitPurchase() {
        const form = document.getElementById('purchaseForm');
        const formData = new FormData(form);
    
        fetch("{{ route('user.exhibition.purchase') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                toastr.success(data.message);
                $('#buyModal').modal('hide');
            } else {
                toastr.error(data.message);
            }
        })
        .catch(error => {
            toastr.error('An error occurred. Please try again.');
        });
    }
</script>

@include("user.footer")