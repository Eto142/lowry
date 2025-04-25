@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            <div class="mt-2 mb-4">
                <a href="{{ route('admin.exhibitions.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <h1 class="title1 text-dark d-inline ml-3">Add New Exhibition</h1>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <form id="createExhibitionForm" enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-dark">Title *</label>
                                            <input type="text" class="form-control" name="title" required>
                                            <small class="text-danger" id="title-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Description *</label>
                                            <textarea class="form-control" name="description" rows="5"
                                                required></textarea>
                                            <small class="text-danger" id="description-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Image *</label>
                                            <input type="file" class="form-control-file" name="picture"
                                                id="pictureInput" required>
                                            <small class="text-danger" id="picture-error"></small>
                                            <small class="text-muted">Max size: 2MB (JPEG, PNG, JPG, GIF)</small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Video (Optional)</label>
                                            <input type="file" class="form-control-file" name="video" id="videoInput">
                                            <small class="text-danger" id="video-error"></small>
                                            <small class="text-muted">Max size: 10MB (MP4, MOV, AVI)</small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Exhibition Type *</label>
                                            <select class="form-control" name="exhibition_type" required>
                                                <option value="past">Past</option>
                                                <option value="current" selected>Current</option>
                                                <option value="future">Future</option>
                                                <option value="static">Static</option>
                                            </select>
                                            <small class="text-danger" id="exhibition_type-error"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Seller Information -->
                                        <div class="form-group">
                                            <label class="text-dark">Seller Name *</label>
                                            <input type="text" class="form-control" name="seller_name" required>
                                            <small class="text-danger" id="seller_name-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Seller Email</label>
                                            <input type="email" class="form-control" name="seller_email">
                                            <small class="text-danger" id="seller_email-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Seller Phone</label>
                                            <input type="text" class="form-control" name="seller_phone">
                                            <small class="text-danger" id="seller_phone-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Seller Address</label>
                                            <textarea class="form-control" name="seller_address" rows="2"></textarea>
                                            <small class="text-danger" id="seller_address-error"></small>
                                        </div>

                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="show_seller_contact"
                                                id="show_seller_contact" value="1" checked>
                                            <label class="form-check-label" for="show_seller_contact">
                                                Show Seller Contact Information
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <!-- Buyer Information (only shown if status is sold) -->
                                        <div id="buyerInfoSection" style="display: none;">
                                            <h5 class="text-dark mb-3">Buyer Information</h5>
                                            <div class="form-group">
                                                <label class="text-dark">Buyer Name</label>
                                                <input type="text" class="form-control" name="buyer_name">
                                                <small class="text-danger" id="buyer_name-error"></small>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-dark">Buyer Email</label>
                                                <input type="email" class="form-control" name="buyer_email">
                                                <small class="text-danger" id="buyer_email-error"></small>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-dark">Buyer Phone</label>
                                                <input type="text" class="form-control" name="buyer_phone">
                                                <small class="text-danger" id="buyer_phone-error"></small>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-dark">Buyer Address</label>
                                                <textarea class="form-control" name="buyer_address" rows="2"></textarea>
                                                <small class="text-danger" id="buyer_address-error"></small>
                                            </div>

                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox"
                                                    name="show_buyer_contact" id="show_buyer_contact" value="1" checked>
                                                <label class="form-check-label" for="show_buyer_contact">
                                                    Show Buyer Contact Information
                                                </label>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-dark">Amount Sold ($)</label>
                                                <input type="number" step="0.01" class="form-control"
                                                    name="amount_sold">
                                                <small class="text-danger" id="amount_sold-error"></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-dark">Status *</label>
                                            <select class="form-control" name="exhibition_status" id="exhibition_status"
                                                required>
                                                <option value="pending">Pending</option>
                                                <option value="available">Available</option>
                                                <option value="sold">Sold</option>
                                                <option value="reserved">Reserved</option>
                                            </select>
                                            <small class="text-danger" id="exhibition_status-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Date *</label>
                                            <input type="date" class="form-control" name="date" required>
                                            <small class="text-danger" id="date-error"></small>
                                        </div>

                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="is_featured"
                                                id="is_featured" value="1" checked>
                                            <label class="form-check-label" for="is_featured">
                                                Feature this exhibition
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-plus-circle"></i> Add Exhibition
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
            if ($(this).val() === 'sold') {
                $('#buyerInfoSection').show();
            } else {
                $('#buyerInfoSection').hide();
            }
        }).trigger('change');

        $('#createExhibitionForm').on('submit', function(e) {
            e.preventDefault();
            
            // Reset error messages
            $('.text-danger').text('');
            
            // Disable button and show spinner
            const submitBtn = $('#submitBtn');
            const originalBtnText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating...');
            $('#spinner').show();
            
            // Validate file types
            const pictureInput = document.getElementById('pictureInput');
            const videoInput = document.getElementById('videoInput');
            
            // Validate image
            if (pictureInput.files.length > 0) {
                const allowedImageTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!allowedImageTypes.includes(pictureInput.files[0].type)) {
                    $('#picture-error').text('Invalid image file type. Only JPEG, PNG, JPG, GIF are allowed.');
                    submitBtn.prop('disabled', false).html(originalBtnText);
                    $('#spinner').hide();
                    return false;
                }
            }
            
            // Validate video
            if (videoInput.files.length > 0) {
                const allowedVideoTypes = ['video/mp4', 'video/quicktime', 'video/x-msvideo'];
                if (!allowedVideoTypes.includes(videoInput.files[0].type)) {
                    $('#video-error').text('Invalid video file type. Only MP4, MOV, AVI are allowed.');
                    submitBtn.prop('disabled', false).html(originalBtnText);
                    $('#spinner').hide();
                    return false;
                }
            }
            
            // Create FormData object
            let formData = new FormData(this);
            formData.append('admin_id', {{ Auth::guard('admin')->user()->id }});
            
            $.ajax({
                url: "{{ route('admin.exhibitions.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    toastr.success(response.message);
                    $('#createExhibitionForm')[0].reset();
                    
                    setTimeout(() => {
                        window.location.href = "{{ route('admin.exhibitions.index') }}";
                    }, 1500);
                },
                error: function(xhr) {
                    // Re-enable button and restore original text
                    submitBtn.prop('disabled', false).html(originalBtnText);
                    $('#spinner').hide();
                    
                    if(xhr.status === 422) {
                        // Validation errors
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $(`#${key}-error`).text(value[0]);
                        });
                        toastr.error('Please correct the form errors');
                    } else {
                        const errorMessage = xhr.responseJSON?.message || 'An unexpected error occurred. Please try again.';
                        toastr.error(errorMessage);
                    }
                }
            });
        });
    });
</script>

@include('admin.footer')