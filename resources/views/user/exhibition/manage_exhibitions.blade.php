@include("user.header")

<style>
    .exhibition-card {
        transition: transform 0.3s ease;
        margin-bottom: 20px;
    }

    .exhibition-card:hover {
        transform: translateY(-5px);
    }

    .exhibition-img {
        height: 200px;
        object-fit: cover;
    }

    .price-tag {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: bold;
    }

    .status-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 5px 10px;
        border-radius: 4px;
        font-weight: bold;
        color: white;
    }

    .status-available {
        background: #28a745;
    }

    .status-sold {
        background: #dc3545;
    }

    .status-pending {
        background: #ffc107;
    }

    .action-buttons {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    .delete-form {
        display: inline;
    }
</style>

<div class="col-12 col-md-9">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Available Exhibitions</h2>
        <a href="{{ route('user.create.exhibition') }}" class="btn btn-dark">Create New Exhibition</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if($exhibitions->isEmpty())
    <div class="alert alert-info">No exhibitions available at the moment.</div>
    @else
    <div class="row">
        @foreach($exhibitions as $exhibition)
        <div class="col-md-4 mb-4">
            <div class="card exhibition-card">
                <div class="position-relative">
                    <img src="{{ $exhibition->picture_url }}" class="card-img-top exhibition-img"
                        alt="{{ $exhibition->title }}">
                    <span class="price-tag">${{ number_format($exhibition->amount_sold, 2) }}</span>
                    <span class="status-badge status-{{ $exhibition->exhibition_status }}">
                        {{ ucfirst($exhibition->exhibition_status) }}
                    </span>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $exhibition->title }}</h5>
                    <p class="card-text text-muted">
                        <small>{{ $exhibition->date->format('M d, Y') }}</small>
                    </p>
                    <p class="card-text">{{ Str::limit($exhibition->description, 100) }}</p>
                    <div class="action-buttons">
                        <a href="{{ route('user.edit.exhibition', $exhibition->id) }}"
                            class="btn btn-outline-dark btn-sm">View Details</a>

                        @if(Auth::id() == $exhibition->user_id)
                        <div class="d-flex gap-2">
                            <a href="{{ route('user.edit.exhibition', $exhibition->id) }}"
                                class="btn btn-outline-primary btn-sm">Edit</a>
                            <form class="delete-form" action="{{ route('user.delete.exhibition', $exhibition->id) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this exhibition?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $exhibitions->links() }}
    </div>
    @endif
</div>

<script>
    // Optional: Add confirmation for delete action
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this exhibition?')) {
                e.preventDefault();
            }
        });
    });
</script>

@include("user.footer")