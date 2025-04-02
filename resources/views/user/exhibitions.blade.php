@include("user.header")

<style>
  .card-img-top {
    height: 200px;
    object-fit: cover;
  }
  .ttf .card-body{
    box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1); /* Light shadow */
    border-right: 1px solid #e9ecef;
    border-left: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
  }

</style>



<div class="col-12 col-md-9 ttf">
  <div class="row">
      <div class="col-12 col-md-4 mb-4">
        <div class="card h-50">
          <img src="https://placehold.co/300x400" class="card-img-top" alt="Abstract Harmony artwork">
          <div class="card-body">
            <h5 class="card-title">Abstract Harmony</h5>
            <p class="card-text">Artist: Elena Rivera</p>
            <p class="card-text"><small class="text-muted">2021</small></p>
          </div>
        </div>
      </div>
      
      <div class="col-12 col-md-4 mb-4">
        <div class="card h-50">
          <img src="https://placehold.co/300x400" class="card-img-top" alt="Urban Reflections artwork">
          <div class="card-body">
            <h5 class="card-title">Urban Reflections</h5>
            <p class="card-text">Artist: Marcus Chen</p>
            <p class="card-text"><small class="text-muted">2019</small></p>
          </div>
        </div>
      </div>
      
      <div class="col-12 col-md-4 mb-4">
        <div class="card h-50">
          <img src="https://placehold.co/300x400" class="card-img-top" alt="Serene Landscape artwork">
          <div class="card-body">
            <h5 class="card-title">Serene Landscape</h5>
            <p class="card-text">Artist: Sophia Johnson</p>
            <p class="card-text"><small class="text-muted">2022</small></p>
          </div>
        </div>
      </div>
    </div>
  
  
</div>
@include("user.footer")
