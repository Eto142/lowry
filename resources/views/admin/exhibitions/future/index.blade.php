@include('admin.header')
<div class="main-panel">
    <div class="content bg-light">
        <div class="page-inner">
            @if(session('message'))
            <div class="alert alert-success mb-2">{{session('message')}}</div>
            @endif
            <div class="mt-2 mb-4">
                <h1 class="title1 text-dark">Future Exhibitions</h1>
            </div>

            <div class="row">
                <div class="col-12">
                    <a href="{{route('admin.future-exhibitions.create')}}" class="float-right btn btn-primary">
                        <i class='fas fa-plus-circle'></i> Add Future Exhibition
                    </a>
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
                                    <input type="text" id="searchInput" placeholder="Search by title or theme"
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
                                    <th>Date</th>
                                    <th>Budget</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="exhibitionlisttbl">
                                @foreach($exhibitions as $exhibition)
                                <tr id="exhibition-row-{{ $exhibition->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $exhibition->title }}</strong><br>
                                        <small class="text-muted">{{ $exhibition->theme }}</small><br>
                                        <small>{{ Str::limit($exhibition->description, 50) }}</small>
                                    </td>
                                    <td>
                                        @if($exhibition->picture_url)
                                        <img src="{{ asset('storage/'.$exhibition->picture_url) }}" width="50"
                                            height="50" class="img-thumbnail" alt="{{ $exhibition->title }}"
                                            style="object-fit: cover;">
                                        @else
                                        <span class="badge badge-secondary">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $exhibition->exhibition_date->format('M d, Y') }}</td>
                                    <td>${{ number_format($exhibition->budget, 2) }}</td>
                                    <td>
                                        <a href="{{ route('admin.future-exhibitions.edit', $exhibition->id) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.future-exhibitions.destroy', $exhibition->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
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
                            let rowsPerPage = 10;

                            function displayTablePage(filteredRows, page) {
                                const start = (page - 1) * rowsPerPage;
                                const end = start + rowsPerPage;

                                rows.forEach(row => row.style.display = "none");
                                filteredRows.slice(start, end).forEach(row => row.style.display = "table-row");

                                generatePagination(filteredRows.length);
                            }

                            function generatePagination(totalRows) {
                                paginationDiv.innerHTML = "";
                                const pageCount = Math.ceil(totalRows / rowsPerPage);

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

                            function filterTable() {
                                const filter = searchInput.value.toLowerCase();
                                const filteredRows = rows.filter(row => row.innerText.toLowerCase().includes(filter));

                                displayTablePage(filteredRows, currentPage);
                            }

                            searchInput.addEventListener("input", () => {
                                currentPage = 1;
                                filterTable();
                            });

                            filterTable();
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.footer')