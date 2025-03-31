@include('home.header')
<div class="container artwork-show">
    <div class="row">
        <!-- Artwork Main Content -->
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('exhibitions.index') }}">Exhibitions</a></li>
                    @if($artwork->exhibition)
                    <li class="breadcrumb-item"><a href="{{ route('exhibitions.show', $artwork->exhibition) }}">{{
                            $artwork->exhibition->title }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $artwork->title }}</li>
                </ol>
            </nav>

            <!-- Artwork Image -->
            <div class="artwork-image mb-4">
                <div class="main-image">
                    <img src="{{ $artwork->image_url ?? 'https://via.placeholder.com/800x600' }}"
                        alt="{{ $artwork->title }}" class="img-fluid rounded">
                </div>
            </div>

            <!-- Artwork Details -->
            <div class="artwork-details">
                <h1 class="mb-3">{{ $artwork->title }}</h1>
                <h4 class="mb-4 text-muted">{{ $artwork->artist->name ?? 'Unknown Artist' }}</h4>

                <div class="artwork-meta mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Year:</strong> {{ $artwork->year_created ?? 'Unknown' }}</p>
                            <p><strong>Medium:</strong> {{ $artwork->medium ?? 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Dimensions:</strong> {{ $artwork->dimensions ?? 'Not specified' }}</p>
                            <p><strong>Category:</strong> {{ $artwork->category ?? 'Not specified' }}</p>
                        </div>
                    </div>
                </div>

                <div class="artwork-description mb-5">
                    <h5>About This Artwork</h5>
                    {!! nl2br(e($artwork->description)) !!}
                </div>
            </div>
        </div>

        <!-- Artwork Sidebar -->
        <div class="col-lg-4">
            <div class="artwork-sidebar">
                <!-- Purchase/Auction Info -->
                <div class="card mb-4">
                    <div class="card-body">
                        @if($artwork->is_auction)
                        <h5 class="card-title">Live Auction</h5>
                        <p class="text-danger">
                            <i class="fas fa-gavel"></i> Bidding ends:
                            {{ $artwork->auction_end->format('F j, Y \a\t g:i A') }}
                        </p>
                        <div class="current-bid mb-3">
                            <h4>Current Bid: ${{ number_format($artwork->current_bid, 2) }}</h4>
                            @if($artwork->bids->count() > 0)
                            <small class="text-muted">
                                {{ $artwork->bids->count() }} bids so far
                            </small>
                            @endif
                        </div>
                        <a href="{{ route('auction.live', $artwork) }}" class="btn btn-danger w-100 mb-2">
                            Join Live Auction
                        </a>
                        @else
                        <h5 class="card-title">Purchase This Artwork</h5>
                        <div class="price mb-3">
                            <h4>${{ number_format($artwork->price, 2) }}</h4>
                            @if($artwork->is_sold)
                            <p class="text-danger">This artwork has been sold</p>
                            @else
                            <p class="text-success">Available for purchase</p>
                            @endif
                        </div>
                        @if(!$artwork->is_sold)
                        <a href="{{ route('artwork.purchase', $artwork) }}" class="btn btn-primary w-100 mb-2">
                            Purchase Now
                        </a>
                        @endif
                        @endif

                        <div class="artist-contact mt-3">
                            <h6>About the Artist</h6>
                            <div class="d-flex align-items-center my-2">
                                @if($artwork->artist->profile_image)
                                <img src="{{ $artwork->artist->profile_image }}" class="rounded-circle me-2" width="40"
                                    alt="{{ $artwork->artist->name }}">
                                @endif
                                <span>{{ $artwork->artist->name }}</span>
                            </div>
                            @auth
                            <a href="mailto:{{ $artwork->artist->email }}" class="btn btn-outline-dark w-100">
                                Contact Artist
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Shipping/Returns -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Shipping Information</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-shipping-fast me-2"></i> Free worldwide shipping</li>
                            <li class="mb-2"><i class="fas fa-undo me-2"></i> 14-day return policy</li>
                            <li><i class="fas fa-certificate me-2"></i> Certificate of authenticity included</li>
                        </ul>
                    </div>
                </div>

                <!-- Share -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Share This Artwork</h5>
                        <div class="social-share d-flex justify-content-between">
                            <a href="#" class="btn btn-outline-dark"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="btn btn-outline-dark"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="btn btn-outline-dark"><i class="fab fa-pinterest"></i></a>
                            <a href="#" class="btn btn-outline-dark"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('home.footer')