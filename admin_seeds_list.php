<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin | Seed List</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=2">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<section class="container-fluid">
    <div class="row">
        <?php include 'includes/sidebar.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h2 class="mt-4">List of Seedling Varieties</h2> <hr>
            
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="searchSeed" class="form-control" placeholder="Search by Seed Name">
                </div>
                <div class="col-md-4">
                    <select id="filterCategory" class="form-select">
                        <option value="">All Category</option>
                        <?php
                        include 'includes/db.php';
                        $categoryQuery = "SELECT DISTINCT category_name FROM seedling_category";
                        $categoryResult = $conn->query($categoryQuery);
                        while ($category = $categoryResult->fetch_assoc()) {
                            echo "<option value='{$category['category_name']}'>{$category['category_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <table class="table table-bordered text-center table-hover">
                <thead>
                    <tr>
                        <th>Seed Name</th>
                        <th>Variety</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="seedTable">
                    <?php
                    $sql = "SELECT sv.id, si.seed_name, sv.variety_name, sc.category_name, sv.price 
                            FROM seedling_variety sv
                            JOIN seedling_info si ON sv.seed_id = si.id
                            JOIN seedling_category sc ON si.category_id = sc.id
                            ORDER BY si.seed_name, sv.variety_name";
                    
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='seedRow' data-seed='{$row['seed_name']}' data-category='{$row['category_name']}' data-seed-id='{$row['id']}'>

                                    <td>{$row['seed_name']}</td>
                                    <td>{$row['variety_name']}</td> 
                                    <td>{$row['category_name']}</td>
                                    <td>" . number_format($row['price'], 2) . "</td>
                                    <td>
                                        <button class='btn btn-warning btn-sm' onclick='openEditModal({$row['id']}, \"{$row['seed_name']}\", \"{$row['variety_name']}\", \"{$row['price']}\")'>
                                            <i class='fas fa-edit'></i> Edit
                                        </button>
                                        <button onclick='confirmDelete({$row['id']})' class='btn btn-danger btn-sm'>
                                            <i class='fas fa-trash'></i> Delete
                                        </button>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center'>No data available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</section>


<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Seed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" id="edit_id" name="edit_id">
                    <div class="mb-3">
                        <label for="edit_seed_name" class="form-label">Seed Name</label>
                        <input type="text" class="form-control" id="edit_seed_name" name="edit_seed_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="edit_price" name="edit_price" step="0.01" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#searchSeed").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $(".seedRow").filter(function () {
                $(this).toggle($(this).data("seed").toLowerCase().includes(value));
            });
        });

        $("#filterCategory").on("change", function () {
            var selectedCategory = $(this).val().toLowerCase();
            $(".seedRow").filter(function () {
                $(this).toggle(selectedCategory === "" || $(this).data("category").toLowerCase() === selectedCategory);
            });
        });
    });

    function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this seed?")) {
        $.post("actions/delete_seed.php", { id: id }, function(response) {
            if (response.trim() === "success") {
                alert("Seed has been deleted.");
                $("tr[data-seed-id='" + id + "']").fadeOut(300, function () {
                    $(this).remove();
                });
            } else {
                alert("Failed to delete seed.");
            }
        });
    }
}



    function openEditModal(id, seedName, price) {
        $("#edit_id").val(id);
        $("#edit_seed_name").val(seedName);
        $("#edit_price").val(parseInt(price));
        $("#editModal").modal("show");
    }

    $("#editForm").submit(function(event) {
        event.preventDefault();
        $.post("actions/update_seed.php", $(this).serialize(), function(response) {
            if (response.trim() == "success") {
                alert("Seed has been updated.");
                location.reload();
            } else {
                alert("Failed to update seed.");
            }
        });
    });
</script>

<script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
</body>
</html>
