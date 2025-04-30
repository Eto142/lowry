@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            <div class="mt-2 mb-4">
                <a href="{{ route('admin.future-exhibitions.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
                <h1 class="title1 text-dark d-inline ml-3">Future Exhibition Details</h1>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    @if($exhibition->picture_url)
                                    <img src="{{ asset('storage/'.$exhibition->picture_url) }}"
                                        alt="{{ $exhibition->title }}" class="img-fluid rounded mb-3"
                                        style="max-height: 300px; width: 100%; object-fit: cover;">
                                    @else
                                    <div class="bg-light d-flex align-items-center justify-content-center"
                                        style="height: 300px; width: 100%;">
                                        <i class="fas fa-image fa-5x text-muted"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <h2 class="text-dark">{{ $exhibition->title }}</h2>
                                    <h4 class="text-muted">{{ $exhibition->theme }}</h4>
                                    <p class="text-muted">Scheduled for {{ $exhibition->exhibition_date->format('F j,
                                        Y') }}</p>

                                    <div class="mb-3">
                                        <span class="badge badge-primary">Future Exhibition</span>
                                        <span class="badge badge-secondary ml-1">
                                            Budget: ${{ number_format($exhibition->budget, 2) }}
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <h5 class="text-dark">Description</h5>
                                        <p>{{ $exhibition->description ?? 'No description provided' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <h5 class="text-dark">Objective</h5>
                                        <p>{{ $exhibition->objective }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <h5 class="text-dark">Mediums</h5>
                                        <p>{{ $exhibition->mediums }}</p>
                                    </div>

                                    @if($exhibition->sections)
                                    <div class="mb-3">
                                        <h5 class="text-dark">Sections</h5>
                                        <ul>
                                            @foreach(json_decode($exhibition->sections) as $section)
                                            <li>{{ $section }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow">
                        <div class="card-header bg-light">
                            <h4 class="card-title text-dark">Exhibition Actions</h4>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('admin.future-exhibitions.edit', $exhibition->id) }}"
                                class="btn btn-primary btn-block mb-3">
                                <i class="fas fa-edit"></i> Edit Exhibition
                            </a>

                            <form action="{{ route('admin.future-exhibitions.destroy', $exhibition->id) }}"
                                method="POST" class="mb-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block"
                                    onclick="return confirm('Are you sure you want to delete this exhibition?')">
                                    <i class="fas fa-trash"></i> Delete Exhibition
                                </button>
                            </form>

                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="card-title text-dark">Quick Stats</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-2">
                                        <strong>Created:</strong>
                                        {{ $exhibition->created_at->format('M j, Y') }}
                                    </p>
                                    <p class="mb-2">
                                        <strong>Last Updated:</strong>
                                        {{ $exhibition->updated_at->format('M j, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.footer')