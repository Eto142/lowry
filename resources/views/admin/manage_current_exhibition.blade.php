@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            @if(session('message'))
            <div class="alert alert-success mb-2">{{session('message')}}</div>
            @endif
            <div class="mt-2 mb-4">
                <h1 class="title1 text-dark">Exhibitions Management</h1>
            </div>

            <!-- Controls -->
            <div class="row mb-4">
                <div class="col-12">
                    <button type="button" class="btn btn-primary mb-3" data-toggle="modal"
                        data-target="#exhibitionModal">
                        <i class="fas fa-plus"></i> Add New Exhibition
                    </button>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-secondary filter-btn" data-type="all">All</button>
                        <button type="button" class="btn btn-info filter-btn" data-type="future">Future</button>
                        <button type="button" class="btn btn-success filter-btn" data-type="current">Current</button>
                    </div>
                    <div class="float-right">
                        <input type="text" id="searchInput" placeholder="Search exhibitions..."
                            class="form-control bg-light text-dark" style="width: 200px;">
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover text-dark" id="exhibitionsTable">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Theme</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Budget</th>
                            <th>Mediums</th>
                            <th>Sections</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="exhibitionsList">
                        @foreach($exhibitions as $exhibition)
                        <tr id="exhibition-row-{{ $exhibition->id }}" data-type="{{ $exhibition->type }}">
                            <td>{{ $exhibition->title }}</td>
                            <td>{{ $exhibition->theme }}</td>
                            <td>
                                <span class="badge badge-{{ $exhibition->type == 'future' ? 'info' : 'success' }}">
                                    {{ ucfirst($exhibition->type) }}
                                </span>
                            </td>
                            <td>{{ $exhibition->exhibition_date->format('M d, Y') }}</td>
                            <td>{{ $exhibition->formatted_budget }}</td>
                            <td>{{ $exhibition->mediums }}</td>
                            <td>
                                <button class="btn btn-sm btn-info view-sections"
                                    data-sections="{{ json_encode($exhibition->sections) }}">
                                    View Sections
                                </button>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-exhibition" data-id="{{ $exhibition->id }}"
                                    data-toggle="modal" data-target="#exhibitionModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-exhibition" data-id="{{ $exhibition->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div id="pagination" class="mt-3"></div>
        </div>
    </div>
</div>

<!-- Exhibition Modal -->
<div class="modal fade" id="exhibitionModal" tabindex="-1" role="dialog" aria-labelledby="exhibitionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-dark" id="exhibitionModalLabel">Manage Exhibition</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body bg-light">
                <form id="exhibitionForm">
                    @csrf
                    <input type="hidden" id="exhibition_id" name="id">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" name="title" required>
                            </div>
                            <div class="form-group">
                                <label>Theme</label>
                                <input type="text" class="form-control" name="theme" required>
                            </div>
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control" name="type" required>
                                    <option value="future">Future</option>
                                    <option value="current">Current</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Exhibition Date</label>
                                <input type="date" class="form-control" name="exhibition_date" required>
                            </div>
                            <div class="form-group">
                                <label>Mediums</label>
                                <input type="text" class="form-control" name="mediums">
                            </div>
                            <div class="form-group">
                                <label>Budget (£)</label>
                                <input type="number" step="0.01" class="form-control" name="budget">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Sections</label>
                        <div id="sectionsContainer">
                            <div class="section-entry mb-2">
                                <div class="input-group">
                                    <input type="text" class="form-control section-title" placeholder="Title">
                                    <input type="text" class="form-control section-desc" placeholder="Description">
                                    <div class="input-group-append">
                                        <button class="btn btn-danger remove-section" type="button">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-success mt-2" id="addSection">
                            <i class="fas fa-plus"></i> Add Section
                        </button>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Objective</label>
                        <textarea class="form-control" name="objective" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Exhibition</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Sections View Modal -->
<div class="modal fade" id="sectionsModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Exhibition Sections</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="sectionsContent"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
    // Section Management
    $('#addSection').click(function() {
        const newEntry = $(`<div class="section-entry mb-2">
            <div class="input-group">
                <input type="text" class="form-control section-title" placeholder="Title">
                <input type="text" class="form-control section-desc" placeholder="Description">
                <div class="input-group-append">
                    <button class="btn btn-danger remove-section" type="button">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>`);
        $('#sectionsContainer').append(newEntry);
    });

    $(document).on('click', '.remove-section', function() {
        $(this).closest('.section-entry').remove();
    });

    // Form Handling
    $('#exhibitionForm').submit(function(e) {
        e.preventDefault();
        
        // Collect sections data
        const sections = [];
        $('.section-entry').each(function() {
            const title = $(this).find('.section-title').val();
            const desc = $(this).find('.section-desc').val();
            if(title || desc) {
                sections.push({
                    title: title,
                    description: desc
                });
            }
        });

        // Validate sections
        if(sections.length === 0) {
            toastr.error('Please add at least one section');
            return;
        }

        // Prepare form data
        const formData = new FormData(this);
        formData.append('sections', JSON.stringify(sections));

        // Submit data
        $.ajax({
            url: $(this).attr('action') || "{{ route('admin.exhibitions.store') }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Handle response
            }
        });
    });

    // Edit Handler
    $(document).on('click', '.edit-exhibition', function() {
        const id = $(this).data('id');
        $.ajax({
            url: "{{ route('admin.exhibitions.edit') }}",
            data: { id: id },
            success: function(response) {
                // Populate form fields
                $('#exhibition_id').val(response.id);
                $('[name="title"]').val(response.title);
                $('[name="theme"]').val(response.theme);
                $('[name="type"]').val(response.type);
                $('[name="exhibition_date"]').val(response.exhibition_date);
                $('[name="mediums"]').val(response.mediums);
                $('[name="budget"]').val(response.budget);
                $('[name="description"]').val(response.description);
                $('[name="objective"]').val(response.objective);
                
                // Populate sections
                $('#sectionsContainer').empty();
                JSON.parse(response.sections).forEach(section => {
                    $('#sectionsContainer').append(
                        `<div class="section-entry mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control section-title" 
                                       value="${section.title}" placeholder="Title">
                                <input type="text" class="form-control section-desc" 
                                       value="${section.description}" placeholder="Description">
                                <div class="input-group-append">
                                    <button class="btn btn-danger remove-section" type="button">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>`
                    );
                });
            }
        });
    });

    // View Sections
    $(document).on('click', '.view-sections', function() {
        const sections = JSON.parse($(this).data('sections'));
        let content = '<div class="row">';
        sections.forEach((section, index) => {
            content += `
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-header">Section ${index + 1}</div>
                        <div class="card-body">
                            <h5>${section.title || 'No title'}</h5>
                            <p>${section.description || 'No description'}</p>
                        </div>
                    </div>
                </div>`;
        });
        content += '</div>';
        $('#sectionsContent').html(content);
        $('#sectionsModal').modal('show');
    });
});
</script>
@include('admin.footer')