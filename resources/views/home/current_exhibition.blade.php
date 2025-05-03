@include('home.header')
<main id="content">

    <div class="container-fluid infoHeaderWrapper type-page align-"
        style="padding-top: 30px; padding-bottom: 30px; background: #fff;">
        <div class="container">
            <div class="infoHeaderContent">
                <div class="inner">
                    <h1>current Exhibitions</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid desc2Wrapper variant- align-"
        style="padding-top: 30px; padding-bottom: 30px; background: #CCD5D8;">
        <div class="container">
            <div class="richtext">
                <p>Discover what's on our Exhibition Halls. These current exhibitions showcase the latest
                    in contemporary art and immersive experiences.</p>
                <p style="text-align: center;"><strong>Exhibition Halls opening hours</strong></p>
                <p style="text-align: center;">Tuesday – Friday | 11:00–17:00<br>Saturday - Sunday | 10:00 –
                    17:00<br>Monday | closed</p>
                <p>Many of our current exhibitions are available for preview booking. Reserve your spot early to ensure
                    you don't miss these limited engagements.</p>
            </div>
        </div>
    </div>

    @auth
    <div class="container-fluid listWrapper theme" style="padding-top: 0px; padding-bottom: 50px; background: #fff;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="exhibitions-grid">
                        @foreach($futureExhibitions as $exhibition)
                        <div class="exhibition-card-container">
                            <div data-entry-id="{{ $exhibition->id }}" class="eventCard">
                                <div class="listItemWrapper">
                                    <div class="inner">
                                        <div class="descMetaContainer">
                                            <div class="desc">
                                                <h2 class="title">{{ $exhibition->title }}</h2>
                                                <div class="subtitle">{{ $exhibition->theme }}</div>
                                                <div class="card-text">
                                                    <strong>Mediums:</strong> {{ $exhibition->mediums }}
                                                </div>
                                                <div class="card-text">
                                                    <strong>Objective:</strong> {{ Str::limit($exhibition->objective,
                                                    80) }}
                                                </div>
                                                @if(is_array($exhibition->sections) && count($exhibition->sections))
                                                <div class="card-text">
                                                    <strong>Sections:</strong>
                                                    <ul class="exhibition-sections">
                                                        @foreach($exhibition->sections as $section)
                                                        <li>{{ $section }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                                <div class="top-date">
                                                    <span class="start">Opening: {{
                                                        $exhibition->exhibition_date->format('D d M Y') ?? 'Coming soon'
                                                        }}
                                                        <span class="time">10:00AM</span>
                                                    </span>
                                                </div>
                                                <div class="price-tag">Budget: {{ $exhibition->formatted_budget }}</div>
                                                <div class="budget-note">(Shared between exhibitor and artist)</div>
                                            </div>
                                            <div class="meta">
                                                <div class="meta-group button">
                                                    <a href="{{ route('user.current.exhibition') }}"
                                                        class="btn btn-active view-exhibition-btn">
                                                        See More Details
                                                    </a>

                                                    @if($exhibition->can_prebook)
                                                    <a href="/book/{{ $exhibition->id }}"
                                                        class="btn btn-secondary">Pre-book Now</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exhibition Modal -->
    <div class="modal fade" id="exhibitionModal" tabindex="-1" aria-labelledby="exhibitionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exhibitionModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <p><strong>Theme:</strong> <span id="modalTheme"></span></p>
                        <p><strong>Date:</strong> <span id="modalDate"></span></p>
                        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                        <p><strong>Budget:</strong> <span id="modalBudget"></span> (shared between exhibitor and artist)
                        </p>
                    </div>

                    <div class="mb-3">
                        <h6>Mediums</h6>
                        <p id="modalMediums"></p>
                    </div>

                    <div class="mb-3">
                        <h6>Objective</h6>
                        <p id="modalObjective"></p>
                    </div>

                    <div class="mb-3">
                        <h6>Sections</h6>
                        <ul id="modalSections" class="exhibition-sections"></ul>
                    </div>

                    <div class="modal-contact-info">
                        <h6 class="modal-section-title"><i class="fas fa-info-circle contact-icon"></i>Contact for More
                            Information</h6>
                        <p>For more inquiries about this exhibition, please contact the exhibitor/ art collector that is
                            contracted to this exhibition. The exhibitor will provide details of the artworks required
                            to be
                            exhibited and the participating artist will create the artworks according to the
                            descriptions
                            given by the exhibitor/ art collector and these arts created will be featured in this
                            exhibition.
                        </p>
                        <button class="btn btn-primary w-100 mt-2">
                            <i class="fas fa-envelope me-2"></i>Contact Organizer
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="container-fluid desc2Wrapper variant- align-"
        style="padding-top: 30px; padding-bottom: 50px; background: #fff;">
        <div class="container">
            <div class="richtext">
                <p>To view our current exhibitions and access pre-booking options, please <a href="/login">log in</a>
                    or <a href="/register">create an account</a>.</p>
            </div>
        </div>
    </div>
    @endauth

</main>
@include('home.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
    var exhibitionModal = document.getElementById('exhibitionModal');
    if (exhibitionModal) {
        exhibitionModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            
            // Extract info from data-* attributes
            var title = button.getAttribute('data-title');
            var theme = button.getAttribute('data-theme');
            var mediums = button.getAttribute('data-mediums');
            var objective = button.getAttribute('data-objective');
            var sections = JSON.parse(button.getAttribute('data-sections'));
            var budget = button.getAttribute('data-budget');
            var date = button.getAttribute('data-date');
            var status = button.getAttribute('data-status');
            
            // Update the modal's content
            var modalTitle = exhibitionModal.querySelector('.modal-title');
            var modalTheme = exhibitionModal.querySelector('#modalTheme');
            var modalMediums = exhibitionModal.querySelector('#modalMediums');
            var modalObjective = exhibitionModal.querySelector('#modalObjective');
            var modalSections = exhibitionModal.querySelector('#modalSections');
            var modalBudget = exhibitionModal.querySelector('#modalBudget');
            var modalDate = exhibitionModal.querySelector('#modalDate');
            var modalStatus = exhibitionModal.querySelector('#modalStatus');
            
            modalTitle.textContent = title;
            modalTheme.textContent = theme;
            modalMediums.textContent = mediums;
            modalObjective.textContent = objective;
            modalBudget.textContent = budget;
            modalDate.textContent = date;
            modalStatus.textContent = status;
            
            // Clear previous sections
            modalSections.innerHTML = '';
            
            // Add new sections
            sections.forEach(function(section) {
                var li = document.createElement('li');
                li.textContent = section;
                modalSections.appendChild(li);
            });
        });
    }
});
</script>