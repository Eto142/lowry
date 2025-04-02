<!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Ziirielcontemporaryartgallery Account</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     <style>
         body {
             background-color: #ffffff;
         }
         .sidebar-button {
             width: 100%;
             text-align: left;
             font-weight: bold;
         }
         .profile-container {
             background: white;
             padding: 20px;
             border-radius: 5px;
         }
         .navbar {
             padding: 15px;
         }
         .border-bottom {
             border-bottom: 2px solid black !important;
         }
         .btn-dark {
             background-color: black;
             color: white;
         }
         .btn-outline-secondary {
             border: 1px solid black;
             color: black;
         }
     </style>
 </head>
 <body>

  <nav class="navbar navbar-light bg-white border-bottom">
    <div class="container d-flex justify-content-between">
        <a class="navbar-brand fw-bold fs-3" href="#"><img class="sticky-logo" src="{{asset('images/logo.png')}}" width="100" alt="Ziirielcontemporaryartgallery"></a>
        <div class="d-flex align-items-center">
            <span class="me-3 fw-bold">{{Auth::user()->first_name}} {{Auth::user()->last_name}}</span>
            <img src="user-icon.png" alt="User" width="30">
        </div>
    </div>
</nav>

    <div class="container">
      <form>
      <div class="row mt-5">
        <h5 class="text-center mb-4 text-uppercase fw-bolder">Add a new exhibition</h5>
        <div class="col-lg-10 col-md-12  mx-auto px-3">
          <div class="row">
            <div class="col-6">
              <div class="mb-3">
                <label for="picture" class="form-label fw-bold">Picture</label>
                <input type="file" id="picture" name="picture" class="form-control">
              </div>
              <div class="mb-3">
                <label for="title" class="form-label fw-bold">Title</label>
                <input type="text" class="form-control" id="title" placeholder="Enter Title">
              </div>
              <div class="mb-3">
                <label for="description" class="form-label fw-bold">Description</label>
                <input type="text" id="Description" name="description" class="form-control" placeholder="Enter Description">
              </div>
              <div class="mb-3">
                <label for="amount" class="form-label fw-bold">Amount</label>
                <input type="number" id="Description" name="amount" class="form-control" placeholder="Enter Amount">
              </div>
            </div>
            <div class="col-6">
              <div class="mb-3">
                <label for="sellerName" class="form-label fw-bold">Seller's Name</label>
                <input type="text" id="sellerName" name="description" class="form-control" placeholder="Enter Description">
              </div>
              <div class="mb-3">
                <label for="sellerEmail" class="form-label fw-bold">Seller's Name</label>
                <input type="email" id="sellerEmail" name="seller_email" class="form-control" placeholder="Enter seller email">
              </div>
              <div class="mb-3">
                <label for="sellerPhone" class="form-label fw-bold">Seller's Phone</label>
                <input type="email" id="sellerPhone" name="seller_phone" class="form-control" placeholder="Enter seller email">
              </div>
              <div class="mb-3">
                <label for="sellerAddress" class="form-label fw-bold">Seller's Address</label>
                <input type="email" id="sellerPhone" name="seller_phone" class="form-control" placeholder="Enter seller's address">
              </div>
  
            </div>

          </div>
          <div>
            <button type="submit" class="btn btn-dark w-100">Add exhibition</button>
          </div>
          
        </div> 
            </div>   
      </div>
    </form>
    </div>
 </body>
 </html>

     