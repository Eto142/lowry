@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            <div class="mt-2 mb-4">
                <a href="{{ route('admin.exhibitions.index') }}" class="btn btn-light">
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
                                            <input type="file" class="form-control-file" name="picture" required>
                                            <small class="text-danger" id="picture-error"></small>
                                            <small class="text-muted">Max size: 2MB (JPEG, PNG, JPG, GIF)</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-dark">Seller Name</label>
                                            <input type="text" class="form-control" name="seller_name">
                                            <small class="text-danger" id="seller_name-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Buyer Name</label>
                                            <input type="text" class="form-control" name="buyer_name">
                                            <small class="text-danger" id="buyer_name-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Status *</label>
                                            <select class="form-control" name="exhibition_status" required>
                                                <option value="available">Available</option>
                                                <option value="sold">Sold</option>
                                                <option value="reserved">Reserved</option>
                                            </select>
                                            <small class="text-danger" id="exhibition_status-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Amount (if sold)</label>
                                            <input type="number" step="0.01" class="form-control" name="amount_sold">
                                            <small class="text-danger" id="amount_sold-error"></small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Date</label>
                                            <input type="date" class="form-control" name="date">
                                            <small class="text-danger" id="date-error"></small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
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

    $('#createExhibitionForm').on('submit', function(e) {
        e.preventDefault();
        
        console.log('Form submitted'); // Debugging
        
        // Reset error messages
        $('.text-danger').text('');
        
        // Show spinner and disable button
        $('#spinner').show();
        $('#submitBtn').prop('disabled', true);
        
        // Create FormData object
        let formData = new FormData(this);
        formData.append('admin_id', {{ Auth::guard('admin')->user()->id }});
        
        // Debugging - show FormData contents
        for (var pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        $.ajax({
            url: "{{ route('admin.exhibitions.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Success:', response); // Debugging
                toastr.success(response.message);
                $('#createExhibitionForm')[0].reset();
                
                setTimeout(() => {
                    window.location.href = "{{ route('admin.exhibitions.index') }}";
                }, 2000);
            },
            error: function(xhr) {
                console.log('Error:', xhr); // Debugging
                
                // Hide spinner and enable button
                $('#spinner').hide();
                $('#submitBtn').prop('disabled', false);
                
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
            },
            complete: function() {
                // This runs after success or error
                $('#spinner').hide();
                $('#submitBtn').prop('disabled', false);
            }
        });
    });
});
</script>

@include('admin.footer')