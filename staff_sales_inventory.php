<?php 
include 'includes/db.php';
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
              <a class="nav-link active" href="staff_sales_inventory.php">Inventory</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="staff_sales_delivery.php">Delivery</a>
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
  <h2 class="text-center mb-4">Seedling Inventory</h2>

  <!-- Filters -->
  <div class="row mb-3">
    <div class="col-md-4">
      <input type="text" id="search" class="form-control" placeholder="Search seed name...">
    </div>
    <div class="col-md-3">
      <select id="category" class="form-select">
        <option value="all">All Categories</option>
        <?php
          $catRes = $conn->query("SELECT category_name FROM seedling_category");
          while ($cat = $catRes->fetch_assoc()):
        ?>
          <option value="<?= $cat['category_name'] ?>"><?= $cat['category_name'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-3">
      <select id="sort" class="form-select">
        <option value="all">Show All</option>
        <option value="low">Low Stock Only</option>
      </select>
    </div>
  </div>

  <div id="inventory-data">
  </div>
</div>
    <script>
function fetchInventory() {
  let search = $('#search').val();
  let category = $('#category').val();
  let sort = $('#sort').val();

  $.ajax({
    url: 'inventory.php',
    method: 'GET',
    data: { search: search, category: category, sort: sort },
    cache: false,
    success: function(data) {
      $('#inventory-data').html(data);
    }
  });
}

fetchInventory();

$('#search').on('input', fetchInventory);
$('#category, #sort').on('change', fetchInventory);
</script>
    <script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
