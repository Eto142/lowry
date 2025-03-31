@include('admin.header')
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

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
                            <form id="editForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-dark">Title *</label>
                                            <input type="text" class="form-control" name="title"
                                                value="{{ old('title', $exhibition->title) }}" required>
                                            <small class="text-danger" id="title-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Description *</label>
                                            <textarea class="form-control" name="description" rows="5" required>
                                                {{ old('description', $exhibition->description) }}
                                            </textarea>
                                            <small class="text-danger" id="description-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Current Image</label><br>
                                            @if($exhibition->picture)
                                            <img src="{{ asset($exhibition->picture) }}" alt="{{ $exhibition->title }}"
                                                class="img-thumbnail mb-2" style="max-height: 200px;">
                                            @else
                                            <div class="bg-light d-flex align-items-center justify-content-center mb-2"
                                                style="height: 200px; width: 100%;">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                            </div>
                                            @endif
                                            <input type="file" class="form-control-file" name="picture">
                                            <small class="text-muted">Leave blank to keep current image</small>
                                            <small class="text-danger" id="picture-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Image URL</label>
                                            <input type="url" class="form-control" name="image_url"
                                                value="{{ old('image_url', $exhibition->image_url) }}">
                                            <small class="text-muted">Alternative to uploading an image</small>
                                            <small class="text-danger" id="image_url-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Artist Email</label>
                                            <input type="email" class="form-control" name="artist_email"
                                                value="{{ old('artist_email', $exhibition->artist_email) }}">
                                            <small class="text-danger" id="artist_email-error"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-dark">Seller Name</label>
                                            <input type="text" class="form-control" name="seller_name"
                                                value="{{ old('seller_name', $exhibition->seller_name) }}">
                                            <small class="text-danger" id="seller_name-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Buyer Name</label>
                                            <input type="text" class="form-control" name="buyer_name"
                                                value="{{ old('buyer_name', $exhibition->buyer_name) }}">
                                            <small class="text-danger" id="buyer_name-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Status *</label>
                                            <select class="form-control" name="exhibition_status" required>
                                                <option value="pending" {{ old('exhibition_status', $exhibition->
                                                    exhibition_status) == 'pending' ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="available" {{ old('exhibition_status', $exhibition->
                                                    exhibition_status) == 'available' ? 'selected' : '' }}>Available
                                                </option>
                                                <option value="sold" {{ old('exhibition_status', $exhibition->
                                                    exhibition_status) == 'sold' ? 'selected' : '' }}>Sold</option>
                                                <option value="reserved" {{ old('exhibition_status', $exhibition->
                                                    exhibition_status) == 'reserved' ? 'selected' : '' }}>Reserved
                                                </option>
                                            </select>
                                            <small class="text-danger" id="exhibition_status-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Amount (if sold)</label>
                                            <input type="number" step="0.01" class="form-control" name="amount_sold"
                                                value="{{ old('amount_sold', $exhibition->amount_sold) }}">
                                            <small class="text-danger" id="amount_sold-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Date</label>
                                            <input type="date" class="form-control" name="date"
                                                value="{{ old('date', $exhibition->date ? \Carbon\Carbon::parse($exhibition->date)->format('Y-m-d') : '') }}">
                                            <small class="text-danger" id="date-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Start Date</label>
                                            <input type="date" class="form-control" name="start_date"
                                                value="{{ old('start_date', $exhibition->start_date ? \Carbon\Carbon::parse($exhibition->start_date)->format('Y-m-d') : '') }}">
                                            <small class="text-danger" id="start_date-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">End Date</label>
                                            <input type="date" class="form-control" name="end_date"
                                                value="{{ old('end_date', $exhibition->end_date ? \Carbon\Carbon::parse($exhibition->end_date)->format('Y-m-d') : '') }}">
                                            <small class="text-danger" id="end_date-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Venue</label>
                                            <input type="text" class="form-control" name="venue"
                                                value="{{ old('venue', $exhibition->venue) }}">
                                            <small class="text-danger" id="venue-error"></small>
                                        </div>

                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" name="is_featured"
                                                id="is_featured" value="1" {{ old('is_featured',
                                                $exhibition->is_featured) ? 'checked' : '' }}>
                                            <label class="form-check-label text-dark" for="is_featured">Featured
                                                Item</label>
                                            <small class="text-danger" id="is_featured-error"></small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save"></i> Update Item
                                    </button>
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

    // Edit Form Submission with AJAX
    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        
        // Reset error messages
        $('.text-danger').text('');
        
        // Disable button and show spinner
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Updating...');
        
        // Create FormData object
        const formData = new FormData(this);
        
        $.ajax({
            url: "{{ route('admin.exhibitions.update', $exhibition->id) }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                toastr.success(response.message);
                // Reload the page to show updated data
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr) {
                // Re-enable button
                $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Update Item');
                
                if(xhr.status === 422) {
                    // Validation errors
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $(`#${key}-error`).text(value[0]);
                    });
                    toastr.error('Please fix the form errors');
                } else {
                    toastr.error(xhr.responseJSON?.message || 'An error occurred while updating');
                }
            }
        });
    });
});
</script>

@include('admin.footer')