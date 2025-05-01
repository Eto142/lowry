@include('home.header')
<main id="content">
    <div class="container-fluid align-" style="padding-top: 30px; padding-bottom: 30px; background: #fff;">
        <div class="container">
            <div class="infoHeaderBoxedImage" data-animations="">
                <picture class="picture" style="--picture-aspect-ratio: 16/9">
                    <source media="(max-width: 991px)"
                        srcset="https://img.thelowry.com/iS1FHr8tnmGsTwmLtXA8wVHWoTSgFKNmVC4qaxJ0psM/c:8021:4533:fp:0.5:0.33/s:690:390:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzEzNjRfb3JpZy5qcGc">
                    <source media="(min-width: 992px)"
                        srcset="https://img.thelowry.com/15fmDdPEuQ9T-0gb2DlmVwAxcW4169AZcVGi5nngCDk/c:8021:4511:fp:0.5:0.33/s:1920:1080:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzEzNjRfb3JpZy5qcGc">
                    <img class="picture__image"
                        src="https://img.thelowry.com/15fmDdPEuQ9T-0gb2DlmVwAxcW4169AZcVGi5nngCDk/c:8021:4511:fp:0.5:0.33/s:1920:1080:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzEzNjRfb3JpZy5qcGc"
                        alt="Past Exhibitions">
                </picture>
            </div>
        </div>
    </div>

    <div class="container-fluid listWrapper theme" style="padding-top: 0px; padding-bottom: 50px; background: #fff;">
        <div class="container">
            <ul data-animations="zoom" class="listItems variant-">
                @foreach($pastExhibitions as $exhibition)
                <li data-entry-id="{{ $exhibition->id }}"
                    class="eventCard context-default production-type-default variant-default topdate">
                    <style>
                        [data-entry-id="{{ $exhibition->id }}"] .thumb .image {
                            background-image: url('{{ $exhibition->picture_url ?? ' https: //via.placeholder.com/855x600' }}');
                            }
                    </style>
                    <div class="listItemWrapper">
                        <div class="thumb">
                            <a class="image" href="/exhibitions/{{ $exhibition->id }}" tabindex="-1"></a>
                        </div>
                        @if($exhibition->video_url)
                        <div class="video-container">
                            <video controls>
                                <source src="{{ $exhibition->video_url }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        @endif
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</main>
@include('home.footer')