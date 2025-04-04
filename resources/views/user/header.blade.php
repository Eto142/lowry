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
            <a class="navbar-brand fw-bold fs-3" href="#">
                <img class="sticky-logo" src="{{asset('images/logo.png')}}" width="100" alt="Ziirielcontemporaryartgallery">
            </a>
            <div class="d-flex align-items-center">
                <span class="me-3 fw-bold">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
@if (Auth::user()->kyc_status == 0)
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kycModal">KYC Not verified</button>
@else
<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#kycModal">KYC verified</button>
@endif

            </div>
        </div>
    </nav>
    
    <!-- KYC Modal -->
    <div class="modal fade" id="kycModal" tabindex="-1" aria-labelledby="kycModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kycModalLabel">KYC Verification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('kyc.submit')}}" method="POST"  enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="kycAddress" class="form-label">ID card Type</label>
                            <input type="text" class="form-control" name="id_type" id="kycAddress" required>
                        </div>
                        <div class="mb-3">
                            <label for="kycDocument" class="form-label">Upload Front of ID Document</label>
                            <input type="file" class="form-control" name="front_id" id="kycDocument" required>
                        </div>
                        <div class="mb-3">
                            <label for="kycDocument" class="form-label">Upload Back of ID Document</label>
                            <input type="file" class="form-control" name="back_id" id="kycDocument" required>
                        </div>
                        
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
 
     <div class="container mt-4">
         <div class="row">
             <div class="col-md-3">
                 <button class="btn btn-dark sidebar-button mb-2">Welcome ></button>
                 <button class="btn btn-light sidebar-button mb-2"><a href="/">Home</a></button>
                 <button class="btn btn-light sidebar-button mb-2"><a href="{{route('user.deposit') }}">Deposit</a></button>
                 <button class="btn btn-light sidebar-button mb-2"><a href="{{route('user.withdrawal') }}">Withdrawal</a></button>
                 <button class="btn btn-light sidebar-button mb-2"><a href="{{route('user.create.exhibition') }}">Add Exhibition</a></button>
                 <button class="btn btn-light sidebar-button mb-2"><a href="">History</a></button>
             </div>

        

             
             