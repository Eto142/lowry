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
    <div class="container-fluid" style="padding: 0; background: #fff;">
        <div class="container">
            <div class="row exhibition-grid">
                @foreach($pastExhibitions as $exhibition)
                <div class="col-12 col-sm-6 col-lg-4 mb-4" data-entry-id="{{ $exhibition->id }}">
                    <!-- Image Card -->
                    <div class="media-card">
                        <div class="media-thumbnail">
                            <a href="/exhibitions/{{ $exhibition->id }}">
                                <img src="{{ $exhibition->picture_url ?? 'https://via.placeholder.com/855x600' }}"
                                    alt="{{ $exhibition->title ?? 'Exhibition image' }}" class="img-fluid w-100">
                            </a>
                        </div>
                    </div>

                    <!-- Video (if exists) -->
                    @if($exhibition->video_url)
                    <div class="media-card mt-2">
                        <div class="embed-responsive embed-responsive-16by9">
                            <video class="embed-responsive-item" controls>
                                <source src="{{ $exhibition->video_url }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</main>
@include('home.footer')

<style>
    /* Responsive Grid Layout */
    .exhibition-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        padding: 20px 0;
    }

    .media-card {
        background: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .media-card:hover {
        transform: translateY(-5px);
    }

    .media-thumbnail {
        position: relative;
        padding-top: 56.25%;
        /* 16:9 Aspect Ratio */
        overflow: hidden;
    }

    .media-thumbnail img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Responsive Video */
    .embed-responsive {
        position: relative;
        display: block;
        width: 100%;
        padding: 0;
        overflow: hidden;
    }

    .embed-responsive-16by9::before {
        padding-top: 56.25%;
    }

    .embed-responsive-item {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    /* Mobile adjustments */
    @media (max-width: 767px) {
        .exhibition-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .media-card {
            margin-bottom: 15px;
        }
    }
</style>