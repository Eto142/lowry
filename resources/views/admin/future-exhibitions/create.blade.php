<!-- admin/future-exhibitions/create.blade.php and edit.blade.php -->
@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            <div class="mt-2 mb-4">
                <h1 class="title1 text-dark">
                    {{ isset($exhibition) ? 'Edit' : 'Create' }} Future Exhibition
                </h1>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-body">
                            <form method="POST" action="{{ isset($exhibition) 
                                    ? route('admin.future-exhibitions.update', $exhibition->id)
                                    : route('admin.future-exhibitions.store') }}" enctype="multipart/form-data">
                                @csrf
                                @if(isset($exhibition)) @method('PUT') @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title *</label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ $exhibition->title ?? old('title') }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Theme *</label>
                                            <input type="text" name="theme" class="form-control"
                                                value="{{ $exhibition->theme ?? old('theme') }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="description" class="form-control"
                                                rows="3">{{ $exhibition->description ?? old('description') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mediums *</label>
                                            <input type="text" name="mediums" class="form-control"
                                                value="{{ $exhibition->mediums ?? old('mediums') }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Objective *</label>
                                            <textarea name="objective" class="form-control" rows="3"
                                                required>{{ $exhibition->objective ?? old('objective') }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Sections (JSON array)</label>
                                            <textarea name="sections" class="form-control"
                                                rows="3">{{ json_encode($exhibition->sections ?? old('sections')) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Budget *</label>
                                            <input type="number" step="0.01" name="budget" class="form-control"
                                                value="{{ $exhibition->budget ?? old('budget') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Exhibition Date *</label>
                                            <input type="date" name="exhibition_date" class="form-control" value="{{ isset($exhibition) 
                                                     ? $exhibition->exhibition_date->format('Y-m-d') 
                                                     : old('exhibition_date') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Featured</label>
                                            <select name="is_featured" class="form-control">
                                                <option value="0" {{ (isset($exhibition) && !$exhibition->is_featured) ?
                                                    'selected' : '' }}>
                                                    No
                                                </option>
                                                <option value="1" {{ (isset($exhibition) && $exhibition->is_featured) ?
                                                    'selected' : '' }}>
                                                    Yes
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($exhibition) ? 'Update' : 'Create' }} Exhibition
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.footer')