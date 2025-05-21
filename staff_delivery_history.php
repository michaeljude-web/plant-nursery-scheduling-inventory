<?php
include 'includes/db.php';

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'All';

$sql = "
SELECT 
    ci.customer_id,
    ci.full_name,
    ci.address,
    ci.contact_number,
    d.delivery_status,
    d.reason,
    d.delivery_date,
    GROUP_CONCAT(CONCAT(si.seed_name, ' - ', sv.variety_name, ' x', o.quantity) SEPARATOR '<br>') AS seedlings_ordered
FROM delivery d
JOIN orders o ON d.order_id = o.order_id
JOIN customer_info ci ON o.customer_id = ci.customer_id
JOIN seedling_variety sv ON o.seedling_variety_id = sv.id
JOIN seedling_info si ON sv.seed_id = si.id
WHERE d.delivery_status IN ('Delivered', 'Cancelled')
";

if ($filter === 'Delivered' || $filter === 'Cancelled') {
    $sql .= " AND d.delivery_status = '$filter'";
}

$sql .= " GROUP BY d.delivery_id ORDER BY d.delivery_date DESC";


$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>EJ's Plant Nursery</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css" />
    <style>
      @media print {
        * {
          box-sizing: border-box;
        }

        @page {
          margin: 0;
          size: auto;
        }

        body, html {
          margin: 0;
          padding: 20px;
          background-color: red;
        }

        .container {
          width: 100%;
        }

        .modal-content {
          margin: 0;
          padding: 0;
          border: 1px solid #ddd;
          box-shadow: none;
        }

        .modal-header {
          background-color: #f8f9fa;
          text-align: center;
          display: none;
        }

        .modal-body {
          padding: 20px;
          font-size: 14px;
        }

        .modal-footer {
          display: none;
        }
      }
    </style>
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
              <a class="nav-link" href="staff_delivery.php">Deliveries</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="staff_delivery_history.php">History</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="staff_login.php">
                <i class="fas fa-sign-out-alt"></i>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container py-4">
      <h3 class="mb-4">Delivery History</h3> <hr>

      <form method="get" class="mb-3 ">
        <label for="filter" class="form-label me-2 fw-bold">Sort by:</label>
        <select name="filter" id="filter" class="form-select d-inline w-auto" onchange="this.form.submit()">
          <option value="All" <?= $filter === 'All' ? 'selected' : '' ?>>All</option>
          <option value="Delivered" <?= $filter === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
          <option value="Cancelled" <?= $filter === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>
      </form>

      <ul class="list-group">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
  <li class="list-group-item d-flex justify-content-between align-items-center">
    <div>
      <strong><?= htmlspecialchars($row['full_name']) ?></strong><br>
      <small>
        <?= date("M d, Y", strtotime($row['delivery_date'])) ?> &bullet;
        <i class="fa-solid <?= $row['delivery_status'] === 'Cancelled' ? 'fa-circle-xmark text-danger' : 'fa-circle-check text-success' ?>"></i>
        <span class="<?= $row['delivery_status'] === 'Cancelled' ? 'text-danger' : 'text-success' ?>">
          <?= $row['delivery_status'] ?>
        </span>
      </small>
    </div>
    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewModal<?= $row['customer_id'] ?>">
      <i class="fa-solid fa-eye"></i>
    </button>
  </li>

  <div class="modal fade" id="viewModal<?= $row['customer_id'] ?>" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content" id="printArea<?= $row['customer_id'] ?>">
        <div class="modal-header">
          <h5 class="modal-title">Delivery Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p><strong>Customer:</strong> <?= htmlspecialchars($row['full_name']) ?></p>
          <p><strong>Contact:</strong> <?= htmlspecialchars($row['contact_number']) ?></p>
          <p><strong>Address:</strong> <?= htmlspecialchars($row['address']) ?></p>
          <p><strong>Seedlings Ordered:</strong><br> <?= $row['seedlings_ordered'] ?></p>
          <p><strong>Delivery Date:</strong> <?= date("M d, Y h:i A", strtotime($row['delivery_date'])) ?></p>
          <p><strong>Status:</strong>
            <span class="<?= $row['delivery_status'] === 'Cancelled' ? 'text-danger' : 'text-success' ?>">
              <?= $row['delivery_status'] ?>
            </span>
          </p>
          <?php if ($row['delivery_status'] === 'Cancelled' && !empty($row['reason'])): ?>
            <p><strong>Reason:</strong> <?= htmlspecialchars($row['reason']) ?></p>
          <?php endif; ?>
        </div>
        <div class="modal-footer">
          <button class="btn bg-primary text-white" onclick="printModal('printArea<?= $row['customer_id'] ?>')">
            <i class="fa-solid fa-print"></i> Print
          </button>
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

    <script>
      function printModal(id) {
        const content = document.getElementById(id).innerHTML;
        const win = window.open('', '', 'height=700,width=900');

        win.document.write('<html><head><title>Print</title>');
        win.document.write('<link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css">');
        win.document.write(`
          <style>
            body {
              font-family: Arial, sans-serif;
              font-size: 30px;
              color: #000;
              padding: 20px;
            }

            .modal-header, .modal-footer {
              display: none !important;
            }

            .print-title {
              text-align: center;
              font-size: 24px;
              font-weight: bold;
              margin-bottom: 20px;
            }

            @page {
              margin: 0;
            }
          </style>
        `);
        win.document.write('</head><body>');
        win.document.write('<div class="print-title"><h1>EJ`s Plant Nursery</h1></div>');
        win.document.write('<div class="print-title"><br> </div>');
        win.document.write(content);
        win.document.write('</body></html>');
        win.document.close();

        win.onload = () => win.print();
      }
    </script>

    <script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

