<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="{{ isset($exhibition) 
                  ? route('admin.future-exhibitions.update', $exhibition->id)
                  : route('admin.future-exhibitions.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($exhibition)) @method('PUT') @endif

            <div class="row">
                <!-- Left column -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="title" class="form-control"
                            value="{{ old('title', $exhibition->title ?? '') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Theme *</label>
                        <input type="text" name="theme" class="form-control"
                            value="{{ old('theme', $exhibition->theme ?? '') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control"
                            rows="3">{{ old('description', $exhibition->description ?? '') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Type *</label>
                        <select name="type" class="form-control" required>
                            @foreach(\App\Models\FutureExhibition::getTypes() as $type)
                            <option value="{{ $type }}" {{ (old('type', $exhibition->type ?? '') === $type) ? 'selected'
                                : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Right column -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Mediums *</label>
                        <input type="text" name="mediums" class="form-control"
                            value="{{ old('mediums', $exhibition->mediums ?? '') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Objective *</label>
                        <textarea name="objective" class="form-control" rows="3"
                            required>{{ old('objective', $exhibition->objective ?? '') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Sections</label>
                        <small class="form-text text-muted">
                            Enter one section per line or use JSON format for complex structures
                        </small>
                        <textarea name="sections" class="form-control" rows="5" placeholder="Section 1&#10;Section 2&#10;Section 3">{{ old('sections', $exhibition->sections ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Bottom row -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Budget *</label>
                        <input type="number" step="0.01" name="budget" class="form-control"
                            value="{{ old('budget', $exhibition->budget ?? '') }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Exhibition Date *</label>
                        <input type="date" name="exhibition_date" class="form-control" value="{{ old('exhibition_date', isset($exhibition) 
                                ? $exhibition->exhibition_date->format('Y-m-d') 
                                : '') }}" required>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Featured</label>
                        <select name="is_featured" class="form-control">
                            <option value="0" {{ old('is_featured', $exhibition->is_featured ?? 0) == 0 ? 'selected' :
                                '' }}>
                                No
                            </option>
                            <option value="1" {{ old('is_featured', $exhibition->is_featured ?? 0) == 1 ? 'selected' :
                                '' }}>
                                Yes
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Picture -->
            <div class="form-group">
                <label>Exhibition Image</label>
                <input type="file" name="picture" class="form-control">
                @if(isset($exhibition) && $exhibition->picture_url)
                <img src="{{ asset('storage/' . $exhibition->picture_url) }}" alt="Current Image" class="mt-2"
                    style="max-width: 200px;">
                @endif
            </div>

            <button type="submit" class="btn btn-primary">
                {{ isset($exhibition) ? 'Update' : 'Create' }} Exhibition
            </button>
        </form>
    </div>
</div>