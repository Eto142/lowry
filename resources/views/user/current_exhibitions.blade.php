@include("user.header")

<style>
  .exhibition-card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    margin-bottom: 24px;
    height: 100%;
  }

  .exhibition-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
  }

  .card-img-container {
    height: 240px;
    overflow: hidden;
    position: relative;
  }

  .card-img-top {
    height: 100%;
    width: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }

  .exhibition-card:hover .card-img-top {
    transform: scale(1.05);
  }

  .card-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    height: calc(100% - 240px);
  }

  .card-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: #333;
  }

  .card-text {
    color: #666;
    margin-bottom: 4px;
  }

  .price-tag {
    font-size: 1.1rem;
    font-weight: 700;
    color: #000;
    margin: 8px 0;
  }

  .exhibition-meta {
    display: flex;
    justify-content: space-between;
    margin-top: auto;
    align-items: center;
  }

  .exhibition-date {
    font-size: 0.9rem;
    color: #888;
  }

  .exhibition-status {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
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
    margin-top: 12px;
  }

  .view-btn:hover {
    background-color: #333;
    transform: translateY(-2px);
  }

  /* Mobile-specific adjustments */
  @media (max-width: 767.98px) {
    .card-img-container {
      height: 150px;
      /* Reduce height for mobile */
    }

    .card-body {
      padding: 15px;
      height: calc(100% - 150px);
    }

    .card-title {
      font-size: 1rem;
    }

    .price-tag {
      font-size: 0.9rem;
    }

    .view-btn {
      padding: 8px 12px;
      font-size: 0.8rem;
    }
  }
</style>

<div class="col-12 col-md-9">
  <div class="row">
    @foreach($exhibitions as $exhibition)
    <div class="col-6 col-sm-4 col-md-4 col-lg-3">
      <!-- Adjusted column classes -->
      <div class="card exhibition-card">
        <div class="card-img-container">
          <img src="{{ $exhibition->picture_url }}" class="card-img-top" alt="{{ $exhibition->title }}">
        </div>
        <div class="card-body">
          <h5 class="card-title">{{ $exhibition->title }}</h5>

          @if($exhibition->description)
          <p class="card-text text-truncate">{{ Str::limit($exhibition->description, 60) }}</p>
          @endif

          <div class="price-tag">${{ number_format($exhibition->amount_sold, 2) }}</div>

          <div class="exhibition-meta">
            <span class="exhibition-date">{{ $exhibition->date->format('M d, Y') }}</span>
            @if($exhibition->is_featured)
            <span class="exhibition-status status-featured">Featured</span>
            @else
            <span class="exhibition-status status-active">Active</span>
            @endif
          </div>

          <a href="{{ route('user.exhibition.show', $exhibition->id) }}" class="btn view-btn">View
            Exhibition</a>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>

@include("user.footer")