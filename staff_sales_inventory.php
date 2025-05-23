<?php 
include 'includes/db.php';

$search = $_GET['search'] ?? '';
$categoryFilter = $_GET['category'] ?? 'all';
$sort = $_GET['sort'] ?? 'all';

function getInventory($conn, $search, $categoryFilter, $sort) {
    $sql = "SELECT 
        si.seed_name,
        sv.variety_name,
        sc.category_name,
        SUM(sii.quantity) AS total_quantity,
        sv.price
    FROM seedling_inventory sii
    JOIN seedling_variety sv ON sii.seedling_variety_id = sv.id
    JOIN seedling_info si ON sv.seed_id = si.id
    JOIN seedling_category sc ON si.category_id = sc.id
    WHERE 1";

    if (!empty($search)) {
        $safeSearch = $conn->real_escape_string($search);
        $sql .= " AND si.seed_name LIKE '%$safeSearch%'";
    }

    if ($categoryFilter !== 'all') {
        $safeCat = $conn->real_escape_string($categoryFilter);
        $sql .= " AND sc.category_name = '$safeCat'";
    }

    $sql .= " GROUP BY sv.id, si.seed_name, sv.variety_name, sc.category_name, sv.price";

    if ($sort === 'low') {
        $sql .= " HAVING total_quantity <= 15 ORDER BY total_quantity ASC";
    } else {
        $sql .= " HAVING total_quantity > 0 ORDER BY si.seed_name ASC";
    }

    $result = $conn->query($sql);

    if (!$result) {
        die("Error executing query: " . $conn->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}
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
        <li class="nav-item">
          <!-- <a class="nav-link" href="staff_sales_report.php">Reports</a> -->
          <li class="nav-item"><a class="nav-link" href="staff_login.php"><i class="fas fa-sign-out-alt"></i></a></li>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
      <h2 class="mt-4">Seedling Inventory |
        <span><a href="staff_sales_fertilizer_inventory.php" class="text-decoration-none text-primary">Fertilizer Inventory</a></span></h2> 
            <hr>

  <!-- Filters -->
  <div class="row mb-3">
    <div class="col-md-4">
      <input type="text" id="search" class="form-control" placeholder="Search seed name..." value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="col-md-3">
      <select id="category" class="form-select">
        <option value="all">All Categories</option>
        <?php
          $catRes = $conn->query("SELECT category_name FROM seedling_category");
          while ($cat = $catRes->fetch_assoc()):
        ?>
          <option value="<?= $cat['category_name'] ?>" <?= $cat['category_name'] == $categoryFilter ? 'selected' : '' ?>>
            <?= $cat['category_name'] ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-3">
      <select id="sort" class="form-select">
        <option value="all" <?= $sort === 'all' ? 'selected' : '' ?>>Show All</option>
        <option value="low" <?= $sort === 'low' ? 'selected' : '' ?>>Low Stock Only</option>
      </select>
    </div>
  </div>

  <div id="inventory-data">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Seed Name</th>
          <th>Variety</th>
          <th>Category</th>
          <th>Total Quantity</th>
          <th>Price</th>
          <th>Total Value</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          $inventory = getInventory($conn, $search, $categoryFilter, $sort);
          if (empty($inventory)): 
        ?>
          <tr>
            <td colspan="6" class="text-center">No records found.</td>
          </tr>
        <?php 
          else:
            foreach ($inventory as $item): 
              $low_stock = $item['total_quantity'] <= 6;
              $row_class = $low_stock ? 'table-danger' : '';
        ?>
          <tr class="<?= $row_class ?>">
            <td><?= htmlspecialchars($item['seed_name']) ?></td>
            <td><?= htmlspecialchars($item['variety_name']) ?></td>
            <td><?= htmlspecialchars($item['category_name']) ?></td>
            <td>
              <?= $item['total_quantity'] ?>
              <?php if ($low_stock): ?>
                <span class="badge bg-danger ms-2">Low</span>
              <?php endif; ?>
            </td>
            <td>&#8369;<?= number_format($item['price'], 2) ?></td>
            <td>&#8369;<?= number_format($item['total_quantity'] * $item['price'], 2) ?></td>
          </tr>
        <?php 
            endforeach;
          endif; 
        ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  function fetchInventory() {
    let search = $('#search').val();
    let category = $('#category').val();
    let sort = $('#sort').val();

    $.get(window.location.href.split('?')[0], {
      search: search,
      category: category,
      sort: sort
    }, function (data) {
      const html = $(data).find('#inventory-data').html();
      $('#inventory-data').html(html);
    });
  }

  $('#search').on('input', function () {
    clearTimeout(this.delay);
    this.delay = setTimeout(fetchInventory, 300);
  });

  $('#category, #sort').on('change', fetchInventory);
</script>

<script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>

