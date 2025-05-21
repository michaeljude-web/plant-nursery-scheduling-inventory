<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_quantity'])) {
    $fertilizer_id = $_POST['fertilizer_id'];
    $quantity = $_POST['quantity'];

    if (is_numeric($quantity) && $quantity > 0) {
        $quantity = (int)$quantity;
        $date_added = date('Y-m-d H:i:s');

        $query = "INSERT INTO fertilizer_inventory (fertilizer_id, quantity, date_added) 
                  VALUES ('$fertilizer_id', '$quantity', '$date_added')";
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

$sql = "SELECT f.id AS fertilizer_id, f.fertilizer_name, c.category_name
        FROM seedling_fertilizer f
        LEFT JOIN fertilizer_category c ON f.category_id = c.category_id
        WHERE 1=1 $where
        ORDER BY f.fertilizer_name ASC";

$result = mysqli_query($conn, $sql);
$category_result = mysqli_query($conn, "SELECT * FROM fertilizer_category ORDER BY category_name ASC");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add Fertilizer Stock</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css" />
    <script src="assets/jquery/jquery-3.6.0.min.js"></script>
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
                    <a class="nav-link" href="staff_sales_inventory.php">Inventory</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link active" href="staff_sales_fertilizer.php">Fertilizer</a>
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

<div class="container mt-4">
    <h3>Add Fertilizer</h3> <hr>

    <div class="row mb-3">
        <div class="col-md-4">
            <select id="categoryFilter" class="form-select">
                <option value="">All Categories</option>
                <?php while ($cat = mysqli_fetch_assoc($category_result)): ?>
                    <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-8">
            <input type="text" id="searchInput" class="form-control" placeholder="Search fertilizer...">
        </div>
    </div>

    <div id="fertilizerContainer">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="card mb-3 fertilizer-card" 
                 data-name="<?= strtolower($row['fertilizer_name']) ?>" 
                 data-category="<?= strtolower($row['category_name']) ?>">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h5 class="mb-1"><?= htmlspecialchars($row['fertilizer_name']) ?></h5>
                        <small class="text-muted"><?= htmlspecialchars($row['category_name']) ?></small>
                    </div>
                    <form method="POST" class="d-flex align-items-center gap-2 add-stock-form">
                        <input type="hidden" name="fertilizer_id" value="<?= $row['fertilizer_id'] ?>">
                        <input type="number" name="quantity" min="1" required class="form-control" style="width: 100px;">
                        <button type="submit" name="add_quantity" class="btn btn-success">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
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

            $(this).toggle(nameMatch && categoryMatch);
        });
    }

    $('#searchInput').on('input', filterFertilizers);
    $('#categoryFilter').on('change', filterFertilizers);

    $('.add-stock-form').submit(function(e) {
        var confirmAdd = confirm('Are you sure you want to add this quantity?');
        if (!confirmAdd) {
            e.preventDefault(); 
        }
    });
});
</script>

<script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
