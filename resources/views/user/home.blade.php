@include('user.header')




            <div class="col-md-6">
                <div class="profile-container p-4 shadow rounded bg-light">
                    <div class="d-flex justify-content-between mb-3">
                        <a href="{{route('user.future.exhibition')}}" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Future Exhibition
                        </a>
                        <a href="{{route('user.exhibitions.manage')}}" class="btn btn-secondary">
                            <i class="bi bi-eye"></i> Manage My Exhibition
                        </a>

                    </div>
                    <h5 class="fw-bold text-dark">Welcome, {{Auth::user()->first_name}} {{Auth::user()->last_name}}
                        <span class="text-primary">&#x25B6;</span>
                        <!-- Recommended option 1: Form-based logout (most secure) -->
                        <form method="POST" action="{{ route('user.logout') }}" class="d-inline">
                            @csrf
                            <button type="submit"
                                class="btn btn-link text-decoration-none text-danger fw-bold p-0 border-0 bg-transparent">
                                Sign Out
                            </button>
                        </form>
                    </h5>
                    <hr>
                    <h6 class="fw-bold text-secondary">My Profile</h6>
                    <p><strong>Full Name:</strong> {{Auth::user()->first_name}} {{Auth::user()->last_name}}</p>
                    <p><strong>Email:</strong> {{Auth::user()->email}}</p>
                    <p><strong>Home Phone:</strong> {{Auth::user()->phone_number}}</p>
                    <p><strong>Mobile:</strong> -</p>
                    <p><strong>Fax:</strong> -</p>
                    <p><strong>Address:</strong> -</p>
                    <div class="d-grid gap-2 d-md-flex">
                        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#contactModal">
                            <i class="bi bi-envelope"></i> Contact Preferences
                        </button>
                        <button class="btn btn-outline-secondary" data-bs-toggle="modal"
                            data-bs-target="#passwordModal">
                            <i class="bi bi-key"></i> Change Password
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="profile-container p-4 shadow rounded bg-light text-center">
                    <h6 class="fw-bold text-secondary"><i class="bi bi-calendar"></i> My Next Event</h6>
                    <p class="text-muted">No tickets found</p>
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
                        <form id="editCustomerForm" action="{{route('profile.update')}}" method="POST">
                            @csrf
                            @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                            @endif
                            <div class="mb-3">
                                <label for="fullName" class="form-label">First Name</label>
                                <input type="text" id="fullName" name="first_name" class="form-control"
                                    placeholder="Enter first name">
                            </div>

                            <div class="mb-3">
                                <label for="fullName" class="form-label">Last Name</label>
                                <input type="text" id="fullName" name="last_name" class="form-control"
                                    placeholder="Enter last name">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Enter email" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="phoneNumber" class="form-label">Home Phone</label>
                                <input type="text" id="phoneNumber" name="phone_number" class="form-control"
                                    placeholder="Enter phone number">
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" id="country" name="country" class="form-control"
                                    placeholder="Enter country">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" id="address" name="address" class="form-control"
                                    placeholder="Enter address">
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
        <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel"
            aria-hidden="true">
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
                                <input type="password" id="oldPassword" name="old_password" class="form-control"
                                    placeholder="Enter old password">
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">New Password</label>
                                <input type="password" id="newPassword" name="new_password" class="form-control"
                                    placeholder="Enter new password">
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                <input type="password" id="confirmPassword" name="confirm_password" class="form-control"
                                    placeholder="Re-enter new password">
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