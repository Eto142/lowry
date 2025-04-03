@include("user.header")

<style>
    .exhibition-card {
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }

    .exhibition-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .action-btns .btn {
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .status-badge {
        font-size: 0.8rem;
        padding: 5px 10px;
        border-radius: 20px;
    }

    .status-pending {
        background-color: #ffc107;
        color: #000;
    }

    .status-available {
        background-color: #28a745;
        color: #fff;
    }

    .status-sold {
        background-color: #dc3545;
        color: #fff;
    }

    .status-reserved {
        background-color: #17a2b8;
        color: #fff;
    }
</style>

<div class="col-12 col-md-9">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Your Exhibitions</h2>
        <a href="{{ route('exhibitions.create') }}" class="btn btn-dark">
            <i class="fas fa-plus"></i> Add New Exhibition
        </a>
    </div>

    <div class="row">
        @foreach($exhibitions as $exhibition)
        <div class="col-md-6 col-lg-4">
            <div class="card exhibition-card">
                <img src="{{ asset($exhibition->picture) }}" class="card-img-top" alt="{{ $exhibition->title }}"
                    style="height: 180px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $exhibition->title }}</h5>
                    <span class="status-badge status-{{ $exhibition->exhibition_status }}">
                        {{ ucfirst($exhibition->exhibition_status) }}
                    </span>
                    <p class="card-text mt-2">
                        <strong>Price:</strong> ${{ number_format($exhibition->amount_sold, 2) }}<br>
                        <strong>Date:</strong> {{ $exhibition->date->format('M d, Y') }}
                    </p>
                </div>
                <div class="card-footer bg-white action-btns">
                    <a href="{{ route('exhibitions.edit', $exhibition->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('exhibitions.destroy', $exhibition->id) }}" method="POST"
                        style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                    <a href="{{ route('exhibitions.show', $exhibition->id) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-eye"></i> View
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $exhibitions->links() }}
    </div>
</div>

@include("user.footer")