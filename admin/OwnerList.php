<?php 

include_once("../owner/OwnerController.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Document</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include_once("layouts/header.php"); ?>

    <div class="container mt-3">
    <div class="container mt-3">
        <h2 class="display-4 text-primary font-weight-bold">Admin Dashboard</h2>
        <form id="search-form" class="d-flex" role="search" method="GET" action="index.php">
                <input class="form-control me-2" type="text" id="search-name" placeholder="Search by name">
                <input class="form-control me-2" type="text" id="search-unit" placeholder="Search by unit">
            <button class="btn btn-outline-primary" type="submit">Search</button>
        </form> 
    </div>

        <table id="user-table" class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>User Phone</th>
                    <th>User Unit</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be inserted here by JavaScript -->
            </tbody>
        </table>

        <nav id="pagination" aria-label="Page navigation">
            <ul class="pagination">
                <!-- Pagination links will be inserted here by JavaScript -->
            </ul>
        </nav>
    </div>

    <?php include_once("layouts/footer.php"); ?>
</body>
</html>
<script>
function fetchOwners(page = 1) {
    let name = $('#search-name').val().trim();
    let unit = $('#search-unit').val().trim();
    
    $.ajax({
        url: 'search.php',
        method: 'GET',
        data: {
            name: name || '',
            unit: unit || '',
            page: page
        },
        dataType: 'json',
        success: function(response) {
            console.log(response);  // Debug: Log response

            if (response.error) {
                alert('Error: ' + response.error);
                return;
            }

            let users = response.users;
            let totalPages = response.totalPages;

            let tableBody = '';
            users.forEach(user => {
                tableBody += `<tr>
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.phone}</td>
                    <td>${user.unit}</td>
                    <td><a href="edit_owner.php?id=${user.id}" class="btn btn-sm btn-primary">Edit</a></td>
                    <td><a href="delete_owner.php?id=${user.id}" class="btn btn-sm btn-danger">Delete</a></td>
                </tr>`;
            });

            $('#user-table tbody').html(tableBody);

            let pagination = '';
            for (let i = 1; i <= totalPages; i++) {
                pagination += `<li class="page-item ${i === page ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
            }

            $('#pagination .pagination').html(pagination);
        },
        error: function() {
            alert('Error loading data. Please try again later.');
        }
    });
}

$(document).ready(function() {
    fetchOwners();
    
    $('#search-form').submit(function(e) {
        e.preventDefault();
        fetchOwners();
    });
    
    $('#pagination').on('click', '.page-link', function(e) {
        e.preventDefault();
        let page = $(this).data('page');
        fetchOwners(page);
    });
});
</script>