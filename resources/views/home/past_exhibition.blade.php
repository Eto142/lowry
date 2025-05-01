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
                <div class="col-12 col-md-6 col-lg-4 mb-5" data-entry-id="{{ $exhibition->id }}">
                    <div class="exhibition-item">
                        <!-- Image Section -->
                        <div class="media-section image-section">
                            <a href="/exhibitions/{{ $exhibition->id }}" class="d-block">
                                <img src="{{ $exhibition->picture_url ?? 'https://via.placeholder.com/800x600' }}"
                                    alt="{{ $exhibition->title ?? 'Exhibition image' }}"
                                    class="img-fluid w-100 exhibition-image">
                            </a>
                        </div>

                        <!-- Video Section (if exists) -->
                        @if($exhibition->video_url)
                        <div class="media-section video-section mt-3">
                            <div class="video-container">
                                <video controls class="w-100">
                                    <source src="{{ $exhibition->video_url }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
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
    /* Exhibition Grid Layout */
    .exhibition-grid {
        display: flex;
        flex-wrap: wrap;
        margin: -15px;
    }

    .exhibition-grid>div {
        padding: 15px;
    }

    .exhibition-item {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    /* Media Sections */
    .media-section {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .media-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    /* Image Section */
    .image-section {
        flex: 1;
    }

    .exhibition-image {
        height: 400px;
        object-fit: cover;
        object-position: center;
    }

    /* Video Section */
    .video-section {
        background: #000;
    }

    .video-container {
        position: relative;
        padding-bottom: 56.25%;
        /* 16:9 aspect ratio */
        height: 0;
        overflow: hidden;
    }

    .video-container video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    /* Responsive Adjustments */
    @media (min-width: 992px) {
        .exhibition-image {
            height: 500px;
        }
    }

    @media (max-width: 991px) {
        .exhibition-grid>div {
            flex: 0 0 50%;
            max-width: 50%;
        }

        .exhibition-image {
            height: 350px;
        }
    }

    @media (max-width: 767px) {
        .exhibition-grid>div {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .exhibition-image {
            height: 300px;
        }
    }
</style>