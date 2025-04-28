<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deduct_quantity'])) {
    $fertilizer_id = $_POST['fertilizer_id'];
    $quantity = $_POST['quantity'];
    $image_file = $_FILES['image']; 
    $employee_id = 1; 

    if (is_numeric($quantity) && $quantity > 0) {
        $quantity = (int)$quantity;

        $check_stock_query = "SELECT IFNULL(SUM(quantity), 0) AS total_quantity 
                              FROM fertilizer_inventory 
                              WHERE fertilizer_id = '$fertilizer_id'";
        $check_result = mysqli_query($conn, $check_stock_query);
        $check_row = mysqli_fetch_assoc($check_result);
        $current_quantity = $check_row['total_quantity'];

        if ($current_quantity >= $quantity) {
            $new_quantity = $current_quantity - $quantity;

            $update_query = "UPDATE fertilizer_inventory 
                             SET quantity = '$new_quantity' 
                             WHERE fertilizer_id = '$fertilizer_id'";

            if (mysqli_query($conn, $update_query)) {
                $image_path = null;
                if (!empty($image_file['name'])) {
                    $image_temp = $image_file['tmp_name'];
                    $image_path = 'uploads/' . basename($image_file['name']);
                    if (move_uploaded_file($image_temp, $image_path)) {
                    }
                }

                $deduction_query = "INSERT INTO fertilizer_deductions (fertilizer_id, quantity, image_path, date_deducted, employee_id) 
                                    VALUES ('$fertilizer_id', '$quantity', '$image_path', NOW(), '$employee_id')";
                
                if (mysqli_query($conn, $deduction_query)) {
                    echo "<script>alert('Successfully deducted $quantity from the stock!'); window.location.href = window.location.href;</script>";
                    exit;
                } else {
                    echo "<script>alert('Error recording deduction.'); window.history.back();</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Database error during deduction.'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Insufficient stock to deduct!'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('Please enter a valid quantity!'); window.history.back();</script>";
        exit;
    }
}

$category_result = mysqli_query($conn, "SELECT * FROM fertilizer_category");
?>
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
          <style>
               .filter-search-container {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 15px;
               }
               .table-white th,
               .table-white td {
                    background-color: #fff;
                    vertical-align: middle;
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
                                   <a class="nav-link" href="staff_nutritionist_calendar.php">Calendar</a>
                              </li>
                              <li class="nav-item dropdown">
                                   <a class="nav-link dropdown-toggle" href="#" id="scheduleDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Schedule
                                   </a>
                                   <ul class="dropdown-menu" aria-labelledby="scheduleDropdown">
                                        <li><a class="dropdown-item" href="staff_nutritionist_add_schedule.php">Add Schedule</a></li>
                                        <li><a class="dropdown-item" href="staff_nutritionist_schedule_history.php">Schedule History</a></li>
                                   </ul>
                              </li>
                              <li class="nav-item">
                                   <a class="nav-link" href="staff_nutritionist_fertilizer_inventory.php">Inventory</a>
                              </li>
                              <li class="nav-item">
                                   <a class="nav-link active" href="staff_nutritionist_fertilizer_deduction.php">Deduction</a>
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
               <h2 class="mb-4">Fertilizer to Deduction</h2>
               <hr>
               <div class="filter-search-container">
                    <div class="form-group me-2">
                         <select id="categoryFilter" class="form-select">
                              <option value="">All Categories</option>
                              <?php while ($cat = mysqli_fetch_assoc($category_result)): ?>
                              <option value="<?= $cat['category_name']; ?>"><?= $cat['category_name']; ?></option>
                              <?php endwhile; ?>
                         </select>
                    </div>
                    <div class="form-group flex-grow-1">
                         <input type="text" id="searchInput" class="form-control" placeholder="Search fertilizer name..." />
                    </div>
               </div>

               <table class="table table-bordered table-white table-white">
                    <thead class="table-light">
                         <tr>
                              <th>Fertilizer Name</th>
                              <th>Category</th>
                              <th>Total Quantity</th>
                              <th>Action</th>
                         </tr>
                    </thead>
                    <tbody id="fertilizerTable">
                         <?php
        $fertilizer_query = "
            SELECT f.id AS fertilizer_id, f.fertilizer_name, c.category_name, 
                   IFNULL(SUM(i.quantity), 0) AS total_quantity
            FROM seedling_fertilizer f
            LEFT JOIN fertilizer_inventory i ON f.id = i.fertilizer_id
            LEFT JOIN fertilizer_category c ON f.category_id = c.category_id
            GROUP BY f.id, f.fertilizer_name, c.category_name
        ";
        $result = mysqli_query($conn, $fertilizer_query);
        while ($row = mysqli_fetch_assoc($result)): ?>
                         <tr>
                              <td class="fertilizer-name"><?= $row['fertilizer_name']; ?></td>
                              <td class="fertilizer-category"><?= $row['category_name']; ?></td>
                              <td><?= $row['total_quantity']; ?></td>
                              <td>
                                   <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deductModal<?= $row['fertilizer_id']; ?>">
                                   <i class="fas fa-minus-circle"></i> Deduct
                                   </button>
                              </td>
                         </tr>

                         <div class="modal fade" id="deductModal<?= $row['fertilizer_id']; ?>" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog">
                                   <div class="modal-content">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                             <div class="modal-header">
                                                  <h5 class="modal-title">
                                                       Deduct Quantity -
                                                       <?= $row['fertilizer_name']; ?>
                                                  </h5>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                             </div>
                                             <div class="modal-body">
                                                  <input type="hidden" name="fertilizer_id" value="<?= $row['fertilizer_id']; ?>" />
                                                  <div class="mb-3">
                                                       <label class="form-label">Quantity to Deduct</label>
                                                       <input type="number" name="quantity" class="form-control" required />
                                                  </div>
                                                  <div class="mb-3">
                                                       <label class="form-label">Upload Image (optional)</label>
                                                       <input type="file" name="image" class="form-control" />
                                                  </div>
                                             </div>
                                             <div class="modal-footer">
                                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                  <button type="submit" name="deduct_quantity" class="btn btn-danger"><i class="fas fa-minus-circle"></i>
                                                  Deduct</button>
                                             </div>
                                        </form>
                                   </div>
                              </div>
                         </div>

                         <?php endwhile; ?>
                    </tbody>
               </table>
          </div>
          <script>
               document.getElementById("searchInput").addEventListener("keyup", filterTable);
               document.getElementById("categoryFilter").addEventListener("change", filterTable);

               function filterTable() {
                    const search = document.getElementById("searchInput").value.toLowerCase();
                    const category = document.getElementById("categoryFilter").value;
                    const rows = document.querySelectorAll("#fertilizerTable tr");

                    rows.forEach((row) => {
                         const name = row.querySelector(".fertilizer-name").textContent.toLowerCase();
                         const cat = row.querySelector(".fertilizer-category").textContent;

                         const matchName = name.includes(search);
                         const matchCat = !category || cat === category;

                         row.style.display = matchName && matchCat ? "" : "none";
                    });
               }
          </script>

          <script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
     </body>
</html>
