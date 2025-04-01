@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            <div class="mt-2 mb-4">
                <a href="{{ route('admin.exhibition.view', $exhibition->id) }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Item
                </a>
                <h1 class="title1 text-dark d-inline ml-3">Edit Exhibition Item</h1>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <form id="updateExhibitionForm" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-dark">Title *</label>
                                            <input type="text" class="form-control" name="title"
                                                value="{{ $exhibition->title }}" required>
                                            <small class="text-danger" id="title-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Description *</label>
                                            <textarea class="form-control" name="description" rows="5"
                                                required>{{ $exhibition->description }}</textarea>
                                            <small class="text-danger" id="description-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Image</label>
                                            <input type="file" class="form-control-file" name="picture">
                                            <small class="text-danger" id="picture-error"></small>
                                            <small class="text-muted">Max size: 2MB (JPEG, PNG, JPG, GIF)</small>
                                            @if($exhibition->picture)
                                            <div class="mt-2">
                                                <img src="{{ asset($exhibition->picture) }}" alt="Current Image"
                                                    style="max-width: 200px; max-height: 200px;">
                                                <input type="hidden" name="current_picture"
                                                    value="{{ $exhibition->picture }}">
                                            </div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Exhibition Type *</label>
                                            <select class="form-control" name="exhibition_type" required>
                                                <option value="past" {{ $exhibition->exhibition_type == 'past' ?
                                                    'selected' : '' }}>Past</option>
                                                <option value="current" {{ $exhibition->exhibition_type == 'current' ?
                                                    'selected' : '' }}>Current</option>
                                                <option value="future" {{ $exhibition->exhibition_type == 'future' ?
                                                    'selected' : '' }}>Future</option>
                                            </select>
                                            <small class="text-danger" id="exhibition_type-error"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Seller Information -->
                                        <div class="form-group">
                                            <label class="text-dark">Seller Name *</label>
                                            <input type="text" class="form-control" name="seller_name"
                                                value="{{ $exhibition->seller_name }}" required>
                                            <small class="text-danger" id="seller_name-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Seller Email</label>
                                            <input type="email" class="form-control" name="seller_email"
                                                value="{{ $exhibition->seller_email }}">
                                            <small class="text-danger" id="seller_email-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Seller Phone</label>
                                            <input type="text" class="form-control" name="seller_phone"
                                                value="{{ $exhibition->seller_phone }}">
                                            <small class="text-danger" id="seller_phone-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Seller Address</label>
                                            <textarea class="form-control" name="seller_address"
                                                rows="2">{{ $exhibition->seller_address }}</textarea>
                                            <small class="text-danger" id="seller_address-error"></small>
                                        </div>

                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="show_seller_contact"
                                                id="show_seller_contact" value="1" {{ $exhibition->show_seller_contact ?
                                            'checked' : '' }}>
                                            <label class="form-check-label" for="show_seller_contact">
                                                Show Seller Contact Information
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <!-- Buyer Information (only shown if status is sold) -->
                                        <div id="buyerInfoSection"
                                            style="display: {{ in_array($exhibition->exhibition_status, ['sold', 'pending', 'available']) ? 'block' : 'none' }};">
                                            <h5 class="text-dark mb-3">Buyer Information</h5>
                                            <div class="form-group">
                                                <label class="text-dark">Buyer Name</label>
                                                <input type="text" class="form-control" name="buyer_name"
                                                    value="{{ $exhibition->buyer_name }}">
                                                <small class="text-danger" id="buyer_name-error"></small>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-dark">Buyer Email</label>
                                                <input type="email" class="form-control" name="buyer_email"
                                                    value="{{ $exhibition->buyer_email }}">
                                                <small class="text-danger" id="buyer_email-error"></small>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-dark">Buyer Phone</label>
                                                <input type="text" class="form-control" name="buyer_phone"
                                                    value="{{ $exhibition->buyer_phone }}">
                                                <small class="text-danger" id="buyer_phone-error"></small>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-dark">Buyer Address</label>
                                                <textarea class="form-control" name="buyer_address"
                                                    rows="2">{{ $exhibition->buyer_address }}</textarea>
                                                <small class="text-danger" id="buyer_address-error"></small>
                                            </div>

                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox"
                                                    name="show_buyer_contact" id="show_buyer_contact" value="1" {{
                                                    $exhibition->show_buyer_contact ? 'checked' : '' }}>
                                                <label class="form-check-label" for="show_buyer_contact">
                                                    Show Buyer Contact Information
                                                </label>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-dark">Amount Sold ($)</label>
                                                <input type="number" step="0.01" class="form-control" name="amount_sold"
                                                    value="{{ $exhibition->amount_sold }}">
                                                <small class="text-danger" id="amount_sold-error"></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-dark">Status *</label>
                                            <select class="form-control" name="exhibition_status" id="exhibition_status"
                                                required>
                                                <option value="pending" {{ $exhibition->exhibition_status == 'pending' ?
                                                    'selected' : '' }}>Pending</option>
                                                <option value="available" {{ $exhibition->exhibition_status ==
                                                    'available' ? 'selected' : '' }}>Available</option>
                                                <option value="sold" {{ $exhibition->exhibition_status == 'sold' ?
                                                    'selected' : '' }}>Sold</option>
                                                <option value="reserved" {{ $exhibition->exhibition_status == 'reserved'
                                                    ? 'selected' : '' }}>Reserved</option>
                                            </select>
                                            <small class="text-danger" id="exhibition_status-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Date *</label>
                                            <input type="date" class="form-control" name="date"
                                                value="{{ $exhibition->date->format('Y-m-d') }}" required>
                                            <small class="text-danger" id="date-error"></small>
                                        </div>

                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="is_featured"
                                                id="is_featured" value="1" {{ $exhibition->is_featured ? 'checked' : ''
                                            }}>
                                            <label class="form-check-label" for="is_featured">
                                                Feature this exhibition
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save"></i> Update Exhibition
                                    </button>
                                    <div class="spinner-border text-primary ml-2" id="spinner" role="status"
                                        style="display: none;">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
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
        // Initialize toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000"
        };

        // Show/hide buyer info based on status
        $('#exhibition_status').change(function() {
            if ($(this).val() === 'sold' || $(this).val() === 'pending' || $(this).val() === 'available') {
                $('#buyerInfoSection').show();
            } else {
                $('#buyerInfoSection').hide();
            }
        });

        $('#updateExhibitionForm').on('submit', function(e) {
            e.preventDefault();
            
            // Reset error messages
            $('.text-danger').text('');
            
            // Disable button and show spinner
            $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
            $('#spinner').show();
            
            // Create FormData object
            let formData = new FormData(this);
            formData.append('admin_id', {{ Auth::guard('admin')->user()->id }});
            formData.append('_method', 'PUT');
            
            $.ajax({
                url: "{{ route('admin.exhibitions.update', $exhibition->id) }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    toastr.success(response.message);
                    
                    setTimeout(() => {
                        window.location.href = "{{ route('admin.exhibitions.index') }}";
                    }, 2000);
                },
                error: function(xhr) {
                    // Re-enable button and restore original text
                    $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Update Exhibition');
                    $('#spinner').hide();
                    
                    if(xhr.status === 422) {
                        // Validation errors
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $(`#${key}-error`).text(value[0]);
                        });
                        toastr.error('Please fix the form errors');
                    } else {
                        toastr.error(xhr.responseJSON?.message || 'An error occurred');
                    }
                }
            });
        });
    });
</script>

@include('admin.footer')