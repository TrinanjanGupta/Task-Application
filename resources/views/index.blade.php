<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Application</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .table thead th {
            cursor: pointer;
        }
        .modal img {
            width: 100%;
        }
        /* Adjustments for smaller screens */
        @media (max-width: 768px) {
            .form-group {
                margin-bottom: 0.5rem; /* Reduce margin between form elements */
            }
        }
        
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Data List</h1>

        <!-- Add Form -->
        <form id="addForm" action="/add" method="post" enctype="multipart/form-data" class="mb-4 row">
            @csrf
            <div class="form-group col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Name" required>
            </div>
            <div class="form-group col-md-3">
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div class="form-group col-md-3">
                <input type="text" name="address" class="form-control" placeholder="Address" required>
            </div>
            <div class="form-group col-md-2">
                <select name="gender" class="form-control" required>
                    <option value="" disabled selected>Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group col-md-1">
                <button type="submit" class="btn btn-primary btn-block">Add</button>
            </div>
        </form>

        <!-- Data Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th onclick="sortTable(0)">ID</th>
                    <th onclick="sortTable(1)">Name</th>
                    <th>Image</th>
                    <th>Address</th>
                    <th>Gender</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                <tr>
                    <td>{{ $item['id'] }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td><img src="{{ $item['image'] }}" alt="Image" class="img-thumbnail" width="50"></td>
                    <td>{{ $item['address'] }}</td>
                    <td>{{ $item['gender'] }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editItem({{ json_encode($item) }})">Edit</button>
                        <form action="/delete" method="post" style="display:inline;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item['id'] }}">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        <button class="btn btn-info btn-sm" onclick="viewItem({{ $item['id'] }})">View</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
        <!-- Edit Item Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" action="/update" method="post" enctype="multipart/form-data" class="mb-4">
                            @csrf
                            <input type="hidden" name="id" id="editId">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="editName">Name</label>
                                    <input type="text" name="name" id="editName" class="form-control" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="editImage">Image</label>
                                    <input type="file" name="image" id="editImage" class="form-control" accept="image/*">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="editAddress">Address</label>
                                    <input type="text" name="address" id="editAddress" class="form-control" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="editGender">Gender</label>
                                    <select name="gender" id="editGender" class="form-control" required>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- View Item Modal -->
        <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewModalLabel">View Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="viewId"></p>
                        <p id="viewName"></p>
                        <img id="viewImage" src="" alt="Image">
                        <p id="viewAddress"></p>
                        <p id="viewGender"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        function sortTable(n) {
            const table = document.querySelector("table tbody");
            const rows = Array.from(table.rows);
            const direction = table.getAttribute("data-sort-direction") || "asc";

            rows.sort((rowA, rowB) => {
                const cellA = rowA.cells[n].innerText.toLowerCase();
                const cellB = rowB.cells[n].innerText.toLowerCase();

                if (direction === "asc") {
                    return cellA > cellB ? 1 : -1;
                } else {
                    return cellA < cellB ? 1 : -1;
                }
            });

            table.setAttribute("data-sort-direction", direction === "asc" ? "desc" : "asc");

            rows.forEach(row => table.appendChild(row));
        }

        function editItem(item) {
            $('#editModal').modal('show');
            document.getElementById('editId').value = item.id;
            document.getElementById('editName').value = item.name;
            document.getElementById('editAddress').value = item.address;
            document.getElementById('editGender').value = item.gender;
        }

        function viewItem(id) {
            fetch('/view', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(item => {
                $('#viewModal').modal('show');
                document.getElementById('viewId').innerText = `ID: ${item.id}`;
                document.getElementById('viewName').innerText = `Name: ${item.name}`;
                document.getElementById('viewImage').src = item.image;
                document.getElementById('viewAddress').innerText = `Address: ${item.address}`;
                document.getElementById('viewGender').innerText = `Gender: ${item.gender}`;
            });
        }
    </script>
</body>
</html>
