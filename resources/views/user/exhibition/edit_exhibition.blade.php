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
    }
</style>

<div class="col-12 col-md-9">
    <div class="form-container">
        <h2 class="mb-4">{{ isset($exhibition) ? 'Edit Exhibition' : 'Create New Exhibition' }}</h2>

        <form
            action="{{ isset($exhibition) ? route('exhibitions.update', $exhibition->id) : route('exhibitions.store') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($exhibition))
            @method('PUT')
            @endif

            <div class="form-section">
                <h4 class="section-title">Basic Information</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ old('title', $exhibition->title ?? '') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Exhibition Date *</label>
                        <input type="date" class="form-control" id="date" name="date"
                            value="{{ old('date', isset($exhibition) ? $exhibition->date->format('Y-m-d') : '') }}"
                            required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description *</label>
                    <textarea class="form-control" id="description" name="description" rows="4"
                        required>{{ old('description', $exhibition->description ?? '') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="picture" class="form-label">Picture *</label>
                    <input type="file" class="form-control" id="picture" name="picture" {{ !isset($exhibition)
                        ? 'required' : '' }}>
                    @if(isset($exhibition) && $exhibition->picture)
                    <img src="{{ asset($exhibition->picture) }}" class="preview-image" id="picturePreview">
                    @endif
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="amount_sold" class="form-label">Price *</label>
                        <input type="number" step="0.01" class="form-control" id="amount_sold" name="amount_sold"
                            value="{{ old('amount_sold', $exhibition->amount_sold ?? '') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="exhibition_type" class="form-label">Exhibition Type *</label>
                        <select class="form-control" id="exhibition_type" name="exhibition_type" required>
                            <option value="past" {{ old('exhibition_type', $exhibition->exhibition_type ?? '') == 'past'
                                ? 'selected' : '' }}>Past</option>
                            <option value="current" {{ old('exhibition_type', $exhibition->exhibition_type ?? '') ==
                                'current' ? 'selected' : '' }}>Current</option>
                            <option value="future" {{ old('exhibition_type', $exhibition->exhibition_type ?? '') ==
                                'future' ? 'selected' : '' }}>Future</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exhibition_status" class="form-label">Status *</label>
                    <select class="form-control" id="exhibition_status" name="exhibition_status" required>
                        <option value="pending" {{ old('exhibition_status', $exhibition->exhibition_status ?? '') ==
                            'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="available" {{ old('exhibition_status', $exhibition->exhibition_status ?? '') ==
                            'available' ? 'selected' : '' }}>Available</option>
                        <option value="sold" {{ old('exhibition_status', $exhibition->exhibition_status ?? '') == 'sold'
                            ? 'selected' : '' }}>Sold</option>
                        <option value="reserved" {{ old('exhibition_status', $exhibition->exhibition_status ?? '') ==
                            'reserved' ? 'selected' : '' }}>Reserved</option>
                    </select>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{
                        old('is_featured', $exhibition->is_featured ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_featured">Featured Exhibition</label>
                </div>
            </div>

            <div class="form-section">
                <h4 class="section-title">Seller Information</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="seller_name" class="form-label">Seller Name *</label>
                        <input type="text" class="form-control" id="seller_name" name="seller_name"
                            value="{{ old('seller_name', $exhibition->seller_name ?? '') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="seller_email" class="form-label">Seller Email</label>
                        <input type="email" class="form-control" id="seller_email" name="seller_email"
                            value="{{ old('seller_email', $exhibition->seller_email ?? '') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="seller_phone" class="form-label">Seller Phone</label>
                        <input type="text" class="form-control" id="seller_phone" name="seller_phone"
                            value="{{ old('seller_phone', $exhibition->seller_phone ?? '') }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="seller_address" class="form-label">Seller Address</label>
                        <input type="text" class="form-control" id="seller_address" name="seller_address"
                            value="{{ old('seller_address', $exhibition->seller_address ?? '') }}">
                    </div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="show_seller_contact" name="show_seller_contact"
                        value="1" {{ old('show_seller_contact', $exhibition->show_seller_contact ?? true) ? 'checked' :
                    '' }}>
                    <label class="form-check-label" for="show_seller_contact">Show Seller Contact Information</label>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('exhibitions.manage') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-dark">
                    {{ isset($exhibition) ? 'Update Exhibition' : 'Create Exhibition' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Preview image when selected
  document.getElementById('picture').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        let preview = document.getElementById('picturePreview');
        if (!preview) {
          preview = document.createElement('img');
          preview.id = 'picturePreview';
          preview.className = 'preview-image';
          event.target.parentNode.appendChild(preview);
        }
        preview.src = e.target.result;
      }
      reader.readAsDataURL(file);
    }
  });
</script>

@include("user.footer")