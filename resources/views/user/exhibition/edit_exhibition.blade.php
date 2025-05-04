@include("user.header")

<style>
    .form-container {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .form-section {
        margin-bottom: 30px;
    }

    .section-title {
        border-bottom: 2px solid #eee;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .preview-image {
        max-height: 200px;
        width: auto;
        margin-top: 10px;
        display: block;
        border-radius: 4px;
    }
</style>

<div class="col-12 col-md-9">
    <div class="form-container">
        <h2 class="mb-4">Edit Exhibition</h2>

        <form action="{{ route('user.exhibitions.update', $exhibition->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-section">
                <h4 class="section-title">Basic Information</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ old('title', $exhibition->title) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Exhibition Date *</label>
                        <input type="date" class="form-control" id="date" name="date"
                            value="{{ old('date', $exhibition->date->format('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description *</label>
                    <textarea class="form-control" id="description" name="description" rows="4"
                        required>{{ old('description', $exhibition->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="picture" class="form-label">Picture</label>
                    <input type="file" class="form-control" id="picture" name="picture">
                    @if($exhibition->picture_url)
                    <img src="{{ $exhibition->picture_url }}" class="preview-image mt-2" id="picturePreview">
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="amount_sold" class="form-label">Price *</label>
                        <input type="number" step="0.01" class="form-control" id="amount_sold" name="amount_sold"
                            value="{{ old('amount_sold', $exhibition->amount_sold) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="exhibition_status" class="form-label">Status *</label>
                        <select class="form-control" id="exhibition_status" name="exhibition_status" required>
                            <option value="pending" {{ old('exhibition_status', $exhibition->exhibition_status) ==
                                'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="available" {{ old('exhibition_status', $exhibition->exhibition_status) ==
                                'available' ? 'selected' : '' }}>Available</option>
                            <option value="sold" {{ old('exhibition_status', $exhibition->exhibition_status) == 'sold' ?
                                'selected' : '' }}>Sold</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h4 class="section-title">Seller Information</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="seller_name" class="form-label">Seller Name *</label>
                        <input type="text" class="form-control" id="seller_name" name="seller_name"
                            value="{{ old('seller_name', $exhibition->seller_name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="seller_email" class="form-label">Seller Email</label>
                        <input type="email" class="form-control" id="seller_email" name="seller_email"
                            value="{{ old('seller_email', $exhibition->seller_email) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="seller_phone" class="form-label">Seller Phone</label>
                    <input type="text" class="form-control" id="seller_phone" name="seller_phone"
                        value="{{ old('seller_phone', $exhibition->seller_phone) }}">
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('user.exhibitions.manage') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-dark">Update Exhibition</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('picture').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById('picturePreview');
                if (!preview) {
                    preview = document.createElement('img');
                    preview.id = 'picturePreview';
                    preview.className = 'preview-image mt-2';
                    event.target.parentNode.appendChild(preview);
                }
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>