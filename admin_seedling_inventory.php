<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Inventory</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <script src="assets/jquery/jquery-3.6.0.min.js"></script>
</head>
<body>

<section class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2 class="mt-4">Seedling Inventory</h2> <hr><br>

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

            <div id="inventory-data"></div>
        </main>
    </div>
</section>

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
