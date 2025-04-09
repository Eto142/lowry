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
                        alt="Current Exhibitions">
                </picture>
            </div>
        </div>
    </div>

    <div class="container-fluid infoHeaderWrapper type-page align-"
        style="padding-top: 30px; padding-bottom: 30px; background: #fff;">
        <div class="container">
            <div class="infoHeaderContent">
                <div class="inner">
                    <h1>Current Exhibitions</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid desc2Wrapper variant- align-"
        style="padding-top: 30px; padding-bottom: 30px; background: #CCD5D8;">
        <div class="container">
            <div class="richtext">
                <p>Our Exhibition Halls are located in the main gallery complex and are free to enter for all visitors.
                    Donations are welcome to support our programs.</p>
                <p style="text-align: center;"><strong>Exhibition Halls opening hours</strong></p>
                <p style="text-align: center;">Tuesday – Friday | 11:00–17:00<br>Saturday - Sunday | 10:00 –
                    17:00<br>Monday | closed</p>
                <p style="text-align: left;"><br>We welcome everyone to our Exhibition Spaces. You can find out more
                    about which exhibitions are currently showing and what is upcoming, plus more information on how to
                    <a href="/plan-your-visit">plan your visit</a>.
                </p>
                <p>&nbsp;</p>
                <h4>Visiting our Current Exhibitions</h4>
                <p>To ensure the best experience for all visitors, we are allocating time slots for both
                    <strong>IMMERSIVE 360</strong> and the <strong>Modern Perspectives: Contemporary Art
                        Exhibition</strong>.
                </p>
                <p>One <strong>FREE</strong> ticket will allow access to <strong>Modern Perspectives</strong>, and you
                    should plan about one hour for this exhibition. There will be interactive activities throughout the
                    venue.</p>
                <p><strong>IMMERSIVE 360</strong> requires a separate <strong>FREE</strong> ticket with 15 minute time
                    slots.</p>
                <p>(Please remember to book separately for each experience, as a ticket to one does not grant access to
                    the other).</p>
                <p>All our exhibitions are <strong>FREE</strong>, and we greatly appreciate any donations.</p>
                <p>When you arrive at the Exhibition Welcome Desk, our staff will be there to guide you and answer any
                    questions.</p>
                <p>We look forward to welcoming you soon!</p>
                <p>(We will make every effort to keep to the time slots but please bear with us at busy times).</p>
                <p style="text-align: left;">&nbsp;</p>
                <h4 style="text-align: left;">Special Exhibition Events</h4>
                <p style="text-align: left;"><strong><a href="/relaxed-sessions">Relaxed Sessions</a></strong> -
                    Designed for individuals and families who would prefer to experience our exhibitions in a calmer
                    environment.</p>
            </div>
        </div>
    </div>

    <div class="container-fluid listWrapper theme" style="padding-top: 0px; padding-bottom: 50px; background: #fff;">
        <div class="container">
            <ul data-animations="zoom" class="listItems variant-">
                @foreach($currentExhibitions as $exhibition)
                <li data-entry-id="{{ $exhibition->id }}"
                    class="eventCard context-default production-type-default variant-default topdate">
                    <style>
                        [data-entry-id="{{ $exhibition->id }}"] .thumb .image {
                            background-image: url('{{ $exhibition->picture ?? ' https: //via.placeholder.com/855x600' }}');
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
                                        <span class="start">Now Showing Until {{ $exhibition->date->format('D d M
                                            Y') }}<span class="time">10:00AM</span></span>
                                    </div>
                                    <div class="venue">{{ $exhibition->venue ?? 'Main Exhibition Hall' }}</div>
                                    @if($exhibition->seller_name)
                                    <div class="seller-info" style="font-style: italic; margin-top: 5px;">
                                        Presented by: {{ $exhibition->seller_name }}
                                    </div>
                                    @endif
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

                                        @auth
                                        <a href="mailto:{{ $exhibition->artist_email }}"
                                            class="btn btn-secondary">Contact Artist</a>
                                        @endauth
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