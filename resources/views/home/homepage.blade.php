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
                <h2>Permanent Collection</h2>
            </div>
        </div>
    </div>

    <div class="container-fluid mediaWrapper single variant-boxed_caption" data-animations=""
        style="padding-top: 30px; padding-bottom: 30px; background: #fff;">
        <div class="container">
            <a class="captionWrapper" href="/permanent-collection">
                <div class="wrapper imgBox">
                    <img src="https://img.thelowry.com/FROID8QTU1PYzaf4fkuGQ-iLb8cQIkHxgbQ40gQ_GwA/rs:fit:1920:800:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzE0MTdfb3JpZy5qcGc"
                        alt="Permanent Collection">
                </div>
                <div class="caption">
                    <div class="title">Modern Perspectives: The Permanent Collection</div>
                    <div class="desc">Explore our comprehensive collection of contemporary artworks</div>
                </div>
            </a>
        </div>
    </div>

    <div class="container-fluid desc2Wrapper variant- align-"
        style="padding-top: 50px; padding-bottom: 20px; background: #fff;">
        <div class="container">
            <div class="richtext">
                <h2>Exhibition</h2>
            </div>
        </div>
    </div>

    <div class="container-fluid listWrapper theme" style="padding-top: 0px; padding-bottom: 0px; background: #fff;">
        <div class="container">
            <ul data-animations="zoom" class="listItems variant-">
                @foreach($exhibitions as $exhibition)
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
                                    <div style="display: flex; align-items: center;">
                                        <h2 class="title">{{ $exhibition->title }}</h2>
                                        @if($exhibition->admin_id)
                                        <img src="{{ $exhibition->picture }}" alt="Official Exhibition"
                                            style="width: 20px; height: 20px; margin-left: 10px;">
                                        @endif
                                    </div>
                                    <div class="subtitle">{{ Str::limit($exhibition->description, 100) }}</div>
                                    <div class="top-date">
                                        <span class="start">{{ $exhibition->created_at->format('D d M Y') }}<span
                                                class="time">10:00AM</span></span>
                                        @if($exhibition->end_date)
                                        <span class="separator"></span>
                                        <span class="end">{{ $exhibition->end_date->format('D d M Y') }}<span
                                                class="time">4:30PM</span></span>
                                        @endif
                                    </div>
                                    <div class="venue">{{ $exhibition->venue ?? 'Main Exhibition Hall' }}</div>
                                    @if($exhibition->seller_name)
                                    <div class="seller-info" style="font-style: italic; margin-top: 5px;">
                                        @if($exhibition->admin_id)
                                        Curated by: {{ $exhibition->seller_name }}
                                        @else
                                        Presented by: {{ $exhibition->seller_name }}
                                        @endif
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



    <div class="container-fluid desc2Wrapper variant- align-"
        style="padding-top: 70px; padding-bottom: 0px; background: #fff;">
        <div class="container">
            <div class="richtext">
                <h2>Current & Upcoming Exhibitions</h2>
            </div>
        </div>
    </div>

    <div class="container-fluid listWrapper theme" style="padding-top: 0px; padding-bottom: 0px; background: #fff;">
        <div class="container">
            <ul data-animations="zoom" class="listItems variant-">
                <li data-entry-id="388"
                    class="eventCard context-default production-type-default variant-default topdate">
                    <style>
                        [data-entry-id="388"] .thumb .image {
                            background-image: url('https://img.thelowry.com/3xGmoQMm4Kg6pQTRGWnFxUf1a2dL9gknHJAdCnUTRYI/c:4624:3244:fp:0.5:0.33/s:855:600:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzI2NTNfb3JpZy5qcGc');
                        }
                    </style>
                    <div class="listItemWrapper">
                        <div class="thumb">
                            <a class="image" href="/immersive-360" tabindex="-1"></a>
                        </div>
                        <div class="inner">
                            <div class="descMetaContainer">
                                <a class="desc" href="/immersive-360">
                                    <h2 class="title">Immersive 360</h2>
                                    <div class="subtitle"></div>
                                    <div class="top-date">
                                        <span class="start">Sat 03 May 2025<span class="time">10:00AM</span></span>
                                        <span class="separator"></span>
                                        <span class="end">Sun 31 Aug 2025<span class="time">4:30PM</span></span>
                                    </div>
                                    <div class="venue">Main Exhibition Hall</div>
                                </a>
                                <div class="meta">
                                    <div class="meta-group">
                                        <ul class="genres">
                                            <li class="genres__item"><a class="genres__link"
                                                    href="/whats-on?genres[0]=17">Interactive</a></li>
                                        </ul>
                                    </div>
                                    <div class="meta-group button">
                                        <a href="/immersive-360" class="btn btn-active">Dates</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <li data-entry-id="389"
                    class="eventCard context-default production-type-default variant-default topdate">
                    <style>
                        [data-entry-id="389"] .thumb .image {
                            background-image: url('https://img.thelowry.com/B-D2rzbZQ5iN7HjHxSqykRSxDa1vEEwAnvOTbyUCpd0/c:1539:1080/s:855:600:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzI4MzNfb3JpZy5qcGc');
                        }
                    </style>
                    <div class="listItemWrapper">
                        <div class="thumb">
                            <a class="image" href="/modern-perspectives" tabindex="-1"></a>
                        </div>
                        <div class="inner">
                            <div class="descMetaContainer">
                                <a class="desc" href="/modern-perspectives">
                                    <h2 class="title">Modern Perspectives: Contemporary Art Exhibition</h2>
                                    <div class="subtitle"></div>
                                    <div class="top-date">
                                        <span class="start">Sat 19 Jul 2025<span class="time">10:00AM</span></span>
                                        <span class="separator"></span>
                                        <span class="end">Sun 31 Aug 2025<span class="time">4:30PM</span></span>
                                    </div>
                                    <div class="venue">Gallery East</div>
                                </a>
                                <div class="meta">
                                    <div class="meta-group">
                                        <ul class="genres">
                                            <li class="genres__item"><a class="genres__link"
                                                    href="/whats-on?genres[0]=17">Contemporary</a></li>
                                        </ul>
                                    </div>
                                    <div class="meta-group button">
                                        <a href="/modern-perspectives" class="btn btn-active">Dates</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="container-fluid pageListWrapper variant-grid variant-boxed arrows"
        style="padding-top: 0px; padding-bottom: 0px; background: #fff;">
        <div class="inner">
            <div class="pageItems grid-root" data-animations="">
                <section class="pageCard flat">
                    <a href="/upcoming-exhibition1" class="thumb" aria-label="Global Visions">
                        <img src="https://img.thelowry.com/x-ZnYo6lqraXzp70uvf07-T1-chpy83VB58YRA2kzB4/c:6000:3391:fp:0.5:0.33/s:690:390:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzI1NzBfb3JpZy5qcGc"
                            alt="Global Visions">
                    </a>
                    <div class="desc">
                        <a class="desc-link" href="/upcoming-exhibition1">
                            <h3>Global Visions</h3>
                            <div class="richtext">Opens Thu 25 Sep 2025 - FREE ENTRY</div>
                        </a>
                    </div>
                </section>

                <section class="pageCard flat">
                    <a href="/upcoming-exhibition2" class="thumb" aria-label="Urban Expressions">
                        <img src="https://img.thelowry.com/QsY_oIvcCueedU1HXwKEz2stkCtF9ytPkuEd7wLy70E/c:1280:723:fp:0.5:0.33/s:690:390:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzI1NjZfb3JpZy5qcGc"
                            alt="Urban Expressions">
                    </a>
                    <div class="desc">
                        <a class="desc-link" href="/upcoming-exhibition2">
                            <h3>Urban Expressions</h3>
                            <div class="richtext">Open from Fri 26 Sep 2025 - FREE ENTRY</div>
                        </a>
                    </div>
                </section>

                <section class="pageCard flat">
                    <a href="/upcoming-exhibition3" class="thumb" aria-label="Color Theory">
                        <img src="https://img.thelowry.com/3hpXCljzjlXxfyeHyWnSct275tdWSDm1LATr3o9zp_o/c:1362:770/s:690:390:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzE0MTVfb3JpZy5qcGc"
                            alt="Color Theory">
                    </a>
                    <div class="desc">
                        <a class="desc-link" href="/upcoming-exhibition3">
                            <h3>Color Theory</h3>
                            <div class="richtext">An Exhibition Celebrating Modern Artists</div>
                        </a>
                    </div>
                </section>
            </div>
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