@include('home.header')
<div class="container exhibition-show">
    <div class="row">
        <!-- Main Exhibition Content -->
        <div class="col-md-8">
            <!-- Exhibition Header -->
            <div class="exhibition-header mb-4">
                <h1 class="mb-2">{{ $exhibition->title }}</h1>
                <div class="exhibition-meta">
                    <span class="badge {{ $isPast ? 'bg-secondary' : 'bg-primary' }}">
                        {{ $isPast ? 'Past Exhibition' : 'Current Exhibition' }}
                    </span>
                    <span class="mx-2">•</span>
                    <span>{{ $exhibition->venue }}</span>
                </div>
            </div>

            <!-- Exhibition Image -->
            <div class="exhibition-image mb-4">
                <img src="{{ $exhibition->image_url ?? 'https://via.placeholder.com/1200x800' }}"
                    alt="{{ $exhibition->title }}" class="img-fluid rounded">
            </div>

            <!-- Exhibition Details -->
            <div class="exhibition-details mb-5">
                <div class="date mb-3">
                    <h5>Exhibition Dates</h5>
                    <p>
                        {{ \Carbon\Carbon::parse($exhibition->start_date)->format('d M
                        Y') }}
                        {{ \Carbon\Carbon::parse($exhibition->end_date)->format('d M
                        Y') }}
                    </p>
                </div>

                <div class="description mb-4">
                    {!! nl2br(e($exhibition->description)) !!}
                </div>

                @if($exhibition->artist)
                <div class="artist-info mb-4">
                    <h5>Featured Artist</h5>
                    <div class="d-flex align-items-center mt-2">
                        @if($exhibition->artist->profile_image)
                        <img src="{{ $exhibition->artist->profile_image }}" class="rounded-circle me-3" width="60"
                            alt="{{ $exhibition->artist->name }}">
                        @endif
                        <div>
                            <h6>{{ $exhibition->artist->name }}</h6>
                            @auth
                            <a href="mailto:{{ $exhibition->artist->email }}" class="btn btn-sm btn-outline-dark">
                                Contact Artist
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Related Artworks -->
            @if($exhibition->artworks->count() > 0)
            <div class="related-artworks mb-5">
                <h4 class="mb-4">Featured Artworks</h4>
                <div class="row">
                    @foreach($exhibition->artworks->take(3) as $artwork)
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('artwork.show', $artwork) }}" class="text-decoration-none">
                            <div class="card h-100">
                                <img src="{{ asset($artwork->picture) }}" class="card-img-top"
                                    alt="{{ $artwork->title }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $artwork->title }}</h5>
                                    <p class="card-text text-muted">
                                        {{ $artwork->artist->name ?? 'Unknown Artist' }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                @if($exhibition->artworks->count() > 3)
                <div class="text-center mt-3">
                    <a href="#" class="btn btn-outline-primary">
                        View All {{ $exhibition->artworks->count() }} Artworks
                    </a>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="exhibition-sidebar">
                <!-- Ticket Info -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Visitor Information</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Status</span>
                                <span class="badge {{ $isPast ? 'bg-secondary' : 'bg-success' }}">
                                    {{ $isPast ? 'Closed' : 'Open' }}
                                </span>
                            </li>
                            <li class="list-group-item">
                                <strong>Hours:</strong>
                                Tuesday-Friday 11:00-17:00<br>
                                Saturday-Sunday 10:00-17:00
                            </li>
                            <li class="list-group-item">
                                <strong>Admission:</strong> Free
                            </li>
                        </ul>
                        @if(!$isPast)
                        <a href="#" class="btn btn-primary mt-3 w-100">
                            Book Free Ticket
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Location -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Location</h5>
                        <p>{{ $exhibition->venue }}</p>
                        <div class="map-container">
                            <!-- Embedded map would go here -->
                            <img src="https://maps.googleapis.com/maps/api/staticmap?center={{ urlencode($exhibition->venue) }}&zoom=15&size=600x300&maptype=roadmap"
                                class="img-fluid rounded" alt="Map to {{ $exhibition->venue }}">
                        </div>
                        <a href="#" class="btn btn-outline-secondary mt-3 w-100">
                            Get Directions
                        </a>
                    </div>
                </div>

                <!-- Share -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Share This Exhibition</h5>
                        <div class="social-share d-flex justify-content-between">
                            <a href="#" class="btn btn-outline-dark"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="btn btn-outline-dark"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="btn btn-outline-dark"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="btn btn-outline-dark"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('home.footer')