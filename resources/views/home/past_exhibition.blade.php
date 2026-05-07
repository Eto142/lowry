@include('home.header')
<main id="content">
    <!-- Hero Banner -->
    <div class="container-fluid" style="padding: 0; position: relative; overflow: hidden; max-height: 400px;">
        <img src="{{ asset('images/past-exhibition.png') }}" alt="Past Exhibitions" style="width: 100%; height: 400px; object-fit: cover; display: block; filter: brightness(0.7);">
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; flex-direction: column; color: #fff; text-align: center; padding: 40px;">
            <h1 style="font-family: 'Playfair Display', serif; font-size: clamp(28px, 4vw, 52px); font-weight: 600; margin: 0; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">Past Exhibitions</h1>
            <p style="font-size: 1.1rem; margin-top: 12px; opacity: 0.9; text-shadow: 0 1px 5px rgba(0,0,0,0.3);">Browse our exhibition archive</p>
        </div>
    </div>

    <!-- Exhibitions Grid -->
    <div class="container-fluid" style="padding: 30px 0; background: #fff;">
        <div class="container">
            <div class="row exhibition-grid">
                {{-- @foreach($pastExhibitions as $exhibition)
                <div class="col-12 col-md-6 col-lg-4 mb-4" data-entry-id="{{ $exhibition->id }}">
                    <div class="media-container">
                        @if($exhibition->video_url)
                        <div class="media-item video-item">
                            <video controls>
                                <source src="{{ $exhibition->video_url }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        @else
                        <div class="media-item image-item">
                            <a href="">
                                <img src="{{ $exhibition->picture_url ?? 'https://via.placeholder.com/800x600' }}"
                                    alt="{{ $exhibition->title ?? 'Exhibition image' }}">
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach --}}
            </div>
        </div>
    </div>
</main>
@include('home.footer')

<style>
    /* Grid Layout */
    .exhibition-grid {
        display: flex;
        flex-wrap: wrap;
        margin: -12px;
    }

    .exhibition-grid>div {
        padding: 12px;
    }

    /* Media Container */
    .media-container {
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .media-container:hover {
        transform: translateY(-5px);
    }

    /* Consistent Media Dimensions */
    .media-item {
        position: relative;
        width: 100%;
        padding-top: 56.25%;
        /* 16:9 Aspect Ratio */
        background: #f5f5f5;
    }

    .media-item img,
    .media-item video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Video Specific Styles */
    .video-item video {
        background: #000;
    }

    /* Responsive Columns */
    @media (min-width: 992px) {
        .exhibition-grid>div {
            flex: 0 0 33.333%;
            max-width: 33.333%;
        }
    }

    @media (min-width: 768px) and (max-width: 991px) {
        .exhibition-grid>div {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }

    @media (max-width: 767px) {
        .exhibition-grid>div {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>