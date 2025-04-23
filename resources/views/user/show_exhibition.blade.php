@include("user.header")

<style>
    /* Main Container */
    .exhibition-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 15px;
    }

    /* Hero Section */
    .exhibition-hero {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        margin-bottom: 3rem;
    }

    @media (min-width: 992px) {
        .exhibition-hero {
            flex-direction: row;
        }
    }

    /* Image Gallery */
    .exhibition-gallery {
        flex: 1;
        position: relative;
    }

    .main-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .main-image:hover {
        transform: scale(1.01);
    }

    /* Details Section */
    .exhibition-details {
        flex: 1;
        background: #fff;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .exhibition-title {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #222;
    }

    .exhibition-date {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .exhibition-date i {
        font-size: 1.1rem;
    }

    .exhibition-price {
        font-size: 1.8rem;
        font-weight: 700;
        color: #28a745;
        margin: 1.5rem 0;
    }

    .exhibition-description {
        font-size: 1.1rem;
        line-height: 1.7;
        color: #444;
        margin-bottom: 2rem;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-primary-action {
        background-color: #28a745;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .btn-primary-action:hover {
        background-color: #218838;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    }

    .btn-secondary-action {
        background-color: #f8f9fa;
        color: #495057;
        padding: 12px 30px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-secondary-action:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
    }

    /* Seller Info */
    .seller-info-card {
        background: #fff;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        margin-top: 2rem;
    }

    .seller-info-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: #222;
        position: relative;
        padding-bottom: 0.5rem;
    }

    .seller-info-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: #28a745;
    }

    .info-item {
        margin-bottom: 1rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .info-label {
        font-weight: 600;
        color: #495057;
        min-width: 80px;
    }

    .info-value {
        color: #6c757d;
        flex: 1;
    }

    /* Modal Styling */
    .purchase-modal .modal-content {
        border-radius: 12px;
        border: none;
    }

    .purchase-modal .modal-header {
        border-bottom: none;
        padding-bottom: 0;
    }

    .purchase-modal .modal-title {
        font-weight: 700;
        color: #222;
    }

    .purchase-modal .modal-body {
        padding-top: 0;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-control {
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #ced4da;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .exhibition-title {
            font-size: 1.8rem;
        }

        .exhibition-details {
            padding: 1.5rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-primary-action,
        .btn-secondary-action {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="exhibition-container">
    <div class="exhibition-hero">
        <div class="exhibition-gallery">
            <img src="{{ asset($exhibition->picture_url) }}" class="main-image" alt="{{ $exhibition->title }}">
        </div>

        <div class="exhibition-details">
            <h1 class="exhibition-title">{{ $exhibition->title }}</h1>
            <div class="exhibition-date">
                <i class="far fa-calendar-alt"></i>
                {{ $exhibition->date->format('F j, Y') }}
            </div>

            <div class="exhibition-price">${{ number_format($exhibition->amount_sold, 2) }}</div>

            <div class="exhibition-description">
                {{ $exhibition->description }}
            </div>

            <div class="action-buttons">
                <button class="btn-primary-action" data-bs-toggle="modal" data-bs-target="#buyModal">
                    <i class="fas fa-shopping-cart me-2"></i> Purchase Now
                </button>
                <button class="btn-secondary-action">
                    <i class="far fa-heart me-2"></i> Save for Later
                </button>
            </div>
        </div>
    </div>

    @if($exhibition->show_seller_contact)
    <div class="seller-info-card">
        <h3 class="seller-info-title">Seller Information</h3>

        <div class="info-item">
            <span class="info-label">Name:</span>
            <span class="info-value">{{ $exhibition->seller_name }}</span>
        </div>

        @if($exhibition->seller_email)
        <div class="info-item">
            <span class="info-label">Email:</span>
            <span class="info-value">
                <a href="mailto:{{ $exhibition->seller_email }}">{{ $exhibition->seller_email }}</a>
            </span>
        </div>
        @endif

        @if($exhibition->seller_phone)
        <div class="info-item">
            <span class="info-label">Phone:</span>
            <span class="info-value">
                <a href="tel:{{ $exhibition->seller_phone }}">{{ $exhibition->seller_phone }}</a>
            </span>
        </div>
        @endif

        @if($exhibition->seller_address)
        <div class="info-item">
            <span class="info-label">Address:</span>
            <span class="info-value">{{ $exhibition->seller_address }}</span>
        </div>
        @endif
    </div>
    @endif
</div>

<!-- Purchase Modal -->
<div class="modal fade purchase-modal" id="buyModal" tabindex="-1" aria-labelledby="buyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buyModalLabel">Purchase {{ $exhibition->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="purchaseForm">
                    @csrf
                    <input type="hidden" name="exhibition_id" value="{{ $exhibition->id }}">

                    <div class="mb-4">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label">Shipping Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success btn-lg py-3" onclick="submitPurchase()">
                            <i class="fas fa-check-circle me-2"></i> Confirm Purchase
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function submitPurchase() {
        const form = document.getElementById('purchaseForm');
        const formData = new FormData(form);
        
        // Show loading state
        const submitBtn = document.querySelector('#buyModal .btn-success');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
        submitBtn.disabled = true;
        
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
                // Show success message
                toastr.success(data.message);
                
                // Close modal after 2 seconds
                setTimeout(() => {
                    $('#buyModal').modal('hide');
                    
                    // Redirect or show confirmation page
                    if(data.redirect) {
                        window.location.href = data.redirect;
                    }
                }, 2000);
            } else {
                toastr.error(data.message);
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            toastr.error('An error occurred. Please try again.');
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        });
    }
</script>

@include("user.footer")