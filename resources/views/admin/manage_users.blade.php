@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            @if(session('message'))
            <div class="alert alert-success mb-2">{{session('message')}}</div>
            @endif
            <div class="mt-2 mb-4">
                <h1 class="title1 text-dark">Users lists</h1>
            </div>

            <div>
            </div>
            <div>
            </div>
            <div class="row">
                <div class="col-12">
                    <a href="#" data-toggle="modal" data-target="#sendmailModal" class="btn btn-primary btn-lg"
                        style="margin:10px;">Message all</a>
                    <a href="" class="btn btn-warning btn-lg">KYC</a>

                    <a href="{{route('admin.home')}}" data-toggle="modal" data-target="#adduser"
                        class="float-right btn btn-primary"> <i class='fas fa-plus-circle'></i>Create a New User</a>
                    <!-- Modal -->
                    <div class="modal fade" id="adduser" tabindex="-1" aria-h6ledby="exampleModalh6" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-light">
                                    <h3 class="mb-2 d-inline text-dark">Manually Add Users</h3>
                                    <button type="button" class="close text-dark" data-dismiss="modal" aria-h6="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body bg-light">
                                    <div>
                                        <form role="form" method="post" action="{{ route('admin.users.store') }}">
                                            {{ csrf_field()}}
                                            <div class="form-row">
                                                <div class="form-group col-md-12">
                                                    <h6 class="text-dark">First Name</h6>
                                                    <input type="text" id="input1"
                                                        class="form-control bg-light text-dark" name="first_name"
                                                        required>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <h6 class="text-dark">Last Name</h6>
                                                    <input type="text" class="form-control bg-light text-dark"
                                                        name="last_name" required>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <h6 class="text-dark">Email</h6>
                                                    <input type="email" class="form-control bg-light text-dark"
                                                        name="email" required>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <h6 class="text-dark">Password</h6>
                                                    <input type="password" class="form-control bg-light text-dark"
                                                        name="password" required>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <h6 class="text-dark">Confirm Password</h6>
                                                    <input type="password" class="form-control bg-light text-dark"
                                                        name="password_confirmation" required>
                                                </div>
                                            </div>
                                            <button type="submit" class="px-4 btn btn-primary">Add User</button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-5 row">

                <div class="col-md-12 shadow card p-4 bg-light">
                    <div class="row">
                        <div class="col-12">
                            <form class="form-inline">
                                <div class="">
                                    <select class="form-control bg-light text-dark" id="numofrecord">
                                        <option>10</option>
                                        <option>20</option>
                                        <option>30</option>
                                        <option>40</option>
                                        <option>50</option>
                                        <option>100</option>
                                        <option>200</option>
                                        <option>300</option>
                                        <option>400</option>
                                        <option>500</option>
                                        <option>600</option>
                                        <option>700</option>
                                        <option>800</option>
                                        <option>900</option>
                                        <option>1000</option>
                                    </select>
                                </div>
                                <div class="">
                                    <select class="form-control bg-light text-dark" id="order">
                                        <option value="desc">Descending</option>
                                        <option value="asc">Ascending</option>
                                    </select>
                                </div>
                                <div>
                                    <input type="text" id="searchInput" placeholder="Search by name or email"
                                        class="float-right mb-2 mr-sm-2 form-control bg-light text-dark">
                                    <small id="errorsearch"></small>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive" data-example-id="hoverable-table">
                        <table class="table table-hover text-dark" id="userTable">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Client Name</th>
                                    <th>Balance</th>
                                    {{-- <th>User Status</th>
                                    <th>Email Status</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="userslisttbl">
                                @foreach($users as $index => $user)
                                <tr id="user-row-{{ $user->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="display: flex; align-items: center;">
                                        <div
                                            style="width: 40px; height: 40px; border-radius: 50%; background: #007bff; color: white; display: flex; justify-content: center; align-items: center; font-weight: bold; margin-right: 10px;">
                                            {{ strtoupper(substr($user->first_name ?? '', 0, 1)) }}{{
                                            strtoupper(substr($user->last_name ?? '', 0, 1)) }}

                                        </div>
                                        <div>
                                            {{ $user->name }} <br>
                                            <small>{{ strtolower($user->email) }}</small>
                                        </div>


                                    </td>
                                    <td>${{ number_format($user->balance->amount ?? 0, 2) }}</td>

                                    {{-- <td>
                                        <button class="btn btn-sm toggle-user-status" data-id="{{ $user->id }}"
                                            data-status="{{ $user->user_status }}">
                                            @if($user->user_status == 0)
                                            <span class="badge badge-danger">Inactive</span>
                                            @else
                                            <span class="badge badge-success">Active</span>
                                            @endif
                                        </button>
                                    </td> --}}
                                    {{-- <td>
                                        <button class="btn btn-sm toggle-email-status" data-id="{{ $user->id }}"
                                            data-status="{{ $user->email_status }}">
                                            @if($user->email_status == 0)
                                            <span class="badge badge-danger">Unverified</span>
                                            @else
                                            <span class="badge badge-success">Verified</span>
                                            @endif
                                        </button>
                                    </td> --}}
                                    <td>
                                        <a class="btn btn-secondary btn-sm"
                                            href="{{ route('admin.user.view', $user->id) }}" role="button">
                                            Manage
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls -->
                    <div id="pagination" class="mt-3"></div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const table = document.getElementById("userTable");
    const tbody = document.getElementById("userslisttbl");
    const rows = Array.from(tbody.getElementsByTagName("tr"));
    const paginationDiv = document.getElementById("pagination");

    let currentPage = 1;
    let rowsPerPage = 5;

    // Function to display rows for the current page
    function displayTablePage(filteredRows, page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.forEach(row => row.style.display = "none"); // Hide all rows
        filteredRows.slice(start, end).forEach(row => row.style.display = "table-row"); // Show rows for the current page

        generatePagination(filteredRows.length);
    }

    // Function to generate pagination buttons
    function generatePagination(totalRows) {
        paginationDiv.innerHTML = "";
        const pageCount = Math.ceil(totalRows / rowsPerPage);

        // Previous Button
        const prevButton = document.createElement("button");
        prevButton.innerText = "Previous";
        prevButton.className = `btn btn-sm btn-outline-primary`;
        prevButton.style.margin = "2px";
        prevButton.disabled = currentPage === 1;
        prevButton.addEventListener("click", () => {
            if (currentPage > 1) {
                currentPage--;
                filterTable();
            }
        });
        paginationDiv.appendChild(prevButton);

        // Page Buttons
        for (let i = 1; i <= pageCount; i++) {
            const btn = document.createElement("button");
            btn.innerText = i;
            btn.className = `btn btn-sm ${i === currentPage ? 'btn-primary' : 'btn-outline-primary'}`;
            btn.style.margin = "2px";
            btn.addEventListener("click", () => {
                currentPage = i;
                filterTable();
            });
            paginationDiv.appendChild(btn);
        }

        // Next Button
        const nextButton = document.createElement("button");
        nextButton.innerText = "Next";
        nextButton.className = `btn btn-sm btn-outline-primary`;
        nextButton.style.margin = "2px";
        nextButton.disabled = currentPage === pageCount;
        nextButton.addEventListener("click", () => {
            if (currentPage < pageCount) {
                currentPage++;
                filterTable();
            }
        });
        paginationDiv.appendChild(nextButton);
    }

    // Function to filter rows based on search input
    function filterTable() {
        const filter = searchInput.value.toLowerCase();
        const filteredRows = rows.filter(row => row.innerText.toLowerCase().includes(filter));

        displayTablePage(filteredRows, currentPage);
    }

    // Event listener for search input
    searchInput.addEventListener("input", () => {
        currentPage = 1; // Reset to the first page when searching
        filterTable();
    });

    // Initial display of the table
    filterTable();
});
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#input1').on('keypress', function(e) {
					return e.which !== 32;
				});
