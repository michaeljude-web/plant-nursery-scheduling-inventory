<?php
include 'includes/db.php'; // Ensure your DB connection is here

// Count queries
$employee_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM employee_info"))['total'];
$customer_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM customer_info"))['total'];
$delivery_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM delivery"))['total'];
$seedling_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM seedling_variety"))['total'];
$fertilizer_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM seedling_fertilizer"))['total'];
$order_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders"))['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Dashboard</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=2">
</head>
<body>

<section class="container-fluid">
    <div class="row">

        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2 class="mt-4">Dashboard</h2><hr>
            <div class="row mt-4">
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-left-primary">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-users"></i> Total Employees</h5>
                <p class="card-text display-6"><?php echo $employee_count; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-left-success">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-user"></i> Total Customers</h5>
                <p class="card-text display-6"><?php echo $customer_count; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-left-info">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-truck"></i> Total Deliveries</h5>
                <p class="card-text display-6"><?php echo $delivery_count; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-left-warning">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-seedling"></i> Total Seedlings</h5>
                <p class="card-text display-6"><?php echo $seedling_count; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-left-danger">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-flask"></i> Total Fertilizers</h5>
                <p class="card-text display-6"><?php echo $fertilizer_count; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm border-left-dark">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-shopping-cart"></i> Total Orders</h5>
                <p class="card-text display-6"><?php echo $order_count; ?></p>
            </div>
        </div>
    </div>
</div>

        </main>
    </div>
</section>


<script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
