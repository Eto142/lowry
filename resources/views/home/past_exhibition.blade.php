@include('home.header')
<main id="content">
    <!-- Main Header Image -->
    <div class="container-fluid" style="padding-top: 30px; padding-bottom: 30px; background: #fff;">
        <div class="container">
            <div class="infoHeaderBoxedImage">
                <picture class="picture">
                    <source media="(max-width: 991px)"
                        srcset="https://img.thelowry.com/iS1FHr8tnmGsTwmLtXA8wVHWoTSgFKNmVC4qaxJ0psM/c:8021:4533:fp:0.5:0.33/s:690:390:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzEzNjRfb3JpZy5qcGc">
                    <source media="(min-width: 992px)"
                        srcset="https://img.thelowry.com/15fmDdPEuQ9T-0gb2DlmVwAxcW4169AZcVGi5nngCDk/c:8021:4511:fp:0.5:0.33/s:1920:1080:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzEzNjRfb3JpZy5qcGc">
                    <img class="picture__image img-fluid w-100"
                        src="https://img.thelowry.com/15fmDdPEuQ9T-0gb2DlmVwAxcW4169AZcVGi5nngCDk/c:8021:4511:fp:0.5:0.33/s:1920:1080:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzEzNjRfb3JpZy5qcGc"
                        alt="Past Exhibitions">
                </picture>
            </div>
        </div>
    </div>

    <!-- Exhibitions Grid -->
    <div class="container-fluid" style="padding: 30px 0; background: #fff;">
        <div class="container">
            <div class="row exhibition-grid">
                @foreach($pastExhibitions as $exhibition)
                <div class="col-12 col-md-6 col-lg-4 mb-4" data-entry-id="{{ $exhibition->id }}">
                    <div class="media-container">
                        <!-- Show video if available, otherwise show image -->
                        @if($exhibition->video_url)
                        <div class="media-item video-item">
                            <video controls>
                                <source src="{{ $exhibition->video_url }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        @else
                        <div class="media-item image-item">
                            <a href="/exhibitions/{{ $exhibition->id }}">
                                <img src="{{ $exhibition->picture_url ?? 'https://via.placeholder.com/800x600' }}"
                                    alt="{{ $exhibition->title ?? 'Exhibition image' }}">
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
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