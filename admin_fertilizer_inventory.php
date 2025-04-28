<?php
include 'includes/db.php';

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Fertilizers</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <script src="assets/jquery/jquery-3.6.0.min.js"></script>
    <style>
        .low-stock {
            background-color: #f8d7da; 
        }
    </style>
</head>
<body>

<section class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2 class="mt-4">Fertilizer Inventory</h2> <hr>
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
       </div>
    </div>

    <div id="fertilizerContainer">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <div class="card mb-3 fertilizer-card <?= ($row['total_quantity'] <= 5) ? 'low-stock' : '' ?>" 
                 data-name="<?= strtolower($row['fertilizer_name']) ?>" 
                 data-category="<?= strtolower($row['category_name']) ?>" 
                 data-quantity="<?= $row['total_quantity'] ?>">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h5 class="mb-1"><?= htmlspecialchars($row['fertilizer_name']) ?></h5>
                        <small class="text-muted"><?= htmlspecialchars($row['category_name']) ?></small><br>
                        <strong class="stock-quantity" id="stock-<?= $row['fertilizer_id'] ?>" data-stock="<?= $row['total_quantity'] ?>">
                            Stocks: <?= htmlspecialchars($row['total_quantity']) ?>
                        </strong>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

        </main>
    </div>
</section>

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
