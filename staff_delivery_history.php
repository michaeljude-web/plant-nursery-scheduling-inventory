<?php
include 'includes/db.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'All';

$sql = "SELECT 
            sfs.*,
            ci.full_name,
            ci.address,
            ci.contact_number,
            sv.variety_name,
            si.seed_name
        FROM seedling_for_sale sfs
        JOIN customer_info ci ON sfs.customer_id = ci.customer_id
        JOIN seedling_variety sv ON sfs.seedling_variety_id = sv.id
        JOIN seedling_info si ON sv.seed_id = si.id
        WHERE sfs.status IN ('Delivered', 'Cancelled')";

if ($filter === 'Delivered' || $filter === 'Cancelled') {
    $sql .= " AND sfs.status = '$filter'";
}

$sql .= " ORDER BY sfs.date_added DESC";
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
        <a class="navbar-brand fw-bold text-primary">Plant Nursery</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="staff_delivery.php">Deliveries</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="staff_delivery_history.php">History</a>
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

    <div class="container py-4">
  <h3 class="mb-4 text-center">Delivery History</h3>

  <form method="get" class="mb-3 text-center">
    <label for="filter" class="form-label me-2 fw-bold">Sort by:</label>
    <select name="filter" id="filter" class="form-select d-inline w-auto" onchange="this.form.submit()">
      <option value="All" <?= $filter === 'All' ? 'selected' : '' ?>>All</option>
      <option value="Delivered" <?= $filter === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
      <option value="Cancelled" <?= $filter === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
    </select>
  </form>

  <ul class="list-group">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): 
          $isDelivered = $row['status'] === 'Delivered';
          $icon = $isDelivered ? 'fa-circle-check text-success' : 'fa-circle-xmark text-danger';
          $statusText = $isDelivered ? 'Delivered' : 'Cancelled';
      ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <div>
            <strong><?= htmlspecialchars($row['seed_name'] . ' - ' . $row['variety_name']) ?></strong><br>
            <small><?= date("M d, Y", strtotime($row['date_added'])) ?> &bullet;
              <i class="fa-solid <?= $icon ?>"></i>
              <span class="<?= $isDelivered ? 'text-success' : 'text-danger' ?>">
                <?= $statusText ?>
              </span>
            </small>
          </div>
          <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewModal<?= $row['sale_id'] ?>">
            <i class="fa-solid fa-eye"></i>
          </button>
        </li>

        <!-- Modal -->
        <div class="modal fade" id="viewModal<?= $row['sale_id'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $row['sale_id'] ?>" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalLabel<?= $row['sale_id'] ?>">Delivery Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <p><strong>Customer:</strong> <?= htmlspecialchars($row['full_name']) ?></p>
                <p><strong>Contact:</strong> <?= htmlspecialchars($row['contact_number']) ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($row['address']) ?></p>
                <p><strong>Seedling:</strong> <?= htmlspecialchars($row['seed_name'] . ' - ' . $row['variety_name']) ?></p>
                <p><strong>Quantity:</strong> <?= $row['quantity'] ?></p>
                <p><strong>Status:</strong> <span class="<?= $isDelivered ? 'text-success' : 'text-danger' ?>"><?= $statusText ?></span></p>
                <p><strong>Date:</strong> <?= date("M d, Y h:i A", strtotime($row['date_added'])) ?></p>
                <?php if (!$isDelivered && !empty($row['reason'])): ?>
                  <p><strong>Cancellation Reason:</strong> <?= htmlspecialchars($row['reason']) ?></p>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <li class="list-group-item text-center text-muted">No records found.</li>
    <?php endif; ?>
  </ul>
</div>
    <script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
