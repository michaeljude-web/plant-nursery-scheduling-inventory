<?php
include "includes/db.php";

$limit = 10;
$page = isset($_GET["page"]) ? (int) $_GET["page"] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";

if (!empty($search)) {
    $query = "SELECT id, firstname, lastname, age, contact_number, address, role 
              FROM staff 
              WHERE firstname LIKE ? OR lastname LIKE ? 
              ORDER BY firstname ASC 
              LIMIT ? OFFSET ?";
    $count_query = "SELECT COUNT(id) AS total FROM staff 
                    WHERE firstname LIKE ? OR lastname LIKE ?";
} else {
    $query = "SELECT id, firstname, lastname, age, contact_number, address, role 
              FROM staff 
              ORDER BY firstname ASC 
              LIMIT ? OFFSET ?";
    $count_query = "SELECT COUNT(id) AS total FROM staff";
}

if ($stmt = mysqli_prepare($conn, $count_query)) {
    if (!empty($search)) {
        $search_param = "%$search%";
        mysqli_stmt_bind_param($stmt, "ss", $search_param, $search_param);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $total_row = mysqli_fetch_assoc($result);
    $total_records = $total_row["total"];
    mysqli_stmt_close($stmt);
} else {
    $total_records = 0;
}

$total_pages = ceil($total_records / $limit);

if ($stmt = mysqli_prepare($conn, $query)) {
    if (!empty($search)) {
        mysqli_stmt_bind_param(
            $stmt,
            "ssii",
            $search_param,
            $search_param,
            $limit,
            $offset
        );
    } else {
        mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $result = false;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff List</title>
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css">
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>

    <style>
.staff-search-form {
     display: flex;
     justify-content: left;
     margin-bottom: 20px;
}
 .staff-search-form input {
     padding: 10px;
     width: 250px;
     border: 2px solid #6a994e;
     border-radius: 20px;
     outline: none;
     font-size: 16px;
     background-color: #e9f5db;
     transition: 0.3s;
}
 .staff-search-form input:focus {
     border-color: #4e7a3f;
     background-color: #fdfaf6;
}
 .staff-search-form button {
     padding: 10px 15px;
     border: none;
     background-color: #6a994e;
     color: white;
     font-size: 16px;
     border-radius: 20px;
     cursor: pointer;
     margin-left: 8px;
     transition: 0.3s;
}
 .staff-search-form button:hover {
     background-color: #4e7a3f;
}
 .staff-table {
     width: 100%;
     border-collapse: collapse;
     background-color: #fdfaf6;
     font-weight: normal;
}
 .staff-table thead {
     background-color: #6a994e;
}
 .staff-table thead th, .staff-table td {
     padding: 12px;
     font-size: 16px;
     border: 1px solid #333;
     font-weight: normal;
}
 .staff-table tbody tr:nth-child(even) {
     background-color: #e9f5db;
}
 .staff-table tbody tr:nth-child(odd) {
     background-color: #f5f5dc;
}
 .staff-table tbody tr:hover {
     background-color: #b7c59b;
}
 .staff-btn-warning {
     background-color: #d4a373;
     color: white;
     padding: 6px 10px;
     border-radius: 5px;
     cursor: pointer;
     border: none;
}
 .staff-btn-warning:hover {
     background-color: #b08968;
}
 .staff-btn-danger {
     background-color: #bc4749;
     color: white;
     padding: 6px 10px;
     border-radius: 5px;
     cursor: pointer;
     border: none;
}
 .staff-btn-danger:hover {
     background-color: #a23e48;
}
 .pagination {
     margin-top: 20px;
     display: flex;
     justify-content: center;
     list-style: none;
     padding: 0;
}
 .pagination li {
     margin: 5px;
}
 .pagination a {
     text-decoration: none;
     padding: 8px 12px;
     background-color: #6a994e;
     color: white;
     border-radius: 5px;
}
 .pagination a:hover {
     background-color: #4e7a3f;
}
 .pagination .active {
     background-color: #4e7a3f;
     font-weight: bold;
}
 </style>
</head>
<body>
    <?php include "includes/sidebar.php"; ?>
    
    <div class="content">
        <h1>Staff List</h1><br>
   
        <form method="GET" class="staff-search-form">
    <input type="text" name="search" placeholder="Search staff..." value="<?= isset(
        $_GET["search"]
    )
        ? $_GET["search"]
        : "" ?>">
    <button type="submit"><i class="fa fa-search"></i></button>
</form>

        <table class="staff-table">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Age</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) {
                    $fullname = "{$row["firstname"]} {$row["lastname"]}";
                    echo "<tr>
                            <td>{$fullname}</td>
                            <td>{$row["age"]}</td>
                            <td>{$row["contact_number"]}</td>
                            <td>{$row["address"]}</td>
                            <td>{$row["role"]}</td>
                            <td>
                                <button class='staff-btn-warning' data-bs-toggle='modal' data-bs-target='#editModal{$row["id"]}'><i class='fa fa-edit'></i></button>
                                <button class='staff-btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal{$row["id"]}'><i class='fa fa-trash'></i></button>
                            </td>
                          </tr>";

                    // Edit
                    echo "<div class='modal fade' id='editModal{$row["id"]}' tabindex='-1' aria-labelledby='editModalLabel{$row["id"]}' aria-hidden='true'>
                            <div class='modal-dialog'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title'>Edit Staff</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <form action='includes/update_staff.php' method='POST'>
                                        <div class='modal-body'>
                                            <input type='hidden' name='id' value='{$row["id"]}'>
                                            <div class='mb-3'>
                                                <label class='form-label'>First Name</label>
                                                <input type='text' name='firstname' class='form-control' value='{$row["firstname"]}' required>
                                            </div>
                                            <div class='mb-3'>
                                                <label class='form-label'>Last Name</label>
                                                <input type='text' name='lastname' class='form-control' value='{$row["lastname"]}' required>
                                            </div>
                                            <div class='mb-3'>
                                                <label class='form-label'>Age</label>
                                                <input type='number' name='age' class='form-control' value='{$row["age"]}' required>
                                            </div>
                                            <div class='mb-3'>
                                                <label class='form-label'>Contact Number</label>
                                                <input type='text' name='contact_number' class='form-control' value='{$row["contact_number"]}' required>
                                            </div>
                                            <div class='mb-3'>
                                                <label class='form-label'>Address</label>
                                                <textarea name='address' class='form-control' required>{$row["address"]}</textarea>
                                            </div>
                                        </div>
                                        <div class='modal-footer'>
                                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                            <button type='submit' class='btn btn-primary'>Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                          </div>";

                    // Delete
                    echo "<div class='modal fade' id='deleteModal{$row["id"]}' tabindex='-1' aria-labelledby='deleteModalLabel{$row["id"]}' aria-hidden='true'>
                            <div class='modal-dialog'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title'>Confirm Delete</h5>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                    </div>
                                    <div class='modal-body'>
                                        Are you sure you want to delete <b>{$fullname}</b>?
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                        <a href='includes/delete_staff.php?id={$row["id"]}' class='btn btn-danger'>Delete</a>
                                    </div>
                                </div>
                            </div>
                          </div>";
                } ?>
            </tbody>
        </table>

        <ul class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li><a href="?page=<?= $i ?>&search=<?= $search ?>" class="<?= $i ==
$page
    ? "active"
    : "" ?>"><?= $i ?></a></li>
    <?php endfor; ?>
</ul>

    </div>
</body>
</html>
