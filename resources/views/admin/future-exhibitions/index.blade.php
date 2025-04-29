<!-- admin/future-exhibitions/index.blade.php -->
@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            @if(session('message'))
            <div class="alert alert-success mb-2">{{session('message')}}</div>
            @endif
            <div class="mt-2 mb-4">
                <h1 class="title1 text-dark">Future Exhibitions</h1>
            </div>

            <div class="row">
                <div class="col-12">
                    <a href="{{route('admin.future-exhibitions.create')}}" class="float-right btn btn-primary">
                        <i class='fas fa-plus-circle'></i> Add New Exhibition
                    </a>
                </div>
            </div>

            <div class="mb-5 row">
                <div class="col-md-12 shadow card p-4 bg-light">
                    <div class="table-responsive">
                        <table class="table table-hover text-dark">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Title</th>
                                    <th>Theme</th>
                                    <th>Budget</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($exhibitions as $exhibition)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $exhibition->title }}</td>
                                    <td>{{ Str::limit($exhibition->theme, 30) }}</td>
                                    <td>${{ number_format($exhibition->budget, 2) }}</td>
                                    <td>{{ $exhibition->exhibition_date->format('M d, Y') }}</td>
                                    <td>
                                        @if($exhibition->is_featured)
                                        <span class="badge badge-info">Featured</span>
                                        @else
                                        <span class="badge badge-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.future-exhibitions.edit', $exhibition->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.future-exhibitions.destroy', $exhibition->id) }}"
                                            method="POST" class="d-inline">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.footer')