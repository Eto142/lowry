@include('home.header')
<div class="container live-auction">
    <div class="row">
        <!-- Auction Main Content -->
        <div class="col-lg-8">
            <div class="auction-header mb-4">
                <h1>{{ $artwork->title }} <span class="badge bg-danger">Live Auction</span></h1>
                <h4 class="text-muted">{{ $artwork->artist->name ?? 'Unknown Artist' }}</h4>
                <div class="countdown mb-3" id="auctionCountdown" data-end="{{ $artwork->auction_end }}">
                    <div class="countdown-inner">
                        <span class="hours">00</span>:<span class="minutes">00</span>:<span class="seconds">00</span>
                    </div>
                    <small class="text-muted">Time remaining</small>
                </div>
            </div>

            <!-- Artwork Image -->
            <div class="artwork-image mb-4">
                <img src="{{ $artwork->image_url ?? 'https://via.placeholder.com/800x600' }}"
                    alt="{{ $artwork->title }}" class="img-fluid rounded">
            </div>

            <!-- Artwork Details -->
            <div class="artwork-details mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Year:</strong> {{ $artwork->year_created ?? 'Unknown' }}</p>
                        <p><strong>Medium:</strong> {{ $artwork->medium ?? 'Not specified' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Dimensions:</strong> {{ $artwork->dimensions ?? 'Not specified' }}</p>
                        <p><strong>Current Bid:</strong> ${{ number_format($artwork->current_bid, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Auction Sidebar -->
        <div class="col-lg-4">
            <div class="auction-sidebar">
                <!-- Bidding Panel -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Place Your Bid</h5>

                        <div class="current-bid mb-3">
                            <h4>${{ number_format($artwork->current_bid, 2) }}</h4>
                            <small class="text-muted">
                                {{ $artwork->bids->count() }} bids •
                                Minimum bid: ${{ number_format($artwork->current_bid + 1, 2) }}
                            </small>
                        </div>

                        <form action="{{ route('artwork.bid', $artwork) }}" method="POST" id="bidForm">
                            @csrf
                            <div class="mb-3">
                                <label for="bidAmount" class="form-label">Your Bid Amount ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="bidAmount" name="amount"
                                        min="{{ $artwork->current_bid + 1 }}" step="1"
                                        value="{{ $artwork->current_bid + 1 }}" required>
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="anonymousBid" name="anonymous">
                                <label class="form-check-label" for="anonymousBid">
                                    Bid anonymously
                                </label>
                            </div>

                            <button type="submit" class="btn btn-danger w-100">
                                Place Bid
                            </button>
                        </form>

                        <div class="terms mt-3">
                            <small class="text-muted">
                                By placing a bid, you agree to our <a href="#">Terms of Sale</a>.
                                Winning bids are binding contracts to purchase.
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Bid History -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Bid History</h5>
                        @if($bids->count() > 0)
                        <div class="bid-list">
                            @foreach($bids as $bid)
                            <div class="bid-item {{ $loop->first ? 'current-bidder' : '' }}">
                                <div class="d-flex justify-content-between">
                                    <span>
                                        @if($bid->is_anonymous)
                                        Anonymous Bidder
                                        @else
                                        {{ $bid->bidder->name }}
                                        @endif
                                    </span>
                                    <strong>${{ number_format($bid->amount, 2) }}</strong>
                                </div>
                                <small class="text-muted">
                                    {{ $bid->created_at->diffForHumans() }}
                                </small>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-muted">No bids yet</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Countdown timer
    function updateCountdown() {
        const endTime = new Date(document.getElementById('auctionCountdown').dataset.end).getTime();
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance < 0) {
            document.getElementById('auctionCountdown').innerHTML = "Auction Ended";
            return;
        }

        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.querySelector('.hours').textContent = hours.toString().padStart(2, '0');
        document.querySelector('.minutes').textContent = minutes.toString().padStart(2, '0');
        document.querySelector('.seconds').textContent = seconds.toString().padStart(2, '0');
    }

    // Update every second
    updateCountdown();
    setInterval(updateCountdown, 1000);

    // Real-time bidding updates (would use Echo in a real app)
    // This is a placeholder - in a real app you'd use Laravel Echo
    function listenForNewBids() {
        // Implementation would connect to websockets
    }
</script>
@include('home.footer')