<?php
include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['seedling_variety_id'])) {
    $seedling_variety_id = $_POST['seedling_variety_id'];
    $plot_count = (int) $_POST['plot_count'];

    $check = $conn->prepare("SELECT COUNT(*) as total FROM planting_plot WHERE seedling_variety_id = ?");
    $check->bind_param("i", $seedling_variety_id);
    $check->execute();
    $result = $check->get_result()->fetch_assoc();
    $existing = (int) $result['total'];

    $max_allowed = 50;
    $remaining = $max_allowed - $existing;

    if ($plot_count > $remaining) {
        echo "<script>alert('Cannot add $plot_count plot(s). Only $remaining plot(s) can be added to stay within the 50 plot limit.'); window.location.href='plot.php';</script>";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO planting_plot (seedling_variety_id, quantity_limit, current_quantity, date_planted, status) VALUES (?, ?, ?, ?, ?)");

    $current_quantity = 1;
    $quantity_limit = 50;
    $date_planted = date("Y-m-d");
    $status = "Growing";

    for ($i = 0; $i < $plot_count; $i++) {
        $stmt->bind_param("iiiss", $seedling_variety_id, $quantity_limit, $current_quantity, $date_planted, $status);
        $stmt->execute();
    }

    echo "<script>alert('Successfully added $plot_count plot(s).'); window.location.href='staff_sales_plot.php';</script>";
    exit();
}

$category_result = $conn->query("SELECT * FROM seedling_category");

$sql = "SELECT 
    sv.id AS seedling_variety_id,
    sc.category_name,
    si.seed_name,
    sv.variety_name,
    COALESCE(SUM(pp.current_quantity), 0) AS total_planted
FROM seedling_variety sv
JOIN seedling_info si ON sv.seed_id = si.id
JOIN seedling_category sc ON si.category_id = sc.id
LEFT JOIN planting_plot pp ON pp.seedling_variety_id = sv.id
GROUP BY sv.id";
$result = $conn->query($sql);

$seedlings = [];
while ($row = $result->fetch_assoc()) {
    $seedlings[] = $row;
}

// About sa Planting
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['transfer_quantity']) && isset($_POST['seedling_variety_id_transfer'])) {
    $seedling_variety_id = $_POST['seedling_variety_id_transfer'];
    $transfer_quantity = (int) $_POST['transfer_quantity'];

    $sql = "SELECT SUM(current_quantity) AS total_quantity FROM planting_plot WHERE seedling_variety_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $seedling_variety_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $total_planted_quantity = (int) $result['total_quantity'];

    if ($transfer_quantity > $total_planted_quantity) {
        echo "<script>alert('Cannot transfer more seedlings than are available in the planting plots.'); window.location.href='staff_sales_plot.php';</script>";
        exit();
    }

    $inventory_sql = $conn->prepare("INSERT INTO seedling_inventory (seedling_variety_id, quantity) VALUES (?, ?)");
    $inventory_sql->bind_param("ii", $seedling_variety_id, $transfer_quantity);
    $inventory_sql->execute();

    $remaining_quantity = $transfer_quantity;

    $plots_sql = $conn->prepare("SELECT plot_id, current_quantity FROM planting_plot WHERE seedling_variety_id = ? ORDER BY plot_id ASC");
    $plots_sql->bind_param("i", $seedling_variety_id);
    $plots_sql->execute();
    $plots_result = $plots_sql->get_result();

    while ($plot = $plots_result->fetch_assoc()) {
        if ($remaining_quantity <= 0) {
            break;
        }

        $deduct = min($remaining_quantity, $plot['current_quantity']);
        if ($deduct > 0) {
            $update = $conn->prepare("UPDATE planting_plot SET current_quantity = current_quantity - ? WHERE plot_id = ?");
            $update->bind_param("ii", $deduct, $plot['plot_id']);
            $update->execute();

            $remaining_quantity -= $deduct;
        }
    }

    echo "<script>alert('Successfully transferred $transfer_quantity seedlings to inventory.'); window.location.href='staff_sales_plot.php';</script>";
    exit();
}

