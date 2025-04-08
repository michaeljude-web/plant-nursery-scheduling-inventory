<?php
include 'includes/db.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['full_name'], $_POST['address'], $_POST['contact_number'], $_POST['quantity'], $_POST['seedling_variety_id'])) {
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $quantity = (int)$_POST['quantity'];
    $seedling_variety_id = (int)$_POST['seedling_variety_id'];

    $sql = "SELECT SUM(sii.quantity) AS available_quantity 
            FROM seedling_inventory sii
            WHERE sii.seedling_variety_id = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }
    $stmt->bind_param("i", $seedling_variety_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        $available_quantity = (int)$result['available_quantity'];
    } else {
        die('No result found for the given seedling variety.');
    }

    if ($quantity > $available_quantity) {
        echo "<script>alert('Not enough quantity available for this seedling.'); window.location.href='staff_sales_delivery.php';</script>";
        exit();
    }

    $customer_sql = "INSERT INTO customer_info (full_name, address, contact_number) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($customer_sql);
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }
    $stmt->bind_param("sss", $full_name, $address, $contact_number);
    $stmt->execute();
    $customer_id = $stmt->insert_id;

    $sale_sql = "INSERT INTO seedling_for_sale (seedling_variety_id, quantity, status, customer_id) VALUES (?, ?, 'Pending', ?)";
    $stmt = $conn->prepare($sale_sql);
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }
    $stmt->bind_param("iii", $seedling_variety_id, $quantity, $customer_id);

    if ($stmt->execute()) {
        $update_inventory_sql = "UPDATE seedling_inventory SET quantity = quantity - ? WHERE seedling_variety_id = ? LIMIT 1";
        $stmt = $conn->prepare($update_inventory_sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $conn->error);
        }
        $stmt->bind_param("ii", $quantity, $seedling_variety_id);
        $stmt->execute();

        echo "<script>alert('Order successfully placed.'); window.location.href='staff_sales_delivery.php';</script>";
        exit();
    } else {
        die('Failed to insert sale into seedling_for_sale: ' . $stmt->error);
    }
}

$sql = "SELECT 
            si.seed_name,
            sv.variety_name,
            sc.category_name,
            sv.price,
            SUM(sii.quantity) AS available_quantity,
            sv.id AS seedling_variety_id
        FROM seedling_inventory sii
        JOIN seedling_variety sv ON sii.seedling_variety_id = sv.id
        JOIN seedling_info si ON sv.seed_id = si.id
        JOIN seedling_category sc ON si.category_id = sc.id
        GROUP BY sv.id, si.seed_name, sv.variety_name, sc.category_name, sv.price";
$result = $conn->query($sql);

$seedlings = [];
while ($row = $result->fetch_assoc()) {
    $seedlings[] = $row;
}
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>EJ's Plant Nursery</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css" />
  </head>
  <body>
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
              <a class="nav-link" href="staff_sales_plot.php">Plots</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="staff_sales_inventory.php">Inventory</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="staff_sales_delivery.php">Delivery</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="staff_sales_report.php">Reports</a>
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


<div class="container mt-4">
    <h2 class="text-center mb-4">Seedling Delivery</h2>

        <h4>Available Seedlings</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Seed Name</th>
                    <th>Variety</th>
                    <th>Category</th>
                    <th>Price (per unit)</th>
                    <th>Available Quantity</th>
                    <th>Quantity to Order</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($seedlings as $seedling): ?>
                    <tr>
                        <td><?= htmlspecialchars($seedling['seed_name']) ?></td>
                        <td><?= htmlspecialchars($seedling['variety_name']) ?></td>
                        <td><?= htmlspecialchars($seedling['category_name']) ?></td>
                        <td>₱ <?= number_format($seedling['price'], 2) ?></td>
                        <td><?= $seedling['available_quantity'] ?></td>
                        <td>
                            <input type="number" name="quantity[<?= $seedling['seedling_variety_id'] ?>]" class="form-control" min="1" max="<?= $seedling['available_quantity'] ?>" value="1">
                        </td>
                        <td>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#customerModal" data-seedling="<?= $seedling['seedling_variety_id'] ?>" data-name="<?= htmlspecialchars($seedling['seed_name'] . ' ' . $seedling['variety_name']) ?>" data-price="<?= $seedling['price'] ?>">Add to Order</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalLabel">Confirm Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="staff_sales_delivery.php"> 
                <div class="modal-body">
                    <h5>Customer Information</h5>
                    <div class="mb-3">
                        <label for="modalFullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="modalFullName" name="full_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="modalAddress" class="form-label">Address</label>
                        <input type="text" class="form-control" id="modalAddress" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label for="modalContactNumber" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="modalContactNumber" name="contact_number" required>
                    </div>

                    <p><strong>Seedling:</strong> <span id="confirmSeedling"></span></p>
                    <p><strong>Quantity:</strong> <span id="confirmQuantity"></span></p>
                    <p><strong>Total Amount:</strong> ₱ <span id="confirmTotalAmount"></span></p>

                    <input type="hidden" id="hiddenSeedlingId" name="seedling_variety_id">
                    <input type="hidden" id="hiddenQuantity" name="quantity">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Confirm Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script>
document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
    button.addEventListener('click', function() {
        const seedlingId = this.getAttribute('data-seedling');
        const seedlingName = this.getAttribute('data-name');
        const seedlingPrice = parseFloat(this.getAttribute('data-price'));
        const availableQuantity = parseInt(this.getAttribute('data-available')); 
        const quantity = document.querySelector(`input[name="quantity[${seedlingId}]"]`).value;

        if (parseInt(quantity) > availableQuantity) {
            alert('Not enough quantity available for this seedling.');
            return false; 
        }

        const totalAmount = (seedlingPrice * quantity).toFixed(2);

        document.getElementById('confirmSeedling').textContent = seedlingName;
        document.getElementById('confirmQuantity').textContent = quantity;
        document.getElementById('confirmTotalAmount').textContent = totalAmount;

        document.getElementById('hiddenSeedlingId').value = seedlingId;
        document.getElementById('hiddenQuantity').value = quantity;
        document.getElementById('hiddenAvailableQuantity').value = availableQuantity; 
    });
});
</script>
    <script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
