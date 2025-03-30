@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            @if(session('message'))
            <div class="alert alert-success mb-2">{{session('message')}}</div>
            @endif
            <div class="mt-2 mb-4">
                <h1 class="title1 text-dark">Exhibition List</h1>
            </div>

            <div class="row">
                <div class="col-12">
                    <a href="{{route('admin.exhibitions.create')}}" class="float-right btn btn-primary"> <i
                            class='fas fa-plus-circle'></i> Add Exhibition Item</a>

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
                                    </select>
                                </div>
                                <div class="">
                                    <select class="form-control bg-light text-dark" id="order">
                                        <option value="desc">Descending</option>
                                        <option value="asc">Ascending</option>
                                    </select>
                                </div>
                                <div>
                                    <input type="text" id="searchInput" placeholder="Search by title or description"
                                        class="float-right mb-2 mr-sm-2 form-control bg-light text-dark">
                                    <small id="errorsearch"></small>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive" data-example-id="hoverable-table">
                        <table class="table table-hover text-dark" id="exhibitionTable">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Exhibition</th>
                                    <th>Picture</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="exhibitionlisttbl">
                                @foreach($exhibitions as $index => $exhibition)
                                <tr id="exhibition-row-{{ $exhibition->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $exhibition->title }}</strong><br>
                                        <p>
                                            Added on {{ $exhibition->created_at->format('M d, Y') }}
                                            @if($exhibition->user_id)
                                            by User: {{ $exhibition->user->name ?? 'Unknown User' }}
                                            @elseif($exhibition->admin_id)
                                            by Admin {{-- {{ $exhibition->admin->name ?? 'Unknown Admin' }} --}}
                                            @else
                                            by System
                                            @endif
                                        </p>
                                        <small>{{ Str::limit($exhibition->description, 50) }}</small>
                                    </td>
                                    <td>
                                        @if($exhibition->picture)
                                        <img src="{{ asset($exhibition->picture) }}" width="50" height="50"
                                            class="img-thumbnail">
                                        @else
                                        <span class="badge badge-secondary">No Image</span>
                                        @endif
                                    </td>

                                    <td>
                                        <button class="btn btn-sm toggle-exhibition-status"
                                            data-id="{{ $exhibition->id }}"
                                            data-status="{{ $exhibition->exhibition_status }}">
                                            @if($exhibition->exhibition_status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                            @elseif($exhibition->exhibition_status == 'active')
                                            <span class="badge badge-success">Active</span>
                                            @elseif($exhibition->exhibition_status == 'sold')
                                            <span class="badge badge-primary">Sold</span>
                                            @else
                                            <span class="badge badge-secondary">Archived</span>
                                            @endif
                                        </button>
                                    </td>
                                    <td>
                                        <a class="btn btn-secondary btn-sm"
                                            href="{{ route('admin.exhibition.view', $exhibition->id) }}" role="button">
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
                            const table = document.getElementById("exhibitionTable");
                            const tbody = document.getElementById("exhibitionlisttbl");
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


@include('admin.footer')