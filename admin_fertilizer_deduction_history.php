<?php
include 'includes/db.php';

$category_query = "SELECT category_id, category_name FROM fertilizer_category ORDER BY category_name";
$category_result = mysqli_query($conn, $category_query);

$selected_category = isset($_GET['category']) ? $_GET['category'] : '';

$query = "
    SELECT 
        d.deduction_id, 
        f.fertilizer_name, 
        c.category_name, 
        d.quantity, 
        d.date_deducted, 
        d.image_path
    FROM fertilizer_deductions d
    JOIN seedling_fertilizer f ON d.fertilizer_id = f.id
    LEFT JOIN fertilizer_category c ON f.category_id = c.category_id
";

if (!empty($selected_category)) {
    $query .= " WHERE c.category_id = " . intval($selected_category);
}

$query .= " ORDER BY d.date_deducted DESC";

$result = mysqli_query($conn, $query);
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
</head>
<body>

<section class="container-fluid">
    <div class="row">

        <?php include 'includes/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2 class="mt-4">Fertilizer Deduction History</h2>
            <hr>

            <form method="GET" class="mb-4">
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <label for="category" class="form-label mb-0">Select Category:</label>
                    </div>
                    <div class="col-auto">
                        <select name="category" id="category" class="form-select" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            <?php while ($cat = mysqli_fetch_assoc($category_result)): ?>
                                <option value="<?= $cat['category_id']; ?>" <?= ($selected_category == $cat['category_id']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($cat['category_name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
            </form>

            <ul class="list-group">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong><?= $row['fertilizer_name']; ?> (<?= $row['category_name'] ?? 'Uncategorized'; ?>)</strong><br>
                            <small>Date Deducted: <?= $row['date_deducted']; ?></small><br>
                            <small>Quantity Deducted: <?= $row['quantity']; ?></small>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#historyModal<?= $row['deduction_id']; ?>">
                            <i class="fas fa-eye"></i>  
                        </button>
                    </li>

                    <!-- Modal -->
                    <div class="modal fade" id="historyModal<?= $row['deduction_id']; ?>" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Fertilizer Deduction Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Fertilizer Name:</strong> <?= $row['fertilizer_name']; ?></p>
                                    <p><strong>Category:</strong> <?= $row['category_name'] ?? 'Uncategorized'; ?></p>
                                    <p><strong>Quantity:</strong> <?= $row['quantity']; ?></p>
                                    <p><strong>Date:</strong> <?= $row['date_deducted']; ?></p>
                                    <?php if (!empty($row['image_path'])): ?>
                                        <p><strong>Proof Image:</strong><br>
                                            <img src="<?= $row['image_path']; ?>" class="img-fluid" style="max-height: 300px;">
                                        </p>
                                    <?php else: ?>
                                        <p><em>No image available.</em></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </ul>
        </main>
    </div>
</section>

<script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
