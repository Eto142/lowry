@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            <div class="mt-2 mb-4">
                <a href="{{ route('admin.future-exhibitions.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <h1 class="title1 text-dark d-inline ml-3">Add New Future Exhibition</h1>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="card-body">
                            <form action="{{ route('admin.future-exhibitions.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-dark">Title *</label>
                                            <input type="text" class="form-control" name="title" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Theme *</label>
                                            <input type="text" class="form-control" name="theme" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Description</label>
                                            <textarea class="form-control" name="description" rows="3"></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Mediums *</label>
                                            <input type="text" class="form-control" name="mediums" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="text-dark">Objective *</label>
                                            <textarea class="form-control" name="objective" rows="3"
                                                required></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Sections (JSON)</label>
                                            <textarea class="form-control" name="sections" rows="3"
                                                placeholder='["Section 1", "Section 2"]'></textarea>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Budget *</label>
                                            <input type="number" step="0.01" class="form-control" name="budget"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Exhibition Date *</label>
                                            <input type="date" class="form-control" name="exhibition_date" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-dark">Featured Image</label>
                                            <input type="file" class="form-control-file" name="picture">
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="type" value="future">

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plus-circle"></i> Create Exhibition
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