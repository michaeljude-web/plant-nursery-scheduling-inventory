<?php
include 'includes/db.php';
$sql = "SELECT CONCAT(firstname, ' ', lastname) AS fullname, contact_number, address FROM staff WHERE role = 'Nutritionist'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutritionist Team</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css">
    <style>
        body {
            font-family: Times New Roman;
            text-align: center;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-light bg-light">
        <div class="container-fluid d-flex justify-content-center">
            <div>
            <a class="nav-link d-inline" href="staff_nutritionist_dashboard.php">
    <i class="fa-solid fa-calendar"></i> Calendar
</a> |
<a class="nav-link d-inline" href="staff_nutritionist_team.php">
    <i class="fa-solid fa-users"></i> Team
</a> |
<a class="nav-link d-inline" href="staff_login.php">
    <i class="fa-solid fa-right-from-bracket"></i> Logout
</a>

            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2 class="text-center">Nutritionist Team</h2>
        <table class="table table-bordered table-striped mt-3 text-center">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
