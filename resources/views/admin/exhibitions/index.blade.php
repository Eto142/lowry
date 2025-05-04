@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            @include('admin.message')
            <div class="mt-2 mb-4">
                <h1 class="title1 text-dark">Exhibitions Management</h1>
                <a href="{{ route('admin.exhibitions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Exhibition
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover text-dark">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Theme</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Budget</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exhibitions as $exhibition)
                        <tr>
                            <td>{{ $exhibition->title }}</td>
                            <td>{{ $exhibition->theme }}</td>
                            <td>
                                <span class="badge badge-{{ $exhibition->type == 'future' ? 'info' : 'success' }}">
                                    {{ ucfirst($exhibition->type) }}
                                </span>
                            </td>
                            <td>{{ $exhibition->exhibition_date->format('M d, Y') }}</td>
                            <td>{{ $exhibition->formatted_budget }}</td>
                            <td>
                                <a href="{{ route('admin.exhibitions.edit', $exhibition->id) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.exhibitions.destroy', $exhibition->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $exhibitions->links() }}
            </div>
        </div>
    </div>
</div>
@include('admin.footer')