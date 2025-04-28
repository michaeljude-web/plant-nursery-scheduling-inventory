<?php
include 'includes/db.php';

$categoryQuery = "SELECT * FROM seedling_category";
$categoryResult = $conn->query($categoryQuery);

$seedQuery = "SELECT * FROM seedling_info";
$seedResult = $conn->query($seedQuery);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $category_name = trim($_POST['category_name']);
    if (!empty($category_name)) {
        $stmt = $conn->prepare("INSERT INTO seedling_category (category_name) VALUES (?)");
        $stmt->bind_param("s", $category_name);
        if ($stmt->execute()) {
            echo "<script>alert('Category Added!');</script>";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_seedname'])) {
    $seed_name = trim($_POST['seed_name']);
    $category_id = $_POST['category'];
    if (!empty($seed_name) && !empty($category_id)) {
        $stmt = $conn->prepare("INSERT INTO seedling_info (category_id, seed_name) VALUES (?, ?)");
        $stmt->bind_param("is", $category_id, $seed_name);
        if ($stmt->execute()) {
            echo "<script>alert('Seed Name Added!');</script>";
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_seed'])) {
    $category_id = $_POST['category'];
    $seed_id = $_POST['seed_name'];
    $variety_name = trim($_POST['variety_name']);
    $price = $_POST['price'];

    if (!empty($category_id) && !empty($seed_id) && !empty($variety_name) && !empty($price)) {
        $stmt = $conn->prepare("INSERT INTO seedling_variety (seed_id, variety_name, price) VALUES (?, ?, ?)");

        $stmt->bind_param("isd", $seed_id, $variety_name, $price);
        if ($stmt->execute()) {
            echo "<script>alert('Seed & Variety Added!'); window.location.href='admin_seeds_add.php';</script>";
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Admin | Seedling</title>
        <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css" />
        <link rel="stylesheet" href="assets/css/style.css?v=2" />
    </head>
    <body>
        <section class="container-fluid">
            <div class="row">
                <?php include 'includes/sidebar.php'; ?>

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <h2 class="mt-4">Add Seeds</h2> <hr>
                    <form method="POST">
                        <div class="d-flex gap-2">
                            <div class="w-50">
                                <label>Category:</label>
                                <select name="category" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <?php while ($row = $categoryResult->fetch_assoc()) { ?>
                                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['category_name']) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="w-50 d-flex align-items-end">
                                <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#addCategoryModal">+ Add Category</button>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <div class="w-50">
                                <label>Seed Name:</label>
                                <select name="seed_name" class="form-control" required>
                                    <option value="">Select Seed Name</option>
                                    <?php while ($row = $seedResult->fetch_assoc()) { ?>
                                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['seed_name']) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="w-50 d-flex align-items-end">
                                <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#addSeedModal">+ Add Seed Name</button>
                            </div>
                        </div>

                        <label>Variety Name:</label>
                        <input type="text" name="variety_name" class="form-control" required />

                        <label>Price:</label>
                        <input type="number" name="price" step="0.01" class="form-control" required />

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" name="add_seed" class="btn btn-primary w-100">Add Seed</button>
                        </div>
                    </form>

                    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <input type="text" name="category_name" class="form-control" placeholder="Enter Category Name" required />
                                        <button type="submit" name="add_category" class="btn btn-success mt-2">Add</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="addSeedModal" tabindex="-1" aria-labelledby="addSeedModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addSeedModalLabel">Add Seed Name</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <input type="hidden" name="category" id="selected_category" />

                                        <input type="text" name="seed_name" class="form-control" placeholder="Enter Seed Name" required />
                                        <button type="submit" name="add_seedname" class="btn btn-primary mt-2">Add</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </section>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
    const categorySelect = document.querySelector("select[name='category']");
    const seedSelect = document.querySelector("select[name='seed_name']");
    const selectedCategory = document.getElementById("selected_category");


    categorySelect.addEventListener("change", function () {
        selectedCategory.value = this.value;
    });

    function updateCategoryDropdown(newCategoryId, newCategoryName) {
        let option = document.createElement("option");
        option.value = newCategoryId;
        option.textContent = newCategoryName;
        categorySelect.appendChild(option);
        categorySelect.value = newCategoryId;
        selectedCategory.value = newCategoryId;
    }

    function updateSeedDropdown(newSeedId, newSeedName) {
        let option = document.createElement("option");
        option.value = newSeedId;
        option.textContent = newSeedName;
        seedSelect.appendChild(option);
        seedSelect.value = newSeedId;
    }
});

        </script>
        <script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
    </body>
</html>