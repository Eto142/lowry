@include('home.header')

<main id="content">
    <!-- Hero Section -->
    <div class="container-fluid hero-section" style="padding-top: 80px; padding-bottom: 80px; background: #f8f9fa;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 mb-4">LS Ziiriel Contemporary Art Gallery Permanent Collection</h1>
                    <p class="lead">A curated selection of significant works that define our artistic legacy</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Collection Intro -->
    <div class="container-fluid collection-intro" style="padding-top: 60px; padding-bottom: 60px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="richtext text-center">
                        <h2 class="mb-4">Our Enduring Collection</h2>
                        <p class="mb-4">The LS Ziiriel Permanent Collection represents the cornerstone of our gallery's
                            identity, featuring seminal works that have shaped contemporary art discourse. This
                            carefully assembled collection spans multiple mediums, movements, and generations of
                            artists.</p>
                        <p>Established in 2023, our permanent collection grows through strategic acquisitions and
                            generous donations, serving as a cultural resource for researchers, students, and art
                            enthusiasts worldwide.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Collection Highlights -->
    <div class="container-fluid highlights-section" style="padding-top: 0px; padding-bottom: 60px; background: #fff;">
        <div class="container">
            <h2 class="text-center mb-5">Collection Highlights</h2>

            <div class="row">
                <!-- Featured Artwork 1 -->
                <div class="col-md-6 col-lg-4 mb-5">
                    <div class="artwork-card h-100">
                        <div class="artwork-image"
                            style="background-image: url('https://img.thelowry.com/15fmDdPEuQ9T-0gb2DlmVwAxcW4169AZcVGi5nngCDk/c:8021:4511:fp:0.5:0.33/s:800:600:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzEzNjRfb3JpZy5qcGc');">
                            <div class="overlay d-flex align-items-center justify-content-center">
                                <a href="/artwork/digital-dawn" class="btn btn-outline-light">View Details</a>
                            </div>
                        </div>
                        <div class="artwork-info p-4">
                            <h3>Digital Dawn</h3>
                            <p class="artist-name">by Elena Vasquez (2022)</p>
                            <p class="medium">Mixed media on digital canvas</p>
                            <p class="description">A groundbreaking exploration of digital-native aesthetics in physical
                                form.</p>
                        </div>
                    </div>
                </div>

                <!-- Featured Artwork 2 -->
                <div class="col-md-6 col-lg-4 mb-5">
                    <div class="artwork-card h-100">
                        <div class="artwork-image"
                            style="background-image: url('https://via.placeholder.com/800x600');">
                            <div class="overlay d-flex align-items-center justify-content-center">
                                <a href="/artwork/urban-echoes" class="btn btn-outline-light">View Details</a>
                            </div>
                        </div>
                        <div class="artwork-info p-4">
                            <h3>Urban Echoes</h3>
                            <p class="artist-name">by Jamal Carter (2021)</p>
                            <p class="medium">Acrylic and spray paint on canvas</p>
                            <p class="description">Vibrant street-inspired abstraction capturing city rhythms.</p>
                        </div>
                    </div>
                </div>

                <!-- Featured Artwork 3 -->
                <div class="col-md-6 col-lg-4 mb-5">
                    <div class="artwork-card h-100">
                        <div class="artwork-image"
                            style="background-image: url('https://via.placeholder.com/800x600/333/fff');">
                            <div class="overlay d-flex align-items-center justify-content-center">
                                <a href="/artwork/silent-dialogue" class="btn btn-outline-light">View Details</a>
                            </div>
                        </div>
                        <div class="artwork-info p-4">
                            <h3>Silent Dialogue</h3>
                            <p class="artist-name">by Lin Wei (2020)</p>
                            <p class="medium">Porcelain and interactive digital projection</p>
                            <p class="description">A meditation on traditional craftsmanship in digital age.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="/collection/browse" class="btn btn-primary btn-lg">Browse Full Collection</a>
            </div>
        </div>
    </div>

    <!-- Collection Themes -->
    <div class="container-fluid themes-section" style="padding-top: 60px; padding-bottom: 60px; background: #CCD5D8;">
        <div class="container">
            <h2 class="text-center mb-5">Collection Themes</h2>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="theme-card p-4 h-100" style="background: #fff;">
                        <h3 class="mb-3">Digital Frontiers</h3>
                        <p>Exploring the intersection of technology and traditional art forms, featuring works that
                            challenge our understanding of medium and materiality.</p>
                        <a href="/collection/digital-frontiers" class="btn btn-sm btn-outline-dark mt-3">View Theme</a>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="theme-card p-4 h-100" style="background: #fff;">
                        <h3 class="mb-3">Global Voices</h3>
                        <p>A celebration of cultural diversity, showcasing artists from underrepresented regions and
                            diaspora communities.</p>
                        <a href="/collection/global-voices" class="btn btn-sm btn-outline-dark mt-3">View Theme</a>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="theme-card p-4 h-100" style="background: #fff;">
                        <h3 class="mb-3">Material Experiments</h3>
                        <p>Innovative uses of unconventional materials that push the boundaries of contemporary art
                            practice.</p>
                        <a href="/collection/material-experiments" class="btn btn-sm btn-outline-dark mt-3">View
                            Theme</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Collection History -->
    <div class="container-fluid history-section" style="padding-top: 60px; padding-bottom: 60px; background: #fff;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="mb-4">Collection History</h2>
                    <p>The LS Ziiriel Permanent Collection began with a core group of 12 works donated by our founding
                        artists. Through thoughtful acquisition and the generous support of our patrons, the collection
                        has grown to encompass over 200 significant contemporary works.</p>
                    <p>Our collection strategy focuses on:</p>
                    <ul>
                        <li>Supporting mid-career artists at pivotal moments</li>
                        <li>Preserving important digital and ephemeral works</li>
                        <li>Documenting artistic responses to global issues</li>
                        <li>Showcasing innovative technical approaches</li>
                    </ul>
                    <a href="/collection/history" class="btn btn-outline-primary mt-3">Learn More About Our History</a>
                </div>
                <div class="col-lg-6">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-year">2023</div>
                            <div class="timeline-content">
                                <h4>Collection Founded</h4>
                                <p>Initial acquisition of 12 works establishing the collection's core</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-year">2024</div>
                            <div class="timeline-content">
                                <h4>Digital Expansion</h4>
                                <p>Added first fully digital-native artworks to the collection</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-year">2025</div>
                            <div class="timeline-content">
                                <h4>Global Reach</h4>
                                <p>Collection expanded to include artists from 6 continents</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Research & Access -->
    <div class="container-fluid research-section" style="padding-top: 60px; padding-bottom: 60px; background: #f8f9fa;">
        <div class="container">
            <h2 class="text-center mb-5">Research & Access</h2>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="research-card text-center p-4 h-100" style="background: #fff;">
                        <div class="icon mb-3" style="font-size: 2.5rem;">📚</div>
                        <h3>Scholar Access</h3>
                        <p>Schedule research visits to study works in person from our collection storage.</p>
                        <a href="/research/access" class="btn btn-sm btn-outline-dark">Request Access</a>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="research-card text-center p-4 h-100" style="background: #fff;">
                        <div class="icon mb-3" style="font-size: 2.5rem;">🖼️</div>
                        <h3>Digital Archive</h3>
                        <p>Explore high-resolution images and documentation of all collection works.</p>
                        <a href="/collection/archive" class="btn btn-sm btn-outline-dark">Browse Archive</a>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="research-card text-center p-4 h-100" style="background: #fff;">
                        <div class="icon mb-3" style="font-size: 2.5rem;">📝</div>
                        <h3>Publication Rights</h3>
                        <p>Information about reproducing collection works for academic and media use.</p>
                        <a href="/rights-reproductions" class="btn btn-sm btn-outline-dark">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('home.footer')

