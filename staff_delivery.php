<?php
include 'includes/db.php';

if (isset($_GET['customer_id']) && isset($_GET['new_status'])) {
    $customer_id = (int) $_GET['customer_id'];
    $new_status = $_GET['new_status'];
    $reason = isset($_GET['reason']) ? $_GET['reason'] : null;

    $orderQuery = $conn->prepare("
        SELECT order_id 
        FROM orders 
        WHERE customer_id = ? AND order_id NOT IN (SELECT order_id FROM delivery)
    ");
    $orderQuery->bind_param("i", $customer_id);
    $orderQuery->execute();
    $orderResult = $orderQuery->get_result();

    $insertStmt = $conn->prepare("
        INSERT INTO delivery (order_id, delivery_status, reason, delivery_date) 
        VALUES (?, ?, ?, NOW())
    ");

    while ($row = $orderResult->fetch_assoc()) {
        $order_id = $row['order_id'];
        $insertStmt->bind_param("iss", $order_id, $new_status, $reason);
        $insertStmt->execute();
    }

    echo "<script>alert('Status updated to $new_status.'); window.location.href='staff_delivery.php';</script>";
    exit();
}

$sql = "
    SELECT 
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
    WHERE o.order_id NOT IN (SELECT order_id FROM delivery) AND o.status = 'Pending'
    GROUP BY ci.customer_id
    ORDER BY date_ordered DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EJ's Plant Nursery</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg border-bottom bg-white">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary">Plant Nursery</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link active" href="staff_delivery.php">Deliveries</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="staff_delivery_history.php">History</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="staff_login.php"><i class="fas fa-sign-out-alt"></i></a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5 table-responsive">
    <h2 class="mb-4">Delivery Management</h2> <hr>
    <table class="table table-bordered align-middle">
        <thead class="table-light text-center">
            <tr>
                <th>Customer Name</th>
                <th>Contact</th>
                <th>Address</th>
                <th>Seedlings Ordered</th>
                <th>Total Quantity</th>
                <th>Total Price</th>
                <th>Date Ordered</th>
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
                <td><?= date("M d, Y h:i A", strtotime($row['date_ordered'])) ?></td>
                <td class="text-center">
                    <button class="btn btn-sm btn-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#statusModal" 
                            data-id="<?= $row['customer_id'] ?>">
                        <i class="fas fa-edit"></i> Update
                    </button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Update Order Status</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <p>Do you want to update the status of this order?</p>
            <div class="mb-3">
                <label for="statusSelect" class="form-label">Select Status</label>
                <select id="statusSelect" class="form-select">
                    <option value="Delivered">Delivered</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
            <div class="mb-3 d-none" id="reasonContainer">
                <label for="cancelReason" class="form-label">Reason for Cancellation</label>
                <textarea id="cancelReason" class="form-control" placeholder="Enter reason..." rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button id="updateStatusBtn" class="btn btn-primary">Update Status</button>
        </div>
    </div>
  </div>
</div>

<script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
<script>
    let selectedCustomerId = null;

    document.getElementById('statusModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        selectedCustomerId = button.getAttribute("data-id");
        document.getElementById("statusSelect").value = "Delivered";
        document.getElementById("cancelReason").value = "";
        document.getElementById("reasonContainer").classList.add("d-none");
    });

    document.getElementById("statusSelect").addEventListener("change", function () {
        const isCancelled = this.value === "Cancelled";
        document.getElementById("reasonContainer").classList.toggle("d-none", !isCancelled);
    });

    document.getElementById("updateStatusBtn").addEventListener("click", function () {
        const newStatus = document.getElementById("statusSelect").value;
        const reason = document.getElementById("cancelReason").value.trim();

        if (newStatus === "Cancelled" && reason === "") {
            alert("Please provide a reason for cancellation.");
            return;
        }

        const params = new URLSearchParams({
            customer_id: selectedCustomerId,
            new_status: newStatus,
            reason: reason
        });

        window.location.href = "staff_delivery.php?" + params.toString();
    });
</script>
</body>
</html>
