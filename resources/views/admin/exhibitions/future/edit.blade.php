@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            <div class="mt-2 mb-4">
                <a href="{{ route('admin.future-exhibitions.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <h1 class="title1 text-dark d-inline ml-3">Edit Future Exhibition</h1>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <form action="{{ route('admin.future-exhibitions.update', $exhibition->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-dark">Title *</label>
                                            <input type="text" class="form-control" name="title"
                                                value="{{ $exhibition->title }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Theme *</label>
                                            <input type="text" class="form-control" name="theme"
                                                value="{{ $exhibition->theme }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Description</label>
                                            <textarea class="form-control" name="description"
                                                rows="3">{{ $exhibition->description }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Mediums *</label>
                                            <input type="text" class="form-control" name="mediums"
                                                value="{{ $exhibition->mediums }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-dark">Objective *</label>
                                            <textarea class="form-control" name="objective" rows="3"
                                                required>{{ $exhibition->objective }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Sections (JSON)</label>
                                            <textarea class="form-control" name="sections"
                                                rows="3">{{ json_encode(json_decode($exhibition->sections), JSON_PRETTY_PRINT) }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Budget *</label>
                                            <input type="number" step="0.01" class="form-control" name="budget"
                                                value="{{ $exhibition->budget }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Exhibition Date *</label>
                                            <input type="date" class="form-control" name="exhibition_date"
                                                value="{{ $exhibition->exhibition_date->format('Y-m-d') }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Featured Image</label>
                                            <input type="file" class="form-control-file" name="picture">
                                            @if($exhibition->picture_url)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/'.$exhibition->picture_url) }}" width="100"
                                                    class="img-thumbnail">
                                                <input type="hidden" name="current_picture"
                                                    value="{{ $exhibition->picture_url }}">
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Exhibition
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
@include('admin.footer')