@include('home.header')

<main id="content">
    <div class="container-fluid hero-section" style="padding-top: 80px; padding-bottom: 80px; background: #f8f9fa;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 mb-4">About Ziiriel Contemporary</h1>
                    <p class="lead">Where creativity meets connection in the digital art world.</p>
                </div>

            </div>
        </div>
    </div>

    <div class="container-fluid about-content" style="padding-top: 60px; padding-bottom: 60px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="richtext">
                        <h2 class="mb-4">Our Story</h2>
                        <p class="mb-4">Founded in 2023, Ziiriel Art House emerged from a passion to
                            democratize access to contemporary art. We recognized that the traditional gallery model
                            excluded many talented artists and art lovers, so we built a digital platform that
                            transcends geographical boundaries.</p>

                        <h2 class="mb-4">Our Mission</h2>
                        <p class="mb-4">At Ziiriel, we believe art is more than just visual expression — it's a powerful
                            dialogue between the artist and the viewer. Our mission is to celebrate that dialogue by
                            making contemporary art accessible, discoverable, and collectible for everyone, everywhere.
                        </p>

                        <div class="mission-card p-4 mb-5" style="background: #CCD5D8; border-left: 4px solid #000;">
                            <h3 class="mb-3">"To create a world where art knows no boundaries"</h3>
                            <p class="mb-0">— Ziiriel Founding Principle</p>
                        </div>

                        <h2 class="mb-4">What We Offer</h2>
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <div class="p-3 h-100" style="background: #f8f9fa;">
                                    <h4>Curated Collections</h4>
                                    <p>Carefully selected works from emerging and established contemporary artists</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-3 h-100" style="background: #f8f9fa;">
                                    <h4>Artist Support</h4>
                                    <p>Fair representation and direct connection with collectors worldwide</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="p-3 h-100" style="background: #f8f9fa;">
                                    <h4>Digital Experiences</h4>
                                    <p>Immersive online exhibitions and virtual gallery tours</p>
                                </div>
                            </div>
                        </div>

                        <h2 class="mb-4">Our Values</h2>
                        <ul class="list-unstyled mb-5">
                            <li class="mb-3 d-flex">
                                <span class="mr-3" style="font-size: 1.5rem;">→</span>
                                <span><strong>Accessibility:</strong> Breaking down barriers to art appreciation</span>
                            </li>
                            <li class="mb-3 d-flex">
                                <span class="mr-3" style="font-size: 1.5rem;">→</span>
                                <span><strong>Innovation:</strong> Embracing digital possibilities for art
                                    presentation</span>
                            </li>
                            <li class="mb-3 d-flex">
                                <span class="mr-3" style="font-size: 1.5rem;">→</span>
                                <span><strong>Community:</strong> Building connections between artists and art
                                    lovers</span>
                            </li>
                            <li class="mb-3 d-flex">
                                <span class="mr-3" style="font-size: 1.5rem;">→</span>
                                <span><strong>Integrity:</strong> Transparent and fair practices for all</span>
                            </li>
                        </ul>

                        <div class="text-center mt-5">
                            <a href="/contact" class="btn btn-primary btn-lg mr-3">Contact Us</a>
                            <a href="/artists" class="btn btn-outline-dark btn-lg">Meet Our Artists</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid team-section" style="padding-top: 60px; padding-bottom: 60px; background: #f8f9fa;">
        <div class="container">
            <h2 class="text-center mb-5">Meet Our Team</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0">
                        <img src="https://via.placeholder.com/400x300" class="card-img-top" alt="Team Member">
                        <div class="card-body">
                            <h4 class="card-title">Alex Morgan</h4>
                            <p class="text-muted">Founder & Creative Director</p>
                            <p class="card-text">Contemporary art curator with 15 years experience in digital and
                                physical gallery spaces.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0">
                        <img src="https://via.placeholder.com/400x300" class="card-img-top" alt="Team Member">
                        <div class="card-body">
                            <h4 class="card-title">Samira Khan</h4>
                            <p class="text-muted">Head of Artist Relations</p>
                            <p class="card-text">Connects with artists worldwide to build our diverse collection.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0">
                        <img src="https://via.placeholder.com/400x300" class="card-img-top" alt="Team Member">
                        <div class="card-body">
                            <h4 class="card-title">Jordan Lee</h4>
                            <p class="text-muted">Technology Director</p>
                            <p class="card-text">Creates the digital experiences that make our gallery unique.</p>
                        </div>
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

    .mission-card {
        transition: transform 0.3s ease;
    }

    .mission-card:hover {
        transform: translateY(-5px);
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
</style>