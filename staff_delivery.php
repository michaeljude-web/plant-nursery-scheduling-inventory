<?php
include 'includes/db.php';

if (isset($_GET['status_id']) && isset($_GET['new_status'])) {
    $sale_id = (int) $_GET['status_id'];
    $new_status = $_GET['new_status'];
    $reason = isset($_GET['reason']) ? $_GET['reason'] : null;

    if ($new_status === "Cancelled" && empty(trim($reason))) {
        echo "<script>alert('Cancellation reason is required.'); window.location.href='deliveries.php';</script>";
        exit();
    }

    if ($new_status === "Cancelled") {
        $sql = "UPDATE seedling_for_sale SET status = ?, reason = ? WHERE sale_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $new_status, $reason, $sale_id);
    } else {
        $sql = "UPDATE seedling_for_sale SET status = ?, reason = NULL WHERE sale_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_status, $sale_id);
    }

    $stmt->execute();
    echo "<script>alert('Status updated to $new_status.'); window.location.href='staff_delivery.php';</script>";
    exit();
}

$sql = "SELECT 
            sfs.sale_id,
            ci.full_name,
            ci.address,
            ci.contact_number,
            sv.variety_name,
            si.seed_name,
            sfs.quantity,
            sfs.status,
            sfs.date_added
        FROM seedling_for_sale sfs
        JOIN customer_info ci ON sfs.customer_id = ci.customer_id
        JOIN seedling_variety sv ON sfs.seedling_variety_id = sv.id
        JOIN seedling_info si ON sv.seed_id = si.id
        WHERE sfs.status = 'Pending'
        ORDER BY sfs.date_added DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>EJ's Plant Nursery</title>
        <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css" />
    </head>
    <body class="bg-light">
        <nav class="navbar navbar-expand-lg border-bottom">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="#">Plant Nursery</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="staff_delivery.php">Deliveries</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="staff_delivery_history.php">History</a>
                        </li>
                        <li class="nav-item">
                             <a class="nav-link" href="staff_login.php"">
                        <i class="fas fa-sign-out-alt"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container mt-5">
            <h2 class="mb-4 text-center">Delivery Management</h2>

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Customer Name</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Seedling</th>
                        <th>Quantity</th>
                        <th>Status</th>
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
                        <td><?= htmlspecialchars($row['seed_name'] . ' - ' . $row['variety_name']) ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td>
                            <?php if ($row['status'] === 'Delivered'): ?>
                            <span class="badge bg-success">Delivered</span>
                            <?php else: ?>
                            <span class="badge bg-warning text-dark"><?= $row['status'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= date("M d, Y h:i A", strtotime($row['date_added'])) ?></td>
                        <td>
                            <?php if ($row['status'] !== 'Delivered'): ?>
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#statusModal" data-id="<?= $row['sale_id'] ?>" data-status="<?= $row['status'] ?>"><i class="fas fa-edit"></i> Update</button>
                            <?php else: ?>
                            <span class="text-muted">Done</span>
                            <?php endif; ?>
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
                        <h5 class="modal-title" id="statusModalLabel">Update Order Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            <textarea class="form-control" id="cancelReason" rows="3" placeholder="Enter reason..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button id="updateStatusBtn" class="btn btn-primary">Update Status</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            let selectedSaleId = null;

            var statusModal = document.getElementById("statusModal");
            statusModal.addEventListener("show.bs.modal", function (event) {
                var button = event.relatedTarget;
                selectedSaleId = button.getAttribute("data-id");
                var currentStatus = button.getAttribute("data-status");

                var statusSelect = document.getElementById("statusSelect");
                var reasonContainer = document.getElementById("reasonContainer");
                var cancelReason = document.getElementById("cancelReason");

                statusSelect.value = currentStatus;
                cancelReason.value = "";

                if (statusSelect.value === "Cancelled") {
                    reasonContainer.classList.remove("d-none");
                } else {
                    reasonContainer.classList.add("d-none");
                }
            });

            document.getElementById("statusSelect").addEventListener("change", function () {
                var reasonContainer = document.getElementById("reasonContainer");
                if (this.value === "Cancelled") {
                    reasonContainer.classList.remove("d-none");
                } else {
                    reasonContainer.classList.add("d-none");
                }
            });

            document.getElementById("updateStatusBtn").addEventListener("click", function () {
                const newStatus = document.getElementById("statusSelect").value;
                const reason = document.getElementById("cancelReason").value;

                if (newStatus === "Cancelled" && reason.trim() === "") {
                    alert("Please provide a reason for cancellation.");
                    return;
                }

                const params = new URLSearchParams({
                    status_id: selectedSaleId,
                    new_status: newStatus,
                    reason: reason,
                });

                window.location.href = "staff_delivery.php?" + params.toString();
            });
        </script>

        <script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
    </body>
</html>


