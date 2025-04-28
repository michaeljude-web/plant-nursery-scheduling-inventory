<?php
include 'includes/db.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['full_name'], $_POST['address'], $_POST['contact_number'], $_POST['selected'], $_POST['submit_order'])) {
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $submit_order = $_POST['submit_order']; 

    $status = ($submit_order == 'reserve') ? 'Reserve' : 'Pending'; 


    $customer_sql = "INSERT INTO customer_info (full_name, address, contact_number) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($customer_sql);
    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }
    $stmt->bind_param("sss", $full_name, $address, $contact_number);
    $stmt->execute();
    $customer_id = $stmt->insert_id;

    foreach ($_POST['selected'] as $seedling_variety_id) {
        $seedling_variety_id = (int)$seedling_variety_id;
        $quantity = isset($_POST['quantity'][$seedling_variety_id]) ? (int)$_POST['quantity'][$seedling_variety_id] : 0;
        if ($quantity <= 0) {
            continue;
        }

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
            echo "<script>alert('Not enough quantity available for seedling (ID: {$seedling_variety_id}).'); window.location.href='staff_sales_orders.php';</script>";
            exit();
        }

        $sale_sql = "INSERT INTO seedling_for_sale (seedling_variety_id, quantity, status, customer_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sale_sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $conn->error);
        }
        $stmt->bind_param("iisi", $seedling_variety_id, $quantity, $status, $customer_id);
        if (!$stmt->execute()) {
            die('Failed to insert sale: ' . $stmt->error);
        }

        $update_inventory_sql = "UPDATE seedling_inventory SET quantity = quantity - ? WHERE seedling_variety_id = ? LIMIT 1";
        $stmt = $conn->prepare($update_inventory_sql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . $conn->error);
        }
        $stmt->bind_param("ii", $quantity, $seedling_variety_id);
        $stmt->execute();
    }

    $message = ($submit_order == 'reserve') ? 'Reservation successfully placed.' : 'Order successfully placed.';
echo "<script>alert('$message'); window.location.href='staff_sales_orders.php';</script>";
exit();

    // echo "<script>alert('Order successfully placed.'); window.location.href='staff_sales_delivery.php';</script>";
    exit();
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
        GROUP BY sv.id, si.seed_name, sv.variety_name, sc.category_name, sv.price
        HAVING available_quantity > 0";

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
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" id="scheduleDropdown" data-bs-toggle="dropdown">
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
    <h2 class="text-center mb-4">Seedling Delivery</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Total: ₱ <span id="totalAmount">0.00</span></h4>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#customerModal" id="orderButton" disabled>
            <i class="fas fa-cart-plus"></i> Order
        </button>
    </div>

    <form method="POST" action="staff_sales_orders.php" id="orderForm">
        <div class="table-responsive">
            <table class="table table-bordered" id="seedlingTable">
                <thead>
                    <tr>
                        <th class="text-center">Select</th>
                        <th>Seed Name</th>
                        <th>Variety</th>
                        <th>Category</th>
                        <th>Price (per seedling)</th>
                        <th class="text-center">Available</th>
                        <th>Quantity to Order</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($seedlings as $seedling): ?>
                        <tr data-price="<?= $seedling['price'] ?>">
                            <td class="text-center">
                                <input type="checkbox" name="selected[]" value="<?= $seedling['seedling_variety_id'] ?>" class="select-seedling">
                            </td>
                            <td><?= htmlspecialchars($seedling['seed_name']) ?></td>
                            <td><?= htmlspecialchars($seedling['variety_name']) ?></td>
                            <td><?= htmlspecialchars($seedling['category_name']) ?></td>
                            <td>₱ <?= number_format($seedling['price'], 2) ?></td>
                            <td class="text-center"><?= $seedling['available_quantity'] ?></td>
                            <td>
                                <input type="number" name="quantity[<?= $seedling['seedling_variety_id'] ?>]" class="form-control quantity-input" min="1" max="<?= $seedling['available_quantity'] ?>" value="1">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="customerModalLabel">Customer Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
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
                    </div>
                    <div class="modal-footer">
                    <button type="submit" name="submit_order" value="reserve" class="btn btn-warning">
                      <i class="fa fa-clipboard"></i> Reserve Order
                    </button>

                    <button type="submit" name="submit_order" value="order" class="btn btn-primary">
                      <i class="fa fa-shopping-cart"></i> Submit Order
                    </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function updateTotal() {
        let total = 0;
        let hasSelected = false;

        document.querySelectorAll('#seedlingTable tbody tr').forEach(row => {
            const checkbox = row.querySelector('.select-seedling');
            const price = parseFloat(row.getAttribute('data-price'));
            const qtyInput = row.querySelector('.quantity-input');
            const quantity = parseFloat(qtyInput.value);

            if (checkbox && checkbox.checked) {
                total += price * quantity;
                hasSelected = true;
            }
        });

        document.getElementById('totalAmount').textContent = total.toFixed(2);
        document.getElementById('orderButton').disabled = !hasSelected;
    }

    document.querySelectorAll('.select-seedling, .quantity-input').forEach(el => {
        el.addEventListener('change', updateTotal);
        el.addEventListener('input', updateTotal);
    });
</script>

<script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
