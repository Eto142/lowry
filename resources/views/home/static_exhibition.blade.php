@include('home.header')
<main id="content">

    <span id="pp_page_instance_746"></span>

    <div class="container-fluid" style="padding: 0; position: relative; overflow: hidden; max-height: 400px;">
        <img src="{{ asset('images/permanent-collection.png') }}" alt="Zyrelis Gallery Permanent Collection"
            style="width: 100%; height: 400px; object-fit: cover; display: block; filter: brightness(0.7);">
        <div
            style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; flex-direction: column; color: #fff; text-align: center; padding: 40px;">
            <h1
                style="font-family: 'Playfair Display', serif; font-size: clamp(28px, 4vw, 52px); font-weight: 600; margin: 0; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                Permanent Collection</h1>
            <p style="font-size: 1.1rem; margin-top: 12px; opacity: 0.9; text-shadow: 0 1px 5px rgba(0,0,0,0.3);">
                Masterpieces from renowned contemporary artists</p>
        </div>
    </div>

    <div class="container-fluid infoHeaderWrapper type-page align-"
        style="padding-top: 30px; padding-bottom: 30px; background: #fff;">
        <div class="container">
            <div class="infoHeaderContent">
                <div class="inner">
                    <h1>Zyrelis Gallery Permanent Collection</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid desc2Wrapper variant- align-"
        style="padding-top: 30px; padding-bottom: 30px; background: #CCD5D8;">
        <div class="container">
            <div class="richtext">
                <p>Explore our esteemed permanent collection featuring masterpieces from renowned contemporary artists.
                    This curated selection represents the core of Zyrelis Gallery 's artistic vision and legacy.</p>

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