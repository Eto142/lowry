@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            @if(session('message'))
            <div class="alert alert-success mb-2">{{session('message')}}</div>
            @endif
            @if(session('message'))
            <div class="alert alert-success mb-2">{{session('message')}}</div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            <div>
            </div>
            <div>
            </div> <!-- Beginning of  Dashboard Stats  -->
            <div class="row">
                <div class="col-md-12">
                    <div class="p-3 card bg-light">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 ">
                                    <h1 class="d-inline text-primary">{{$user->name}}</h1>
                                    <span></span>
                                    <div class="d-inline">
                                        <div class="float-right btn-group">
                                            <a class="btn btn-primary btn-sm" href="{{route('admin.users.index')}}"> <i
                                                    class="fa fa-arrow-left"></i> back</a> &nbsp;
                                            <button type="button" class="btn btn-secondary dropdown-toggle btn-sm"
                                                data-toggle="dropdown" data-display="static" aria-haspopup="true"
                                                aria-expanded="false">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-lg-right">
                                                <a class="dropdown-item" href="">Login Activity</a>
                                                <a href="#" data-toggle="modal" data-target="#totalBalanceModal"
                                                    class="dropdown-item">Credit/Debit Total Balance</a>
                                                <a href="{{ route('admin.users.deposits.index', ['user' => $user->id]) }}"
                                                    class="dropdown-item">Edit Deposit</a>
                                                <a href="{{ route('admin.users.withdrawals.index', ['user' => $user->id]) }}"
                                                    class="dropdown-item">Edit Withdrawal</a>

                                                <a href="#" data-toggle="modal" data-target="#sendmailtooneuserModal"
                                                    class="dropdown-item">Send Email</a>
                                                <a href="#" data-toggle="modal" data-target="#switchuserModal"
                                                    class="dropdown-item text-success">Gain Access</a>
                                                <a href="#" data-toggle="modal" data-target="#deleteModal"
                                                    class="dropdown-item text-danger">Delete {{$user->name}}</a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 mt-4 border rounded row text-dark">
                                <div class="col-md-3">
                                    <h5 class="text-bold">Total Balance</h5>
                                    <p>{{ config('currencies.' . $user->currency, '$')
                                        }}{{number_format($total_balance, 2, '.', ',')}}</p>
                                </div>


                                <div class="col-md-3">
                                    <h5>Total Deposit</h5>
                                    <p>{{ config('currencies.' . $user->currency, '$')
                                        }}{{number_format($successful_deposits_sum, 2, '.', ',')}}</p>
                                </div>


                                <div class="col-md-3">
                                    <h5>Manage User</h5>

                                    <a class="btn btn-sm btn-primary d-inline"
                                        href="{{ route('admin.users.edit', ['user' => $user->id]) }}">Edit
                                        User</a>


                                </div>
                                <div class="col-md-3">
                                    <h5>ID Verification</h5>

                                    <a class="btn btn-sm btn-primary d-inline"
                                        href="{{ route('admin.users.identity-verifications.index', ['user' => $user->id]) }}">View</a>


                                </div>



                            </div>
                            <div class="mt-3 row text-dark">
                                <div class="col-md-12">
                                    <h5>USER INFORMATION</h5>
                                </div>
                            </div>
                            @php
                            $fields = [
                            'first_name' => 'First Name',
                            'last_name' => 'Last Name',
                            'email' => 'Email Address',
                            'phone_number' => 'Mobile Number',
                            'country' => 'Country',
                            'email_verification' => 'Email Verification',
                            'id_verification' => 'ID Verification',
                            ];
                            @endphp

                            @foreach($fields as $key => $label)
                            <div class="p-3 border row text-dark">
                                <div class="col-md-4 border-right">
                                    <h5>{{ $label }}</h5>
                                </div>
                                <div class="col-md-8">
                                    @if($key === 'profile_photo')
                                    <span id="display-{{ $key }}">
                                        <img src="{{ asset($user->$key) }}" alt="Profile Photo"
                                            style="width:100px; height:auto;">
                                    </span>
                                    <input type="file" class="form-control d-none" id="input-{{ $key }}"
                                        accept="image/*">
                                    @elseif($key === 'email_verification')
                                    <span id="display-{{ $key }}">
                                        {{ $user->$key == 1 ? 'Verified' : 'Not Verified' }}
                                    </span>
                                    <select class="form-control d-none" id="input-{{ $key }}">
                                        <option value="1" {{ $user->$key == 1 ? 'selected' : '' }}>Verified</option>
                                        <option value="0" {{ $user->$key == 0 ? 'selected' : '' }}>Not Verified</option>
                                    </select>
                                    @elseif($key === 'id_verification')
                                    <span id="display-{{ $key }}">
                                        {{ $user->$key == 1 ? 'Verified' : 'Not Verified' }}
                                    </span>
                                    <select class="form-control d-none" id="input-{{ $key }}">
                                        <option value="1" {{ $user->$key == 1 ? 'selected' : '' }}>Verified</option>
                                        <option value="0" {{ $user->$key == 0 ? 'selected' : '' }}>Not Verified</option>
                                    </select>
                                    @elseif($key === 'address_verification')
                                    <span id="display-{{ $key }}">
                                        {{ $user->$key == 1 ? 'Verified' : 'Not Verified' }}
                                    </span>
                                    <select class="form-control d-none" id="input-{{ $key }}">
                                        <option value="1" {{ $user->$key == 1 ? 'selected' : '' }}>Verified</option>
                                        <option value="0" {{ $user->$key == 0 ? 'selected' : '' }}>Not Verified</option>
                                    </select>
                                    @elseif($key === 'password' || $key === 'plain')
                                    <span id="display-{{ $key }}">********</span> <!-- Masked for security -->
                                    <input type="password" class="form-control d-none" id="input-{{ $key }}" value="">
                                    @else
                                    <span id="display-{{ $key }}">{{ $user->$key }}</span>
                                    <input type="text" class="form-control d-none" id="input-{{ $key }}"
                                        value="{{ $user->$key }}">
                                    @endif
                                    <button class="btn btn-sm btn-primary edit-btn"
                                        data-field="{{ $key }}">Edit</button>
                                    <button class="btn btn-sm btn-success save-btn d-none"
                                        data-field="{{ $key }}">Save</button>
                                </div>
                            </div>
                            @endforeach


                            <div class="p-3 border row text-dark">
                                <div class="col-md-4 border-right">
                                    <h5>Registered</h5>
                                </div>
                                <div class="col-md-8">
                                    <h5>{{ \Carbon\Carbon::parse($user->created_at)->format('D, M j, Y g:i A') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- send a single user email Modal-->
    <div id="sendmailtooneuserModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h4 class="modal-title text-dark">Send Email</h4>
                    <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body bg-light">
                    <p class="text-dark">This message will be sent to {{$user->name}}</p>
                    <form style="padding:3px;" role="form" method="post" action="{{ route('admin.send.mail')}}">

                        @csrf
                        <input type="hidden" name="email" value="{{$user->email}}">
                        <div class=" form-group">
                            <input type="text" name="subject" class="form-control bg-light text-dark"
                                placeholder="Subject" required>
                        </div>
                        <div class=" form-group">
                            <textarea placeholder="Type your message here" class="form-control bg-light text-dark"
                                name="message" row="8" placeholder="Type your message here" required></textarea>
                        </div>
                        <div class=" form-group">

                            <input type="submit" class="btn btn-light" value="Send">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit user Modal -->
    <div id="edituser" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h4 class="modal-title text-dark">Edit {{$user->name}} details</h4>
                    <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body bg-light">
                    <form role="form" method="post" action="{{ route('admin.updateUser', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="text-dark">First Name</label>
                            <input class="form-control bg-light text-dark" id="input1" value="{{$user->name}}"
                                type="text" name="username" required>
                        </div>
                        <div class="form-group">
                            <label class="text-dark">Last Name</label>
                            <input class="form-control bg-light text-dark" value="{{$user->last_name}}" type="text"
                                name="name" required>
                        </div>
                        <div class="form-group">
                            <label class="text-dark">Email</label>
                            <input class="form-control bg-light text-dark" value="{{$user->email}}" type="text"
                                name="email" required>
                        </div>
                        <div class="form-group">
                            <label class="text-dark">Phone Number</label>
                            <input class="form-control bg-light text-dark" value="{{$user->phone}}" type="text"
                                name="phone" required>
                        </div>
                        <div class="form-group">
                            <label class="text-dark">Country</label>
                            <input class="form-control bg-light text-dark" value="{{$user->country}}" type="text"
                                name="country">
                        </div>

                        <div class="form-group">
                            <input type="submit" class="btn btn-light" value="Update">
                        </div>
                    </form>
                </div>
                <script>
                    $('#input1').on('keypress', function(e) {
                    return e.which !== 32; // Disallow spaces in username
                });
                </script>
            </div>
        </div>
    </div>
    <!-- /Edit user Modal -->


    <!-- Reset user password Modal -->
    <div id="resetpswdModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h4 class="modal-title text-dark">Reset Password</strong></h4>
                    <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body bg-light">
                    <p class="text-dark">Are you sure you want to reset password for {{$user->name}} to <span
                            class="text-primary font-weight-bolder">user01236</span></p>
                    <a class="btn btn-light" href="{{ route('reset.password', $user->id) }}">Reset Now</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /Reset user password Modal -->

    <!-- Switch useraccount Modal -->
    <div id="switchuserModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h4 class="modal-title text-dark">You are about to login as {{$user->name}}.</strong></h4>
                    <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body bg-light">
                    <a class="btn btn-success" href="{{ route('users.impersonate', $user->id) }}">Proceed</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /Switch user account Modal -->


    <!-- Delete user Modal -->
    <div id="deleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-light">

                    <h4 class="modal-title text-dark">Delete User</strong></h4>
                    <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body bg-light p-3">
                    <p class="text-dark">Are you sure you want to delete {{$user->name}}
                        Account? Everything
                        associated
                        with this account will be loss.</p>
                    <a class="btn btn-danger" href="{{ route('delete.user', $user->id) }}">Yes i'm sure</a>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete user Modal -->

    <!-- total Balance Modal -->
    <div id="totalBalanceModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h4 class="modal-title text-dark">Update {{$user->first_name}}'s Holding Balance</h4>
                    <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body bg-light">
                    <form action="{{ route('update.total.balance') }}" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <div class="form-group">
                            <label class="text-dark">Amount</label>
                            <input class="form-control bg-light text-dark" type="number" name="amount" required>
                        </div>
                        <div class="form-group">
                            <label class="text-dark">Type</label>
                            <select class="form-control bg-light text-dark" name="type" required>
                                <option value="credit">Credit</option>
                                <option value="debit">Debit</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="text-dark">Description</label>
                            <textarea class="form-control bg-light text-dark" name="description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" value="Submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".edit-btn").forEach(button => {
                button.addEventListener("click", function() {
                    let field = this.dataset.field;
                    document.getElementById(`display-${field}`).classList.add("d-none");
                    document.getElementById(`input-${field}`).classList.remove("d-none");
                    this.classList.add("d-none");
                    document.querySelector(`.save-btn[data-field='${field}']`).classList.remove("d-none");
                });
            });
    
            document.querySelectorAll(".save-btn").forEach(button => {
                button.addEventListener("click", function() {
                    let field = this.dataset.field;
                    let newValue;
    
                    if (field === 'profile_photo') {
                        let fileInput = document.getElementById(`input-${field}`);
                        let file = fileInput.files[0];
    
                        if (!file) {
                            toastr.error("Please select a photo to upload.");
                            return;
                        }
    
                        let formData = new FormData();
                        formData.append('photo', file); // Use 'photo' as the key to match backend validation
                        formData.append('user_id', "{{ $user->id }}");
    
                        fetch("{{ route('admin.updateUser') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById(`display-${field}`).innerHTML = `<img src="${data.new_value}" alt="Profile Photo" style="width:100px; height:auto;">`;
                                document.getElementById(`display-${field}`).classList.remove("d-none");
                                document.getElementById(`input-${field}`).classList.add("d-none");
                                button.classList.add("d-none");
                                document.querySelector(`.edit-btn[data-field='${field}']`).classList.remove("d-none");
                                toastr.success(data.message);
                                if (data.redirect) {
                                    window.location.href = data.redirect;
                                }
                            } else {
                                toastr.error(data.message || "Error updating profile photo.");
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            toastr.error("An error occurred while updating the photo.");
                        });
                    } else if (field === 'password' || field === 'plain') {
                        newValue = document.getElementById(`input-${field}`).value;
    
                        fetch("{{ route('admin.updateUser') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                field: field,
                                value: newValue,
                                user_id: "{{ $user->id }}"
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById(`display-${field}`).textContent = "********"; // Mask the updated value
                                document.getElementById(`display-${field}`).classList.remove("d-none");
                                document.getElementById(`input-${field}`).classList.add("d-none");
                                button.classList.add("d-none");
                                document.querySelector(`.edit-btn[data-field='${field}']`).classList.remove("d-none");
                                toastr.success("Password updated successfully!");
                            } else {
                                toastr.error("Error updating password.");
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                        });
                    } else {
                        newValue = document.getElementById(`input-${field}`).value;
    
                        fetch("{{ route('admin.updateUser') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                field: field,
                                value: newValue,
                                user_id: "{{ $user->id }}"
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById(`display-${field}`).textContent = newValue;
                                document.getElementById(`display-${field}`).classList.remove("d-none");
                                document.getElementById(`input-${field}`).classList.add("d-none");
                                button.classList.add("d-none");
                                document.querySelector(`.edit-btn[data-field='${field}']`).classList.remove("d-none");
                                toastr.success("User updated successfully!");
                            } else {
                                toastr.error("Error updating data.");
                            }
                        })
                        .catch(error => {
                            console.error("Error:", error);
                        });
                    }
                });
            });
        });
    </script>

    @include('admin.footer')