</script>
<script>
    function getallusers() {
        let number = document.querySelector('#numofrecord').value;
        let searchvalue = document.querySelector('#searchitem').value.trim();
        let ordervalue = document.querySelector('#order').value;
        let table = document.querySelector('#userslisttbl');

        // Construct URL with query parameters
        let url = "{{ route('admin.getusers') }}?" + new URLSearchParams({
            num: number,
            search: searchvalue,
            order: ordervalue
        });

        fetch(url)
        .then(res => res.json())
        .then(response => {
            table.innerHTML = response.data;
            document.querySelector('#searchitem').style.borderColor = 
                response.status === 201 ? 'red' : '';
        })
        .catch(err => console.error(err));
    }

    // Event listeners
    ['#numofrecord', '#order'].forEach(selector => {
        document.querySelector(selector).addEventListener('change', getallusers);
    });
    document.querySelector('#searchitem').addEventListener('input', getallusers);

    // Initial load
    getallusers();

    function viewuser(id) {
        window.location.href = "{{ route('admin.user.view', '') }}/" + id;
    }
</script>
<!-- send all users email -->
<div id="sendmailModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h4 class="modal-title text-dark">This message will be sent to all your users.</h4>
                <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body bg-light">
                <form method="post" action="">
                    @csrf
                    <div class=" form-group">
                        <input type="text" name="subject" class="form-control bg-light text-dark" placeholder="Subject"
                            required>
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
<!-- /send all users email Modal -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
            $('.toggle-email-status').click(function() {
                var button = $(this);
                var userId = button.data('id');
                var currentStatus = button.data('status');
    
                $.ajax({
                    url: "{{ route('admin.user.toggleEmailStatus') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        user_id: userId,
                        status: currentStatus
                    },
                    success: function(response) {
                        if (response.success) {
                            button.data('status', response.new_status);
                            button.find('span').removeClass('badge-danger badge-success')
                                .addClass(response.new_status == 1 ? 'badge-success' : 'badge-danger')
                                .text(response.new_status == 1 ? 'Verified' : 'Unverified');
                                toastr.success("Email status updated successfully!");
                        } else {
                            alert("Error updating status.");
                        }
                    },
                    error: function() {
                        alert("Something went wrong!");
                    }
                });
            });
        });
</script>
<script>
    $(document).ready(function() {
            $('.toggle-user-status').click(function() {
                var button = $(this);
                var userId = button.data('id');
                var currentStatus = button.data('status');
    
                $.ajax({
                    url: "{{ route('admin.user.toggleUserStatus') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        user_id: userId, 
                        status: currentStatus
                    },
                    success: function(response) {
                        if (response.success) {
                            button.data('status', response.new_status);
                            button.find('span').removeClass('badge-danger badge-success')
                                .addClass(response.new_status == 1 ? 'badge-success' : 'badge-danger')
                                .text(response.new_status == 1 ? 'Active' : 'Inactive');
                                toastr.success("User status updated successfully!");
                        } else {
                            alert("Error updating status.");
                        }
                    },
                    error: function() {
                        alert("Something went wrong!");
                    }
                });
            });
        });
</script>





@include('admin.footer')