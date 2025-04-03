@include("user.header")

<style>
  .card-img-top {
    height: 200px;
    object-fit: cover;
  }

  .card {
    transition: transform 0.3s ease;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
  }

  .buy-btn {
    background-color: #000;
    color: #fff;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    transition: all 0.3s ease;
  }

  .buy-btn:hover {
    background-color: #333;
  }
</style>

<div class="col-12 col-md-9">
  <div class="row">
    @foreach($exhibitions as $exhibition)
    <div class="col-12 col-md-4 mb-4">
      <div class="card h-100">
        <img src="{{ asset($exhibition->picture) }}" class="card-img-top" alt="{{ $exhibition->title }}">
        <div class="card-body">
          <h5 class="card-title">{{ $exhibition->title }}</h5>
          <p class="card-text">${{ number_format($exhibition->amount_sold, 2) }}</p>
          <p class="card-text"><small class="text-muted">{{ $exhibition->date->format('Y') }}</small></p>
          <a href="{{ route('user.exhibition.show', $exhibition->id) }}" class="btn buy-btn">View Details</a>
        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>

@include("user.footer")