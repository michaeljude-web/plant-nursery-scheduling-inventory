<?php
include 'includes/db.php';

if (isset($_GET['customer_id']) && isset($_GET['action']) && $_GET['action'] == 'confirm') {
    $customer_id = (int) $_GET['customer_id'];

    $sql = "UPDATE orders SET status = 'Pending', date_added = NOW() 
            WHERE customer_id = ? AND status = 'Reserve'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();

    echo "<script>alert('Order confirmed!'); window.location.href='staff_sales_reserve_orders.php';</script>";
    exit();
}

$sql = "SELECT 
            ci.customer_id,
            ci.full_name,
            ci.address,
            ci.contact_number,
            GROUP_CONCAT(CONCAT(si.seed_name, ' - ', sv.variety_name, ' (', o.quantity, ')') SEPARATOR '<br>') AS seedlings_ordered,
            SUM(o.quantity) AS total_quantity,
            SUM(o.quantity * sv.price) AS total_price,
            MAX(o.date_added) AS date_ordered
        FROM orders o
        JOIN customer_info ci ON o.customer_id = ci.customer_id
        JOIN seedling_variety sv ON o.seedling_variety_id = sv.id
        JOIN seedling_info si ON sv.seed_id = si.id
        WHERE o.status = 'Reserve'
        GROUP BY ci.customer_id
        ORDER BY date_ordered DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>EJ's Plant Nursery</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css" />
    <script src="assets/jquery/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary">Plant Nursery</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="staff_sales_plot.php">Plots</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="staff_sales_inventory.php">Inventory</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="staff_sales_fertilizer.php">Fertilizer</a>
                </li> -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="scheduleDropdown" data-bs-toggle="dropdown">
                        Orders
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="scheduleDropdown">
                        <li><a class="dropdown-item" href="staff_sales_orders.php">Add Order</a></li>
                        <li><a class="dropdown-item" href="staff_sales_reserve_orders.php">Reserve Order</a></li>
                    </ul>
                </li>
                <!-- <li class="nav-item"><a class="nav-link" href="staff_sales_report.php">Reports</a></li> -->
                <li class="nav-item"><a class="nav-link" href="staff_login.php"><i class="fas fa-sign-out-alt"></i></a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5 table-responsive">
    <h2 class="mb-4">Reserved Orders</h2> <hr>

    <table class="table table-bordered">
        <thead class="table-light text-center">
            <tr>
                <th>Customer Name</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Seedlings Ordered</th>
                <th>Total Quantity</th>
                <th>Total Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['contact_number']) ?></td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td><?= $row['seedlings_ordered'] ?></td>
                    <td class="text-center"><?= $row['total_quantity'] ?></td>
                    <td>₱<?= number_format($row['total_price'], 2) ?></td>
                    <td class="text-center">
                        <button class="btn btn-success btn-sm" onclick="confirmOrder(<?= $row['customer_id'] ?>)">
                            <i class="fas fa-check-circle"></i> Confirm Order
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    function confirmOrder(customerId) {
        if (confirm("Are you sure you want to confirm this reservation to order?")) {
            window.location.href = "staff_sales_reserve_orders.php?customer_id=" + customerId + "&action=confirm";
        }
    }
</script>

<script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
