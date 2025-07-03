@include('home.header')
<main id="content">

    <span id="pp_page_instance_746"></span>

    <div class="container-fluid align-" style="padding-top: 30px; padding-bottom: 30px; background: #fff;">
        <div class="container">
            <div class="infoHeaderBoxedImage" data-animations="">
                <picture class="picture" style="--picture-aspect-ratio: 16/9">
                    <source media="(max-width: 991px)" srcset="images/home.jpg">
                    <source media="(min-width: 992px)" srcset="images/home.jpg">
                    <img class="picture__image" src="images/home.jpg" alt="Exhibition Hall">
                </picture>
            </div>
        </div>
    </div>
    <!-- About Us Section Added Here -->
    <div class="container-fluid desc2Wrapper variant- align-"
        style="padding-top: 30px; padding-bottom: 30px; background: #f8f9fa;">
        <div class="container">
            <div class="richtext">
                <h2>About Ziiriel Art House</h2>
                <p>Welcome to Ziiriel Art House, a vibrant online space where creativity meets
                    connection. We are a forward-thinking digital art gallery and marketplace dedicated to showcasing
                    contemporary art from emerging and established artists around the world.</p>

                <p>At Ziiriel, we believe art is more than just visual expression — it's a powerful dialogue between the
                    artist and the viewer. Our platform is designed to celebrate that dialogue by making contemporary
                    art accessible, discoverable, and collectible for everyone, everywhere.</p>

                <p>Whether you're an art enthusiast, a seasoned collector, or simply looking to bring more beauty into
                    your space, Ziiriel offers a curated selection of paintings, illustrations, drawings, mixed media
                    works, and more — all available for purchase directly from the artists. We provide a seamless
                    experience that connects creators with collectors, helping to support the global art community and
                    elevate new voices in contemporary art.</p>

                <p>Ziiriel is not just a gallery — it's a movement. We are passionate about nurturing artistic talent
                    and giving artists the visibility they deserve in today's digital world. Join us on this journey and
                    discover a world of contemporary art that inspires, challenges, and resonates.</p>
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
                    <a href="mailto:support@ziiriel-arthouse.com">plan your visit</a>


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
            <ul class="media-list">
                @foreach($pastExhibitions as $exhibition)
                <li class="media-item">
                    @if(!empty($exhibition->video_url))
                    <!-- Show video only if it exists -->
                    <video controls class="media-element">
                        <source src="{{ $exhibition->video_url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    @else
                    <!-- Fallback to picture if no video -->
                    <img class="media-element"
                        src="{{ $exhibition->picture_url ?? 'https://via.placeholder.com/855x600' }}"
                        alt="Exhibition media">
                    @endif
                </li>
                @endforeach
            </ul>
        </div>

        <style>
            .media-list {
                list-style: none;
                padding: 0;
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                gap: 20px;
            }

            .media-item {
                aspect-ratio: 16/9;
            }

            .media-element {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
            }

            video.media-element {
                background: #000;
            }
        </style>
    </div>



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



    <div class="container-fluid mediaWrapper multi variant-masonry arrows" data-animations=""
        style="padding-top: 20px; padding-bottom: 30px; background: #fff;">
        <div class="container">
            <div class="grid-root">
                @auth
                <a class="imageCard" href="{{route('future.exhibitions')}}">
                    <div class="wrapper">
                        <img src="https://img.thelowry.com/BM1_oS6aAg-1k_afGjgIkul3gNhaaBEO3G62hBYcpRc/c:5350:5350/s:900:900:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzEzODZfb3JpZy5qcGc"
                            alt="Book a Tour">
                    </div>
                    <div class="content">
                        <div class="title">Future Exhibition</div>
                    </div>
                </a>
                @else
                <a class="imageCard" href="{{route('login')}}">
                    <div class="wrapper">
                        <img src="https://img.thelowry.com/BM1_oS6aAg-1k_afGjgIkul3gNhaaBEO3G62hBYcpRc/c:5350:5350/s:900:900:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzEzODZfb3JpZy5qcGc"
                            alt="Book a Tour">
                    </div>
                    <div class="content">
                        <div class="title">Future Exhibition</div>
                    </div>
                </a>
                @endauth

                <a class="imageCard" href="{{route('current.exhibitions')}}">
                    <div class="wrapper">
                        <img src="https://img.thelowry.com/hvowjLzFRgW1YiE1b0zSJMwjklIE5AXdzWIJg8o_-g8/c:5464:5464/s:900:900:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzEzODdfb3JpZy5qcGc"
                            alt="Plan Your Visit">
                    </div>
                    <div class="content">
                        <div class="title">Current Exhibition</div>
                    </div>
                </a>

                <a class="imageCard" href="{{route('past.exhibitions')}}">
                    <div class="wrapper">
                        <img src="https://img.thelowry.com/kE6e9bt_boJCLFzAjRoni07kf5oY9XTUG45UH6bC-Vg/c:5464:5464/s:900:900:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzE0MThfb3JpZy5qcGc"
                            alt="Exhibition Shop">
                    </div>
                    <div class="content">
                        <div class="title">Past Exhibition</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</main>
@include('home.footer')