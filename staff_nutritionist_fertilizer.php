<?php
include 'includes/db.php';

if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    mysqli_query($conn, "INSERT INTO fertilizer_category (category_name) VALUES ('$category_name')");
}

if (isset($_POST['add_fertilizer'])) {
    $fertilizer_name = $_POST['fertilizer_name'];
    $category_id = $_POST['category_id'];
    mysqli_query($conn, "INSERT INTO seedling_fertilizer (fertilizer_name, category_id) VALUES ('$fertilizer_name', $category_id)");
}



if (isset($_GET['delete_fertilizer'])) {
    $fertilizer_id = $_GET['delete_fertilizer'];
    mysqli_query($conn, "DELETE FROM seedling_fertilizer WHERE id = $fertilizer_id");

}

$filter_category = isset($_POST['filter_category']) ? $_POST['filter_category'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>EJ's Plant Nursery</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css" />
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
                        <a class="nav-link active" href="staff_nutritionist_fertilizer.php">Fertilizer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="staff_nutritionist_fertilizer_deduction.php">Deduction</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="staff_nutritionist_fertilizer_inventory.php">Inventory</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="staff_nutritionist_inventory.php">Inventory</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="staff_login.php">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
    <h2 class="text-center mb-4">Seedling Fertilizers</h2>

<div class="row align-items-center mb-4 g-12">
    <div class="col-12 col-md-6 col-lg-4">
        <input type="text" id="searchFertilizer" class="form-control" placeholder="Search fertilizer..." onkeyup="searchFertilizers()">
    </div>

    <div class="col-12 col-md-6 col-lg-4">
        <form method="POST">
            <select name="filter_category" class="form-select" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php
                $categories = mysqli_query($conn, "SELECT * FROM fertilizer_category ORDER BY category_name ASC");
                while ($cat = mysqli_fetch_assoc($categories)) {
                    $selected = ($filter_category == $cat['category_id']) ? 'selected' : '';
                    echo "<option value='{$cat['category_id']}' $selected>{$cat['category_name']}</option>";
                }
                ?>
            </select>
        </form>
    </div>

    <div class="col-12 col-lg-4 text-md-end">
        <button class="btn btn-primary me-2 mb-2" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="fas fa-folder-plus me-1"></i> Category
        </button>
        <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#addFertilizerModal">
            <i class="fas fa-plus-circle me-1"></i> Fertilizer
        </button>
    </div>
</div>

<div>
    <h5 class="mb-3">Fertilizer List</h5>
    <div id="fertilizerList">
    <?php
    $fertilizer_query = "SELECT * FROM seedling_fertilizer";
    if ($filter_category) {
        $fertilizer_query .= " WHERE category_id = $filter_category";
    }
    $fertilizers = mysqli_query($conn, $fertilizer_query);
    while ($f = mysqli_fetch_assoc($fertilizers)) {
        $cat_query = mysqli_query($conn, "SELECT category_name FROM fertilizer_category WHERE category_id = " . $f['category_id']);
        $category = mysqli_fetch_assoc($cat_query)['category_name'];
        echo '
        <div class="d-flex justify-content-between align-items-center bg-white border rounded p-2 mb-2 fertilizer-item flex-wrap">
            <div>
                <strong class="fertilizer-name">'.htmlspecialchars($f['fertilizer_name']).'</strong>
                <div class="text-muted small">'.$category.'</div>
            </div>
            <a href="?delete_fertilizer='.$f['id'].'" class="btn btn-sm btn-danger mt-2 mt-md-0" onclick="return confirm(\'Are you sure you want to delete this fertilizer?\')">
                <i class="fas fa-trash me-1"></i> Remove
            </a>
        </div>';
    }
    ?>
    </div>
</div>

</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog">
    <form method="POST" class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Add Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <input type="text" name="category_name" class="form-control" placeholder="Category name" required>
        </div>
        <div class="modal-footer">
            <button type="submit" name="add_category" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
</div>

<div class="modal fade" id="addFertilizerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Fertilizer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="fertilizer_name" class="form-control mb-2" placeholder="Fertilizer name" required>
                <select name="category_id" class="form-select mb-2" required>
                    <option value="" disabled selected>Select Category</option>
                    <?php
                    $cats = mysqli_query($conn, "SELECT * FROM fertilizer_category ORDER BY category_name ASC");
                    while ($row = mysqli_fetch_assoc($cats)) {
                        echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="modal-footer">
                <button type="submit" name="add_fertilizer" class="btn btn-success">Save</button>
            </div>
        </form>
    </div>
</div>



<script>
function searchFertilizers() {
    let input = document.getElementById('searchFertilizer').value.toLowerCase();
    let items = document.querySelectorAll('.fertilizer-item');
    items.forEach(item => {
        let name = item.querySelector('.fertilizer-name').textContent.toLowerCase();
        if (name.includes(input)) {
            item.classList.remove('d-none');
        } else {
            item.classList.add('d-none');
        }
    });
}
</script>

<script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
