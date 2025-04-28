<?php
include 'includes/db.php';

$category_query = "SELECT * FROM seedling_category";
$category_result = mysqli_query($conn, $category_query);

$seedling_query = "
    SELECT sv.id, sv.variety_name, si.seed_name, sc.category_name
    FROM seedling_variety sv
    JOIN seedling_info si ON sv.seed_id = si.id
    LEFT JOIN seedling_category sc ON si.category_id = sc.id
";
$seedling_result = mysqli_query($conn, $seedling_query);

$fertilizer_query = "
    SELECT f.id, f.fertilizer_name, i.quantity
    FROM seedling_fertilizer f
    JOIN fertilizer_inventory i ON f.id = i.fertilizer_id
    WHERE i.quantity > 0";
$fertilizer_result = mysqli_query($conn, $fertilizer_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_schedule'])) {
    if (!empty($_POST['seedling_ids'])) {
        $seedling_ids = $_POST['seedling_ids'];
        $fertilizer_id = $_POST['fertilizer_id'];
        $unit = $_POST['unit'];
        $scheduled_date = $_POST['scheduled_date'];

        foreach ($seedling_ids as $seedling_id) {
            $schedule_query = "
                INSERT INTO fertilizer_schedule (seedling_id, fertilizer_id, unit, scheduled_date)
                VALUES ('$seedling_id', '$fertilizer_id', '$unit', '$scheduled_date')";
            if (!mysqli_query($conn, $schedule_query)) {
                echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                exit;
            }
        }
        echo "<script>alert('Schedule created!'); window.location.href=window.location.href;</script>";
    } else {
        echo "<script>alert('Please select at least one seedling.');</script>";
    }
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
                                   <a class="nav-link dropdown-toggle active" href="#" id="scheduleDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Schedule
                                   </a>
                                   <ul class="dropdown-menu" aria-labelledby="scheduleDropdown">
                                        <li><a class="dropdown-item" href="staff_nutritionist_add_schedule.php"> Schedule</a></li>
                                        <li><a class="dropdown-item" href="staff_nutritionist_schedule_history.php">Schedule History</a></li>
                                   </ul>
                              </li>
                              <li class="nav-item">
                                   <a class="nav-link" href="staff_nutritionist_fertilizer_inventory.php">Inventory</a>
                              </li>
                              <li class="nav-item">
                                   <a class="nav-link" href="staff_nutritionist_fertilizer_deduction.php">Deduction</a>
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
    <h2>Select Seddling to Schedule Fertilizer</h2>
    <hr>
    <div class="row mb-3">
        <div class="col-md-4">
            <select id="categoryFilter" class="form-select">
                <option value="">All Categories</option>
                <?php while ($cat = mysqli_fetch_assoc($category_result)): ?>
                    <option value="<?= $cat['category_name']; ?>"><?= $cat['category_name']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" id="searchInput" class="form-control" placeholder="Search Seed Name...">
        </div>
        <div class="col-md-4 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScheduleModal"><i class="fa-solid fa-plus"></i> Schedule</button>
        </div>
    </div>

    <form action="" method="POST">
        <table class="table table-bordered" id="seedlingTable">
            <thead>
                <tr>
                    <th class="text-center">Select</th>
                    <th>Seed Name</th>
                    <th>Variety Name</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                <?php mysqli_data_seek($seedling_result, 0); ?>
                <?php while ($row = mysqli_fetch_assoc($seedling_result)): ?>
                    <tr>
                        <td class="text-center"><input type="checkbox" name="seedling_ids[]" value="<?= $row['id']; ?>"></td>
                        <td class="seed-name"><?= $row['seed_name']; ?></td>
                        <td><?= $row['variety_name']; ?></td>
                        <td class="category-name"><?= $row['category_name']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Add Schedule Modal -->
        <div class="modal fade" id="addScheduleModal" tabindex="-1" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addScheduleModalLabel">Add Fertilizer Schedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Select Fertilizer</label>
                            <select name="fertilizer_id" class="form-select" required>
                                <option value="">Select Fertilizer</option>
                                <?php mysqli_data_seek($fertilizer_result, 0); ?>
                                <?php while ($fertilizer = mysqli_fetch_assoc($fertilizer_result)): ?>
                                    <option value="<?= $fertilizer['id']; ?>">
                                        <?= $fertilizer['fertilizer_name']; ?> (<?= $fertilizer['quantity']; ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Unit</label>
                            <input type="text" name="unit" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Scheduled Date</label>
                            <input type="datetime-local" name="scheduled_date" class="form-control" required>
                        </div>
                        <button type="submit" name="create_schedule" class="btn btn-success">Create Schedule</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
$(document).ready(function () {
    $('#searchInput, #categoryFilter').on('input change', function () {
        var searchText = $('#searchInput').val().toLowerCase();
        var selectedCategory = $('#categoryFilter').val().toLowerCase();

        $('#seedlingTable tbody tr').each(function () {
            var seedName = $(this).find('.seed-name').text().toLowerCase();
            var categoryName = $(this).find('.category-name').text().toLowerCase();

            var matchSearch = seedName.includes(searchText);
            var matchCategory = !selectedCategory || categoryName === selectedCategory;

            if (matchSearch && matchCategory) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
</script>

          <script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
     </body>
</html>