<style>
    .hero-section {
        background: linear-gradient(rgba(248, 249, 250, 0.9), rgba(248, 249, 250, 0.9)),
            url('https://img.thelowry.com/15fmDdPEuQ9T-0gb2DlmVwAxcW4169AZcVGi5nngCDk/c:8021:4511:fp:0.5:0.33/s:1920:1080:1/aHR0cHM6Ly90aGVsb3dyeS5jb20vL2Ntc19maWxlcy9zeXN0ZW0vaW1hZ2VzL2ltZzEzNjRfb3JpZy5qcGc');
        background-size: cover;
        background-position: center;
    }

    .artwork-card {
        border: 1px solid #eee;
        transition: all 0.3s ease;
    }

    .artwork-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .artwork-image {
        height: 300px;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.3);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .artwork-card:hover .overlay {
        opacity: 1;
    }

    .artwork-info {
        background: #fff;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline:before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #000;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }

    .timeline-year {
        position: absolute;
        left: -30px;
        top: 0;
        width: 60px;
        height: 60px;
        line-height: 60px;
        text-align: center;
        background: #000;
        color: #fff;
        border-radius: 50%;
        font-weight: bold;
    }

    .timeline-content {
        margin-left: 40px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 4px;
    }

    .research-card {
        transition: all 0.3s ease;
    }

    .research-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
</style>