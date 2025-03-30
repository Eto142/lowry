<!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Lowry Account</title>
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
             <a class="navbar-brand fw-bold fs-3" href="#">LOWRY</a>
             <div class="d-flex align-items-center">
                 <span class="me-3 fw-bold">Egod Egod</span>
                 <img src="user-icon.png" alt="User" width="30">
             </div>
         </div>
     </nav>
 
     <div class="container mt-4">
         <div class="row">
             <div class="col-md-3">
                 <button class="btn btn-dark sidebar-button mb-2">Welcome ></button>
                 <button class="btn btn-light sidebar-button mb-2">Donation pledges</button>
                 <button class="btn btn-light sidebar-button mb-2">Credit Cards</button>
                 <button class="btn btn-light sidebar-button mb-2">History</button>
             </div>
             <div class="col-md-6">
                 <div class="profile-container">
                     <h5 class="fw-bold">Welcome egod egod <span class="text-primary">&#x25B6;</span> <a href="#" class="text-decoration-none">Sign Out</a></h5>
                     <h6 class="fw-bold">My Profile</h6>
                     <p><strong>Full Name:</strong> {{Auth::user()->first_name}} {{Auth::user()->last_name}}</p>
                     <p><strong>Email:</strong> {{Auth::user()->email}}</p>
                     <p><strong>Home phone:</strong> {{Auth::user()->phone_number}}</p>
                     <p><strong>Mobile:</strong> -</p>
                     <p><strong>Fax:</strong> -</p>
                     <p><strong>Address:</strong> -</p>
                     <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                     <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#contactModal">Contact Preferences</button>
                     <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#passwordModal">Change Password</button>
                 </div>
             </div>
             <div class="col-md-3">
                 <div class="profile-container">
                     <h6 class="fw-bold"><span class="bi bi-calendar"></span> My Next Event</h6>
                     <p>No tickets found</p>
                 </div>
             </div>
         </div>
     </div>
 
    <!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Customer Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    @csrf
                    <div class="mb-3">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" id="fullName" name="first_name" class="form-control" placeholder="Enter full name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" class="form-control" placeholder="Enter email" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="phoneNumber" class="form-label">Home Phone</label>
                        <input type="text" id="phoneNumber" name="phone_number" class="form-control" placeholder="Enter phone number">
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label">Country</label>
                        <input type="text" id="country" name="country" class="form-control" placeholder="Enter country">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" id="address" name="address" class="form-control" placeholder="Enter address">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>

     <!-- Contact Preferences Modal -->
     <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
         <div class="modal-dialog">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="contactModalLabel">Contact Preferences</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                 <div class="modal-body">
                     <p>Manage your contact preferences here.</p>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                 </div>
             </div>
         </div>
     </div>
 
    <!-- Change Password Modal -->
<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted"><strong>Login Name:</strong> {{ Auth::user()->first_name }}</p>
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="oldPassword" class="form-label">Old Password</label>
                        <input type="password" id="oldPassword" name="old_password" class="form-control" placeholder="Enter old password">
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" id="newPassword" name="new_password" class="form-control" placeholder="Enter new password">
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                        <input type="password" id="confirmPassword" name="confirm_password" class="form-control" placeholder="Re-enter new password">
                    </div>
             
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
        </div>
    </div>
</div>

         </div>
     </div>
 </body>
 </html>