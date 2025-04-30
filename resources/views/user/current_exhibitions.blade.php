@include("user.header")

<style>
  /* Improved Card Styling */
  .exhibition-card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    margin-bottom: 24px;
    height: 100%;
    background: #fff;
    border: 1px solid #eee;
  }

  .exhibition-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
  }

  .card-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
  }

  .card-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 12px;
    color: #222;
    line-height: 1.3;
  }

  .card-text {
    color: #555;
    margin-bottom: 8px;
    font-size: 0.95rem;
    line-height: 1.5;
  }

  .card-text strong {
    color: #333;
    font-weight: 600;
  }

  .price-tag {
    font-size: 1.1rem;
    font-weight: 700;
    color: #000;
    margin: 12px 0 4px;
    background: #f8f9fa;
    padding: 8px 12px;
    border-radius: 6px;
    display: inline-block;
  }

  .exhibition-meta {
    display: flex;
    justify-content: space-between;
    margin-top: 16px;
    align-items: center;
    padding-top: 12px;
    border-top: 1px solid #eee;
  }

  .exhibition-date {
    font-size: 0.85rem;
    color: #666;
  }

  .exhibition-status {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
  }

  .status-active {
    background-color: #e6f7ee;
    color: #00a859;
  }

  .status-featured {
    background-color: #fff8e6;
    color: #ff9500;
  }

  .view-btn {
    background-color: #000;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    font-weight: 500;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    width: 100%;
    margin-top: 16px;
    font-size: 0.95rem;
  }

  .view-btn:hover {
    background-color: #333;
    transform: translateY(-2px);
  }

  /* Exhibition Sections List */
  .exhibition-sections {
    margin: 8px 0 0 0;
    padding-left: 0;
  }

  .exhibition-sections li {
    position: relative;
    margin-bottom: 4px;
    list-style-type: none;
    padding-left: 18px;
    font-size: 0.9rem;
    color: #555;
  }

  .exhibition-sections li:before {
    content: "•";
    position: absolute;
    left: 0;
    color: #666;
    font-weight: bold;
  }

  /* Responsive Grid Layout */
  .exhibitions-grid {
    display: grid;
    gap: 20px;
  }

  /* Mobile first - single column */
  .exhibitions-grid {
    grid-template-columns: 1fr;
  }

  /* Tablet - two columns */
  @media (min-width: 768px) {
    .exhibitions-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }

  /* Large desktop - three columns */
  @media (min-width: 1200px) {
    .exhibitions-grid {
      grid-template-columns: repeat(3, 1fr);
    }
  }

  /* Modal Styles */
  .modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  }

  .modal-header {
    border-bottom: none;
    padding-bottom: 0;
  }

  .modal-title {
    font-weight: 700;
    font-size: 1.5rem;
  }

  .modal-body {
    padding-top: 0;
  }

  .modal-contact-info {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 1.25rem;
    margin-top: 1.5rem;
  }

  .contact-icon {
    font-size: 1.25rem;
    margin-right: 0.5rem;
    color: #0d6efd;
  }

  /* Budget note */
  .budget-note {
    font-size: 0.8rem;
    color: #777;
    margin-top: -8px;
    margin-bottom: 12px;
  }
</style>

<div class="col-12 col-md-9">
  <div class="exhibitions-grid">
    @foreach($exhibitions as $exhibition)
    <div class="exhibition-card-container">
      <div class="card exhibition-card">
        <div class="card-body">
          <h5 class="card-title">{{ $exhibition->title }}</h5>

          <div class="card-text">
            <strong>Theme:</strong> {{ $exhibition->theme }}
          </div>

          <div class="card-text">
            <strong>Mediums:</strong> {{ $exhibition->mediums }}
          </div>

          <div class="card-text">
            <strong>Objective:</strong> {{ Str::limit($exhibition->objective, 80) }}
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

          <div class="price-tag">Budget: {{ $exhibition->formatted_budget }}</div>
          <div class="budget-note">(Shared between exhibitor and artist)</div>

          <div class="exhibition-meta">
            <span class="exhibition-date">{{ $exhibition->formatted_date }}</span>
            @if($exhibition->is_featured)
            <span class="exhibition-status status-featured">Featured</span>
            @else
            <span class="exhibition-status status-active">Active</span>
            @endif
          </div>

          <button class="btn view-btn view-exhibition-btn" data-bs-toggle="modal" data-bs-target="#exhibitionModal"
            data-title="{{ $exhibition->title }}" data-theme="{{ $exhibition->theme }}"
            data-mediums="{{ $exhibition->mediums }}" data-objective="{{ $exhibition->objective }}"
            data-sections="{{ json_encode($exhibition->sections ?? []) }}"
            data-budget="{{ $exhibition->formatted_budget }}"
            data-date="{{ $exhibition->exhibition_date->format('F j, Y') }}"
            data-status="{{ $exhibition->is_featured ? 'Featured' : 'Active' }}">
            See More Details
          </button>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>

<!-- Exhibition Modal -->
<div class="modal fade" id="exhibitionModal" tabindex="-1" aria-labelledby="exhibitionModalLabel" aria-hidden="true">
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
          <p><strong>Budget:</strong> <span id="modalBudget"></span> (shared between exhibitor and artist)</p>
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
          <p>For inquiries about this exhibition, please contact the organizer directly using the button
            below.</p>
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

@include("user.footer")

<script>
  document.addEventListener('DOMContentLoaded', function() {
        var exhibitionModal = document.getElementById('exhibitionModal');
        
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
            document.getElementById('exhibitionModalLabel').textContent = title;
            document.getElementById('modalTheme').textContent = theme;
            document.getElementById('modalMediums').textContent = mediums;
            document.getElementById('modalObjective').textContent = objective;
            document.getElementById('modalBudget').textContent = budget;
            document.getElementById('modalDate').textContent = date;
            document.getElementById('modalStatus').textContent = status;
            
            // Clear and populate sections
            var sectionsList = document.getElementById('modalSections');
            sectionsList.innerHTML = '';
            
            sections.forEach(function(section) {
                var li = document.createElement('li');
                li.textContent = section;
                sectionsList.appendChild(li);
            });
        });
    });
</script>