//report
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_variety_report'])) {
    $seedling_variety_id = $_POST['seedling_variety_id_report'];
    $description = $_POST['damage_description'];
    $damaged_quantity = (int) $_POST['damaged_quantity'];
    $report_date = date("Y-m-d H:i:s");

    $image_path = null;
    if (isset($_FILES['damage_image']) && $_FILES['damage_image']['error'] === 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $filename = time() . "_" . basename($_FILES["damage_image"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["damage_image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        }
    }

    $plots_sql = $conn->prepare("SELECT plot_id, current_quantity FROM planting_plot WHERE seedling_variety_id = ? ORDER BY plot_id ASC");
    $plots_sql->bind_param("i", $seedling_variety_id);
    $plots_sql->execute();
    $plots_result = $plots_sql->get_result();

    $remaining = $damaged_quantity;

    while ($plot = $plots_result->fetch_assoc()) {
        if ($remaining <= 0) {
            break;
        }

        $deduct = min($remaining, $plot['current_quantity']);

        if ($deduct > 0) {
            $stmt = $conn->prepare("INSERT INTO inventory_reports (plot_id, report_date, damage_description, image_path, damaged_quantity) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isssi", $plot['plot_id'], $report_date, $description, $image_path, $deduct); 
            $stmt->execute();

            $update = $conn->prepare("UPDATE planting_plot SET current_quantity = current_quantity - ? WHERE plot_id = ?");
            $update->bind_param("ii", $deduct, $plot['plot_id']);
            $update->execute();

            $remaining -= $deduct;
        }
    }

    echo "<script>alert('Damage report submitted and quantity updated successfully.'); window.location.href='staff_sales_plot.php';</script>";
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>EJ's Plant Nursery</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css" />
    <style>
      .card {
        border-radius: 10px;
        padding: 15px;
        margin: 10px;
        background: #f9f9f9;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
      }
      input[type="number"]::-webkit-inner-spin-button,
      input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }
      input[type="number"] {
        -moz-appearance: textfield;
      }
    </style>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg border-bottom">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary">Plant Nursery</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                    <a class="nav-link active" href="staff_sales_plot.php">Plots</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="staff_sales_inventory.php">Inventory</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="scheduleDropdown" data-bs-toggle="dropdown">
                        Orders
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="scheduleDropdown">
                        <li><a class="dropdown-item" href="staff_sales_orders.php">Add Order</a></li>
                        <li><a class="dropdown-item" href="staff_sales_reserve_orders.php">Reserve Order</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="staff_sales_report.php">Reports</a></li>
            </ul>
        </div>
    </div>
</nav>

    <div class="container mt-4">
      <h2 class="text-center mb-4">Planting Plots</h2>

      <div class="row mb-4">
        <div class="col-md-6 mb-2">
          <input
            type="text"
            id="searchBar"
            class="form-control"
            placeholder="Search seed name..."
          />
        </div>
        <div class="col-md-4 mb-2">
          <select id="categoryFilter" class="form-select">
            <option value="">All Category</option>
            <?php while ($cat = $category_result->fetch_assoc()): ?>
              <option value="<?= $cat['category_name'] ?>">
                <?= $cat['category_name'] ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
      </div>

      <div class="row" id="seedlingList">
        <?php foreach ($seedlings as $row): ?>
          <?php
            $plots_sql = $conn->prepare("SELECT * FROM planting_plot WHERE seedling_variety_id = ?");
            $plots_sql->bind_param("i", $row['seedling_variety_id']);
            $plots_sql->execute();
            $plots_result = $plots_sql->get_result();
            $plots = [];
            $total_quantity = 0;
            while ($plot = $plots_result->fetch_assoc()) {
              $plots[] = $plot;
              $total_quantity += $plot['current_quantity'];
            }
          ?>

          <div
            class="col-md-4 seedling-item mb-4"
            data-name="<?= strtolower($row['seed_name']) ?>"
            data-category="<?= $row['category_name'] ?>"
          >
            <div class="card h-100">
              <h5>
                <?= $row['seed_name'] ?> - <?= $row['variety_name'] ?>
              </h5>
              <p><strong>Category:</strong> <?= $row['category_name'] ?></p>
              <p><strong>Total Planted:</strong> <?= $row['total_planted'] ?></p>

              <form method="POST">
                <input
                  type="hidden"
                  name="seedling_variety_id"
                  value="<?= $row['seedling_variety_id'] ?>"
                />

                <label class="form-label fw-bold">Insert Plots (max 50):</label>

                <div class="input-group mb-3 align-items-center">
                  <input
                    type="number"
                    name="plot_count"
                    min="1"
                    max="50"
                    required
                    class="form-control form-control-sm me-2"
                    style="max-width: 80px;"
                  />

                  <button type="submit" class="btn btn-success btn-sm me-2">
                    <i class="fas fa-plus-circle me-1"></i> Add
                  </button>

                  <button
                    type="button"
                    class="btn btn-primary btn-sm me-2"
                    data-bs-toggle="modal"
                    data-bs-target="#transferModal<?= $row['seedling_variety_id'] ?>"
                  >
                    <i class="fas fa-boxes me-1"></i> For Sale
                  </button>

                  <button
                    type="button"
                    class="btn btn-danger btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#reportModal<?= $row['seedling_variety_id'] ?>"
                  >
                    <i class="fas fa-exclamation-triangle me-1"></i> Report
                  </button>
                </div>
              </form>

              <div
                class="modal fade"
                id="transferModal<?= $row['seedling_variety_id'] ?>"
                tabindex="-1"
                aria-labelledby="transferModalLabel"
                aria-hidden="true"
              >
                <div class="modal-dialog">
                  <form method="POST" class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="transferModalLabel">
                        Transfer Seedlings to Inventory -
                        <?= $row['seed_name'] ?> (<?= $row['variety_name'] ?>)
                      </h5>
                      <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                      ></button>
                    </div>
                    <div class="modal-body">
                      <input
                        type="hidden"
                        name="seedling_variety_id_transfer"
                        value="<?= $row['seedling_variety_id'] ?>"
                      />
                      <div class="mb-3">
                        <label>Transfer Quantity</label>
                        <input
                          type="number"
                          name="transfer_quantity"
                          min="1"
                          max="<?= $row['total_planted'] ?>"
                          class="form-control"
                          required
                        />
                        <small class="form-text text-muted">
                          Maximum available quantity: <?= $row['total_planted'] ?>
                        </small>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">
                        Transfer
                      </button>
                      <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                      >
                        Close
                      </button>
                    </div>
                  </form>
                </div>
              </div>

              <div class="modal fade" id="reportModal<?= $row['seedling_variety_id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                  <form method="POST" enctype="multipart/form-data" class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">
                        Report Damage - <?= $row['seed_name'] ?> (<?= $row['variety_name'] ?>)
                      </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" name="seedling_variety_id_report" value="<?= $row['seedling_variety_id'] ?>" />
                      <div class="mb-3">
                        <label>Damage Description</label>
                        <textarea name="damage_description" class="form-control" required></textarea>
                      </div>
                      <div class="mb-3">
                        <label>Number of Damaged Seedlings (Max: <?= $total_quantity ?>)</label>
                        <input type="number" name="damaged_quantity" min="1" max="<?= $total_quantity ?>" class="form-control" required />
                      </div>
                      <div class="mb-3">
                        <label>Upload Image</label>
                        <input type="file" name="damage_image" class="form-control" />
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" name="submit_variety_report" class="btn btn-primary">
                        Submit Report
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
      document.getElementById("searchBar").addEventListener("keyup", function () {
        const val = this.value.toLowerCase();
        document.querySelectorAll(".seedling-item").forEach((item) => {
          const name = item.getAttribute("data-name");
          item.style.display = name.includes(val) ? "block" : "none";
        });
      });

      document
        .getElementById("categoryFilter")
        .addEventListener("change", function () {
          const selected = this.value;
          document.querySelectorAll(".seedling-item").forEach((item) => {
            const category = item.getAttribute("data-category");
            item.style.display = selected === "" || category === selected ? "block" : "none";
          });
        });
    </script>
  </body>
</html>
