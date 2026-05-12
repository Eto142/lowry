@include('home.header')

<link rel="stylesheet" href="{{ asset('css/homepage.css') }}">

<main id="content">

    <span id="pp_page_instance_746"></span>

    {{-- ===== HERO SECTION WITH IMAGE SLIDER ===== --}}
    <section class="hp-hero" id="hp-hero">
        <div class="hp-hero__slider">
            <div class="hp-hero__slide active">
                <img src="{{ asset('images/hero-gallery.png') }}" alt="Zyrelis Gallery Gallery">
            </div>
            <div class="hp-hero__slide">
                <img src="{{ asset('images/gallery-exhibition.png') }}" alt="Contemporary Exhibition">
            </div>
            <div class="hp-hero__slide">
                <img src="{{ asset('images/abstract-painting.png') }}" alt="Abstract Art Collection">
            </div>
        </div>
        <div class="hp-hero__overlay"></div>
        <div class="hp-hero__content">
            <p class="hp-hero__tagline">Contemporary Art &middot; Since 2020</p>
            <h1 class="hp-hero__title">Where Art Meets Imagination</h1>
            <p class="hp-hero__subtitle">Discover extraordinary contemporary art from emerging and established artists around the world.</p>
            <a href="{{ route('current.exhibitions') }}" class="hp-hero__cta">
                Explore Exhibitions
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
        <div class="hp-hero__dots">
            <span class="hp-hero__dot active" data-slide="0"></span>
            <span class="hp-hero__dot" data-slide="1"></span>
            <span class="hp-hero__dot" data-slide="2"></span>
        </div>
    </section>

    {{-- ===== ABOUT SECTION ===== --}}
    <section class="hp-section" id="hp-about">
        <div class="hp-container">
            <div class="hp-about">
                <div class="hp-about__image hp-animate">
                    <img src="{{ asset('images/artist-at-work.png') }}" alt="Artist at Work">
                    <div class="hp-about__image-accent"></div>
                </div>
                <div class="hp-about__content hp-animate hp-animate--delay-2">
                    <span class="hp-about__label">Our Story</span>
                    <h2 class="hp-about__title">A Vibrant Space Where Creativity Meets Connection</h2>
                    <p class="hp-about__text">
                        Welcome to Zyrelis Gallery, a forward-thinking digital art gallery and marketplace dedicated to showcasing contemporary art from emerging and established artists around the world.
                    </p>
                    <p class="hp-about__text">
                        At Zyrelis Gallery, we believe art is more than just visual expression — it's a powerful dialogue between the artist and the viewer. Our platform celebrates that dialogue by making contemporary art accessible, discoverable, and collectible for everyone, everywhere.
                    </p>
                    <div class="hp-about__stats">
                        <div class="hp-stat">
                            <span class="hp-stat__number">500+</span>
                            <span class="hp-stat__label">Artworks</span>
                        </div>
                        <div class="hp-stat">
                            <span class="hp-stat__number">120+</span>
                            <span class="hp-stat__label">Artists</span>
                        </div>
                        <div class="hp-stat">
                            <span class="hp-stat__number">50+</span>
                            <span class="hp-stat__label">Exhibitions</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== IMAGE MOSAIC GALLERY ===== --}}
    <section class="hp-section hp-section--warm" id="hp-gallery">
        <div class="hp-container">
            <div class="hp-section__header hp-animate">
                <span class="hp-section__label">Gallery</span>
                <h2 class="hp-section__title">Explore Our Spaces</h2>
                <div class="hp-section__divider"></div>
                <p class="hp-section__desc">Step inside our world of curated exhibitions and immersive art experiences.</p>
            </div>
            <div class="hp-mosaic hp-animate hp-animate--delay-1">
                <div class="hp-mosaic__item">
                    <img src="{{ asset('images/hero-gallery.png') }}" alt="Main Gallery Hall">
                    <div class="hp-mosaic__overlay">
                        <span class="hp-mosaic__caption">Main Gallery Hall</span>
                    </div>
                </div>
                <div class="hp-mosaic__item">
                    <img src="{{ asset('images/gallery-exhibition.png') }}" alt="Exhibition Wing">
                    <div class="hp-mosaic__overlay">
                        <span class="hp-mosaic__caption">Exhibition Wing</span>
                    </div>
                </div>
                <div class="hp-mosaic__item">
                    <img src="{{ asset('images/sculpture-gallery.png') }}" alt="Sculpture Garden">
                    <div class="hp-mosaic__overlay">
                        <span class="hp-mosaic__caption">Sculpture Garden</span>
                    </div>
                </div>
                <div class="hp-mosaic__item">
                    <img src="{{ asset('images/abstract-painting.png') }}" alt="Modern Art Collection">
                    <div class="hp-mosaic__overlay">
                        <span class="hp-mosaic__caption">Modern Art Collection</span>
                    </div>
                </div>
                <div class="hp-mosaic__item">
                    <img src="{{ asset('images/gallery-event.png') }}" alt="Gallery Events">
                    <div class="hp-mosaic__overlay">
                        <span class="hp-mosaic__caption">Gallery Events</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== CURRENT EXHIBITIONS ===== --}}
    <section class="hp-section hp-exhibitions" id="hp-exhibitions">
        <div class="hp-container">
            <div class="hp-section__header hp-animate">
                <span class="hp-section__label">Now Showing</span>
                <h2 class="hp-section__title">Current Exhibitions</h2>
                <div class="hp-section__divider"></div>
                <p class="hp-section__desc">Discover what's on view in our galleries today.</p>
            </div>
            <div class="hp-exhibitions__grid">
                <a class="hp-exhibition-card hp-animate hp-animate--delay-1" href="{{ route('current.exhibitions') }}">
                    <div class="hp-exhibition-card__image">
                        <img src="{{ asset('images/gallery-exhibition.png') }}" alt="Current Exhibition">
                        <span class="hp-exhibition-card__badge">Now Open</span>
                    </div>
                    <div class="hp-exhibition-card__body">
                        <h3 class="hp-exhibition-card__title">Current Exhibition</h3>
                        <span class="hp-exhibition-card__meta">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            Open Now &middot; Free Entry
                        </span>
                    </div>
                    <div class="hp-exhibition-card__arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </div>
                </a>

                @auth
                    <a class="hp-exhibition-card hp-animate hp-animate--delay-2" href="{{ route('future.exhibitions') }}">
                @else
                    <a class="hp-exhibition-card hp-animate hp-animate--delay-2" href="{{ route('login') }}">
                @endauth
                    <div class="hp-exhibition-card__image">
                        <img src="{{ asset('images/abstract-painting.png') }}" alt="Upcoming Exhibition">
                        <span class="hp-exhibition-card__badge">Coming Soon</span>
                    </div>
                    <div class="hp-exhibition-card__body">
                        <h3 class="hp-exhibition-card__title">Future Exhibition</h3>
                        <span class="hp-exhibition-card__meta">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Upcoming &middot; Preview Available
                        </span>
                    </div>
                    <div class="hp-exhibition-card__arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </div>
                </a>

                <a class="hp-exhibition-card hp-animate hp-animate--delay-3" href="{{ route('past.exhibitions') }}">
                    <div class="hp-exhibition-card__image">
                        <img src="{{ asset('images/sculpture-gallery.png') }}" alt="Past Exhibition">
                        <span class="hp-exhibition-card__badge">Archive</span>
                    </div>
                    <div class="hp-exhibition-card__body">
                        <h3 class="hp-exhibition-card__title">Past Exhibitions</h3>
                        <span class="hp-exhibition-card__meta">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Browse Archive
                        </span>
                    </div>
                    <div class="hp-exhibition-card__arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- ===== VISITING INFORMATION ===== --}}
    <section class="hp-section hp-section--warm" id="hp-visit">
        <div class="hp-container">
            <div class="hp-section__header hp-animate">
                <span class="hp-section__label">Plan Your Visit</span>
                <h2 class="hp-section__title">Visit Our Gallery</h2>
                <div class="hp-section__divider"></div>
            </div>
            <div class="hp-visit hp-animate hp-animate--delay-1">
                <div class="hp-visit__info">
                    <h3 class="hp-visit__title">Opening Hours</h3>
                    <ul class="hp-visit__hours">
                        <li><span>Monday</span><strong>Closed</strong></li>
                        <li><span>Tuesday – Friday</span><strong>11:00 – 17:00</strong></li>
                        <li><span>Saturday – Sunday</span><strong>10:00 – 17:00</strong></li>
                    </ul>
                    <div class="hp-visit__note">
                        <strong>Free Entry.</strong> Our Exhibition Halls are located in the main gallery complex and are free to enter for all visitors. Donations are welcome to support our programs.
                    </div>
                </div>
                <div class="hp-visit__features">
                    <div class="hp-feature-card">
                        <div class="hp-feature-card__icon">🎨</div>
                        <h4 class="hp-feature-card__title">IMMERSIVE 360</h4>
                        <p class="hp-feature-card__desc">15-minute immersive time slots. Separate free ticket required.</p>
                    </div>
                    <div class="hp-feature-card">
                        <div class="hp-feature-card__icon">🖼️</div>
                        <h4 class="hp-feature-card__title">Modern Perspectives</h4>
                        <p class="hp-feature-card__desc">Contemporary art exhibition. Plan about one hour for the full experience.</p>
                    </div>
                    <div class="hp-feature-card">
                        <div class="hp-feature-card__icon">🧘</div>
                        <h4 class="hp-feature-card__title">Relaxed Sessions</h4>
                        <p class="hp-feature-card__desc">Calm viewing environment for individuals and families who prefer a quieter experience.</p>
                    </div>
                    <div class="hp-feature-card">
                        <div class="hp-feature-card__icon">🎟️</div>
                        <h4 class="hp-feature-card__title">Free Tickets</h4>
                        <p class="hp-feature-card__desc">All exhibitions are free. Book separate tickets for each experience.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== ART MARKETPLACE ===== --}}
    <section class="hp-section" id="hp-marketplace">
        <div class="hp-container">
            <div class="hp-section__header hp-animate">
                <span class="hp-section__label">Collect</span>
                <h2 class="hp-section__title">Art Marketplace</h2>
                <div class="hp-section__divider"></div>
                <p class="hp-section__desc">Browse and purchase artwork directly from our platform. Our secure payment system connects you with the artists.</p>
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
                                    background-image: url('{{ $artwork->image_url ?? 'https://via.placeholder.com/855x600' }}');
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
                                                    <strong>Live Auction:</strong> {{ $artwork->auction_end->format('D d M Y H:i') }}
                                                </div>
                                            @endif
                                        </a>
                                        <div class="meta">
                                            <div class="meta-group">
                                                <ul class="genres">
                                                    <li class="genres__item"><a class="genres__link" href="#">{{ $artwork->medium }}</a></li>
                                                    <li class="genres__item"><a class="genres__link" href="#">{{ $artwork->category }}</a></li>
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
    </section>

    {{-- ===== CTA BANNER ===== --}}
    <section class="hp-section" id="hp-cta" style="padding-bottom: 100px;">
        <div class="hp-cta-banner hp-animate">
            <div class="hp-cta-banner__bg">
                <img src="{{ asset('images/gallery-event.png') }}" alt="Join Zyrelis Gallery">
            </div>
            <div class="hp-cta-banner__overlay"></div>
            <div class="hp-cta-banner__content">
                <h2 class="hp-cta-banner__title">Join Our Creative Community</h2>
                <p class="hp-cta-banner__text">Whether you're an art enthusiast, a seasoned collector, or simply looking to bring more beauty into your space.</p>
                <a href="{{ route('login') }}" class="hp-cta-banner__btn">
                    Get Started
                    <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>
        </div>
    </section>

</main>

{{-- ===== HERO SLIDER SCRIPT ===== --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero slider
    const slides = document.querySelectorAll('.hp-hero__slide');
    const dots = document.querySelectorAll('.hp-hero__dot');
    let currentSlide = 0;
    let slideInterval;

    function goToSlide(n) {
        slides[currentSlide].classList.remove('active');
        dots[currentSlide].classList.remove('active');
        currentSlide = n;
        slides[currentSlide].classList.add('active');
        dots[currentSlide].classList.add('active');
    }

    function nextSlide() {
        goToSlide((currentSlide + 1) % slides.length);
    }

    function startSlider() {
        slideInterval = setInterval(nextSlide, 5000);
    }

    dots.forEach(dot => {
        dot.addEventListener('click', function() {
            clearInterval(slideInterval);
            goToSlide(parseInt(this.dataset.slide));
            startSlider();
        });
    });

    startSlider();

    // Scroll animations
    const animateElements = document.querySelectorAll('.hp-animate');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px'
    });

    animateElements.forEach(el => observer.observe(el));
});
</script>

@include('home.footer')