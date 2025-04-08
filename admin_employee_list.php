<?php 
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Employee</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <script src="assets/jquery/jquery-3.6.0.min.js"></script>
</head>
<body>

<section class="container-fluid">
    <div class="row">

        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2 class="mt-4">Employee List</h2> <hr>

            <div class="row mb-3">
    <div class="col-md-6">
        <input type="text" id="searchInput" class="form-control" placeholder="Search employee...">
    </div>
          <div class="col-md-6">
             <select id="roleFilter" class="form-select">
              <option value="">All Roles</option>
              <option value="Sales Team">Sales Team</option>
              <option value="Delivery Staff">Delivery Staff</option>
              <option value="Nutritionist">Nutritionist</option>
             </select>
          </div></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="text-center">
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Age</th>
                                <th>Contact Number</th>
                                <th>Address</th>
                                <th>Role</th>
                                <th>Username</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="staffTable">
                            <?php
                             include 'includes/db.php';
                            $sql = "SELECT id, firstname, lastname, age, contact_number, address, role, username FROM employee_info ORDER BY id ASC";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr class='text-center staff-row' data-role='{$row['role']}'>
                                            <td>{$row['id']}</td>
                                            <td>{$row['firstname']} {$row['lastname']}</td>
                                            <td>{$row['age']}</td>
                                            <td>{$row['contact_number']}</td>
                                            <td>{$row['address']}</td>
                                            <td>{$row['role']}</td>
                                            <td>{$row['username']}</td>
                                            <td>
                                                <button class='btn btn-sm btn-warning edit-btn' data-id='{$row['id']}' data-firstname='{$row['firstname']}' data-lastname='{$row['lastname']}' data-age='{$row['age']}' data-contact='{$row['contact_number']}' data-address='{$row['address']}' data-role='{$row['role']}' data-username='{$row['username']}'>
                                                    <i class='fa-solid fa-pen-to-square'></i> Edit
                                                </button>
                                                <button class='btn btn-sm btn-danger delete-btn' data-id='{$row['id']}'>
                                                    <i class='fa-solid fa-trash'></i> Delete
                                                </button>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center text-danger'>No staff records found</td></tr>";
                            }

                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Employee Info</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editForm">
                                <input type="hidden" id="editId">
                                <div class="mb-3">
                                    <label>First Name</label>
                                    <input type="text" id="editFirstname" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Last Name</label>
                                    <input type="text" id="editLastname" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Age</label>
                                    <input type="number" id="editAge" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Contact Number</label>
                                    <input type="text" id="editContact" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Address</label>
                                    <input type="text" id="editAddress" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</section>

<script>
    $(document).ready(function () {
    $("#searchInput").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#staffTable tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    $("#roleFilter").on("change", function () {
        var selectedRole = $(this).val().toLowerCase();
        $("#staffTable tr").filter(function () {
            $(this).toggle(selectedRole === "" || $(this).data("role").toLowerCase() === selectedRole);
        });
    });

    // Edit
    $(".edit-btn").on("click", function () {
        $("#editId").val($(this).data("id"));
        $("#editFirstname").val($(this).data("firstname"));
        $("#editLastname").val($(this).data("lastname"));
        $("#editAge").val($(this).data("age"));
        $("#editContact").val($(this).data("contact"));
        $("#editAddress").val($(this).data("address"));
        $("#editModal").modal("show");
    });

    // Save
    $("#editForm").on("submit", function (e) {
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "actions/update_employee.php",
            data: {
                id: $("#editId").val(),
                firstname: $("#editFirstname").val(),
                lastname: $("#editLastname").val(),
                age: $("#editAge").val(),
                contact: $("#editContact").val(),
                address: $("#editAddress").val(),
            },
            success: function (response) {
                if (response === "success") {
                    alert("Employee updated successfully!");
                    location.reload();
                } else {
                    alert("Error updating staff.");
                }
            },
        });
    });

    $(".delete-btn").on("click", function () {
        var id = $(this).data("id");

        if (confirm("Are you sure you want to delete this staff?")) {
            $.ajax({
                url: "actions/delete_employee.php",
                type: "GET",
                data: { id: id },
                success: function (response) {
                    if (response === "success") {
                        alert("Employee deleted successfully!");
                        location.reload();
                    } else {
                        alert("Error deleting staff.");
                    }
                },
            });
        }
    });
});

</script>
<script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>