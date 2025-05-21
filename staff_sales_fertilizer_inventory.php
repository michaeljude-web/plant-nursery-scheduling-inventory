<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_quantity'])) {
    $fertilizer_id = $_POST['fertilizer_id'];
    $quantity = $_POST['quantity'];

    if (is_numeric($quantity) && $quantity > 0) {
        $quantity = (int)$quantity;
        $query = "INSERT INTO fertilizer_inventory (fertilizer_id, quantity) 
                  VALUES ('$fertilizer_id', '$quantity')";
        mysqli_query($conn, $query);
    }
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

$where = '';
if ($search !== '') {
    $where .= " AND f.fertilizer_name LIKE '%$search%'";
}
if ($category_filter !== '') {
    $where .= " AND f.category_id = '$category_filter'";
}

$sql = "SELECT f.id AS fertilizer_id, f.fertilizer_name, c.category_name,
        IFNULL(SUM(i.quantity), 0) AS total_quantity
        FROM seedling_fertilizer f
        LEFT JOIN fertilizer_category c ON f.category_id = c.category_id
        LEFT JOIN fertilizer_inventory i ON f.id = i.fertilizer_id
        WHERE 1=1 $where
        GROUP BY f.id
        ORDER BY f.fertilizer_name ASC";

$result = mysqli_query($conn, $sql);
$category_result = mysqli_query($conn, "SELECT * FROM fertilizer_category ORDER BY category_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>EJ's Plant Nursery</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css" />
    <script src="assets/jquery/jquery-3.6.0.min.js"></script>
    <style>
     
    .low-stock {
        background-color: #f8d7da !important;
    }

    .fertilizer-card {
        border-radius: 5px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid gray;
    }

    .filter-search-container {
        flex-wrap: wrap;
    }

    .badge {
        font-size: 0.75rem;
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
                    <a class="nav-link" href="staff_sales_plot.php">Plots</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="staff_sales_inventory.php">Inventory</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="staff_sales_fertilizer.php">Fertilizer</a>
                </li> -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="scheduleDropdown" data-bs-toggle="dropdown">
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

<div class="container mt-4 ">
<h2 class="mt-4"> <span><a href="staff_sales_inventory.php" class="text-decoration-none text-primary">Seedling Inventory</a></span> |
       Seedling Fertilizer
</h2> 
            <hr>

    <div class="container">

<div class="d-flex mb-4 filter-search-container">
  <div class="form-group me-2" style="flex: 0 0 auto; width: 250px;">
    <select id="categoryFilter" class="form-select">
      <option value="">All Categories</option>
      <?php 
      mysqli_data_seek($category_result, 0);
      while ($cat = mysqli_fetch_assoc($category_result)): ?>
        <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
      <?php endwhile; ?>
    </select>
  </div>
  <div class="form-group flex-grow-1">
    <input type="text" id="searchInput" class="form-control" placeholder="Search fertilizer..." />
  </div> &nbsp; &nbsp;

  <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addStockModal">
  <i class="fas fa-plus"></i> Add Stock
</button>
</div>

<div id="fertilizerContainer">
  <?php while ($row = mysqli_fetch_assoc($result)) : ?>
    <div class="fertilizer-card <?= ($row['total_quantity'] <= 5) ? 'low-stock' : '' ?>"
        data-name="<?= strtolower($row['fertilizer_name']) ?>" 
        data-category="<?= strtolower($row['category_name']) ?>" 
        data-quantity="<?= $row['total_quantity'] ?>">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-1"><?= htmlspecialchars($row['fertilizer_name']) ?></h5>
          <p class="mb-0 text-muted"><?= htmlspecialchars($row['category_name']) ?></p>
          <p class="mb-0">
            <strong>Stocks:</strong> <?= htmlspecialchars($row['total_quantity']) ?>
            <?php if ($row['total_quantity'] <= 5): ?>
              <span class="badge bg-danger ms-2">Low</span>
            <?php endif; ?>
          </p>
        </div>
      </div>
    </div>
  <?php endwhile; ?>
</div>
<!-- Stock Modal -->
<div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addStockModalLabel">Add Fertilizer Stock</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="fertilizer_id" class="form-label">Select Fertilizer</label>
          <select name="fertilizer_id" class="form-select" required>
            <option value="" disabled selected>Choose fertilizer</option>
            <?php 
              mysqli_data_seek($result, 0); 
              while ($row = mysqli_fetch_assoc($result)): ?>
              <option value="<?= $row['fertilizer_id'] ?>"><?= htmlspecialchars($row['fertilizer_name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="quantity" class="form-label">Quantity</label>
          <input type="number" name="quantity" class="form-control" min="1" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="add_quantity" class="btn btn-primary">Add Stock</button>
      </div>
    </form>
  </div>
</div>


<script>
$(document).ready(function() {
    function filterFertilizers() {
        var searchVal = $('#searchInput').val().toLowerCase();
        var selectedCategory = $('#categoryFilter option:selected').text().toLowerCase();

        $('.fertilizer-card').each(function() {
            var name = $(this).data('name');
            var category = $(this).data('category');

            var nameMatch = name.includes(searchVal);
            var categoryMatch = selectedCategory === 'all categories' || selectedCategory === '' || category === selectedCategory;

            if (nameMatch && categoryMatch) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    $('#searchInput').on('input', filterFertilizers);
    $('#categoryFilter').on('change', filterFertilizers);
});
</script>




<script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
