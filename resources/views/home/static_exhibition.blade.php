@include('home.header')
<main id="content">

    <span id="pp_page_instance_746"></span>

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
                        alt="Ziiriel Art House Permanent Collection">
                </picture>
            </div>
        </div>
    </div>

    <div class="container-fluid infoHeaderWrapper type-page align-"
        style="padding-top: 30px; padding-bottom: 30px; background: #fff;">
        <div class="container">
            <div class="infoHeaderContent">
                <div class="inner">
                    <h1>Ziiriel Art House Permanent Collection</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid desc2Wrapper variant- align-"
        style="padding-top: 30px; padding-bottom: 30px; background: #CCD5D8;">
        <div class="container">
            <div class="richtext">
                <p>Explore our esteemed permanent collection featuring masterpieces from renowned contemporary artists.
                    This curated selection represents the core of Ziiriel's artistic vision and legacy.</p>

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
                            background-image: url('{{ $exhibition->picture_url?? ' https: //via.placeholder.com/855x600' }}');
                            }
                    </style>
                    <div class="listItemWrapper">
                        <div class="thumb">
                            <a class="image" href="/exhibitions/{{ $exhibition->id }}" tabindex="-1"></a>
                        </div>
                        <div class="inner">
                            <div class="descMetaContainer">
                                <a class="desc" href="/exhibitions/{{ $exhibition->id }}">
                                    <h2 class="title">{{ $exhibition->title }}</h2>
                                    <div class="subtitle">{{ Str::limit($exhibition->description, 100) }}</div>
                                    <div class="top-date">
                                        <span class="start">Date: {{ $exhibition->date->format('D d M Y')
                                            }}
                                            @if($exhibition->end_date)
                                            <span class="separator"></span>
                                            <span class="end">Until: {{ $exhibition->end_date->format('D d M Y') }}<span
                                                    class="time">4:30PM</span></span>
                                            @endif
                                    </div>
                                    <div class="venue">{{ $exhibition->venue ?? 'Main Exhibition Hall' }}</div>
                                </a>
                                <div class="meta">
                                    <div class="meta-group">
                                        <ul class="genres">
                                            <li class="genres__item"><a class="genres__link" href="#">{{
                                                    $exhibition->genre ?? 'Art' }}</a></li>
                                        </ul>
                                    </div>
                                    <div class="meta-group button">
                                        <a href="/exhibitions/{{ $exhibition->id }}" class="btn btn-active">Details</a>
                                        @if($exhibition->is_auction)
                                        <a href="/auctions/{{ $exhibition->id }}" class="btn btn-primary">Join Live
                                            Auction</a>
                                        @endif
                                        @if($exhibition->can_prebook)
                                        <a href="/book/{{ $exhibition->id }}" class="btn btn-secondary">Pre-book Now</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

</main>
@include('home.footer')