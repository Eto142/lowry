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
                        alt="Exhibition Hall">
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
                <h4>Visiting our Exhibitions this season</h4>
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

    <div class="container-fluid desc2Wrapper variant- align-"
        style="padding-top: 50px; padding-bottom: 20px; background: #fff;">
        <div class="container">
            <div class="richtext">
                <h2>Past Exhibitions</h2>
            </div>
        </div>
    </div>

    <div class="container-fluid listWrapper theme" style="padding-top: 0px; padding-bottom: 0px; background: #fff;">
        <div class="container">
            <ul data-animations="zoom" class="listItems variant-">
                @foreach($pastExhibitions as $exhibition)
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
                                        <span class="start">{{ $exhibition->created_at->format('D d M Y') }}<span
                                                class="time">10:00AM</span></span>
                                        @if($exhibition->end_date)
                                        <span class="separator"></span>
                                        <span class="end"> {{ \Carbon\Carbon::parse($exhibition->end_date)->format('d M
                                            Y') }}
                                            <span class="time">4:30PM</span></span>
                                        @endif
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

    @auth
    <div class="container-fluid desc2Wrapper variant- align-"
        style="padding-top: 70px; padding-bottom: 0px; background: #fff;">
        <div class="container">
            <div class="richtext">
                <h2>Upcoming Exhibitions</h2>
            </div>
        </div>
    </div>

    <div class="container-fluid listWrapper theme" style="padding-top: 0px; padding-bottom: 0px; background: #fff;">
        <div class="container">
            <ul data-animations="zoom" class="listItems variant-">
                @foreach($upcomingExhibitions as $exhibition)
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
                                        <span class="start">{{ $exhibition->start_date->format('D d M Y') }}<span
                                                class="time">10:00AM</span></span>
                                        @if($exhibition->end_date)
                                        <span class="separator"></span>
                                        <span class="end">{{ $exhibition->end_date->format('D d M Y') }}<span
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
    @endauth

    <div class="container-fluid desc2Wrapper variant- align-"
        style="padding-top: 50px; padding-bottom: 20px; background: #fff;">
        <div class="container">
            <div class="richtext">
                <h2>Art Marketplace</h2>
                <p>Browse and purchase artwork directly from our platform. Our secure payment system connects you with
                    the artists.</p>
            </div>
        </div>
    </div>

    <div class="container-fluid listWrapper theme" style="padding-top: 0px; padding-bottom: 0px; background: #fff;">
        <div class="container">
            <ul data-animations="zoom" class="listItems variant-">
                @foreach($availableArtworks as $artwork)
                <li data-entry-id="{{ $artwork->id }}"
                    class="eventCard context-default production-type-default variant-default topdate">
                    <style>
                        [data-entry-id="{{ $artwork->id }}"] .thumb .image {
                            background-image: url('{{ $artwork->image_url ?? ' https: //via.placeholder.com/855x600' }}');
                            }
                    </style>
                    <div class="listItemWrapper">
                        <div class="thumb">
                            <a class="image" href="/artworks/{{ $artwork->id }}" tabindex="-1"></a>
                        </div>
                        <div class="inner">
                            <div class="descMetaContainer">
                                <a class="desc" href="/artworks/{{ $artwork->id }}">
                                    <h2 class="title">{{ $artwork->title }}</h2>
                                    <div class="subtitle">{{ Str::limit($artwork->description, 100) }}</div>
                                    <div class="venue">By {{ $artwork->artist_name }}</div>
                                    <div class="price">${{ number_format($artwork->price, 2) }}</div>
                                    @if($artwork->is_auction)
                                    <div class="auction-info">
                                        <strong>Live Auction:</strong> {{ $artwork->auction_end->format('D d M Y H:i')
                                        }}
                                    </div>
                                    @endif
                                </a>
                                <div class="meta">
                                    <div class="meta-group">
                                        <ul class="genres">
                                            <li class="genres__item"><a class="genres__link" href="#">{{
                                                    $artwork->medium }}</a></li>
                                            <li class="genres__item"><a class="genres__link" href="#">{{
                                                    $artwork->category }}</a></li>
                                        </ul>
                                    </div>
                                    <div class="meta-group button">
                                        <a href="/artworks/{{ $artwork->id }}" class="btn btn-active">View Details</a>
                                        <a href="/checkout/{{ $artwork->id }}" class="btn btn-primary">Purchase</a>
                                        @if($artwork->is_auction)
                                        <a href="/auctions/{{ $artwork->id }}" class="btn btn-secondary">Place Bid</a>
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

    <div class="container-fluid iframeWrapper variant- align-"
        style="padding-top: 30px; padding-bottom: 0px; background: #fff;">
        <div class="collapsed-heading">
            <details open>
                <summary class="container align-">
                    <div class="inner">
                        <span class='peppered-icon icon-chevron-down'><svg version='1.1'
                                xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px'
                                y='0px' width='30' height='30' viewBox='0 0 20 20' xml:space='preserve'>
                                <g transform='matrix(1,0,0,1,5,2)'>
                                    <path d='M5,11L0,6L1.5,4.5L5,8.25L8.5,4.5L10,6L5,11Z' />
                                </g>
                            </svg></span>
                        Sign up to our Exhibition Newsletter
                    </div>
                </summary>
                <div class="container">
                    <div class="inner">
                        <iframe src="/exhibition-newsletter-signup" height="400px" width="100%"
                            allow="autoplay; fullscreen" allowtransparency="true" allowfullscreen="true"></iframe>
                    </div>
                </div>
            </details>
        </div>
    </div>

    <div class="container-fluid desc2Wrapper variant- align-"
        style="padding-top: 0px; padding-bottom: 0px; background: #fff;">
        <div class="container">
            <div class="richtext">
                <h2>Useful Information</h2>
            </div>
        </div>
    </div>

    <div class="container-fluid mediaWrapper multi variant-masonry arrows" data-animations=""
        style="padding-top: 20px; padding-bottom: 30px; background: #fff;">
        <div class="container">
            <div class="grid-root">
                <a class="imageCard" href="/exhibition-tours">
                    <div class="wrapper">
                        <img src="https://img.thelowry.com/BM1_oS6aAg-1k_afGjgIkul3gNhaaBEO3G62hBYcpRc/c:5350:5350/s:900:900:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzEzODZfb3JpZy5qcGc"
                            alt="Book a Tour">
                    </div>
                    <div class="content">
                        <div class="title">Book a Guided Tour</div>
                        <div class="desc">Explore with our experts ></div>
                    </div>
                </a>

                <a class="imageCard" href="/plan-your-visit">
                    <div class="wrapper">
                        <img src="https://img.thelowry.com/hvowjLzFRgW1YiE1b0zSJMwjklIE5AXdzWIJg8o_-g8/c:5464:5464/s:900:900:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzEzODdfb3JpZy5qcGc"
                            alt="Plan Your Visit">
                    </div>
                    <div class="content">
                        <div class="title">Plan Your Visit</div>
                        <div class="desc">Access and facilities ></div>
                    </div>
                </a>

                <a class="imageCard" href="/exhibition-shop">
                    <div class="wrapper">
                        <img src="https://img.thelowry.com/kE6e9bt_boJCLFzAjRoni07kf5oY9XTUG45UH6bC-Vg/c:5464:5464/s:900:900:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzE0MThfb3JpZy5qcGc"
                            alt="Exhibition Shop">
                    </div>
                    <div class="content">
                        <div class="title">Exhibition Shop</div>
                        <div class="desc">Catalogues and merchandise ></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</main>
@include('home.footer')