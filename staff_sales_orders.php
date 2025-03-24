<?php 
include 'includes/db.php';

$sql = "SELECT o.id, CONCAT(o.firstname, ' ', o.lastname) AS fullname, 
               o.contact, o.address, o.quantity, o.total_price, 
               s.seed_name, v.variety_name 
        FROM orders o 
        JOIN seed_varieties v ON o.variety_id = v.id 
        JOIN seeds s ON v.seed_id = s.id 
        ORDER BY o.id DESC";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title></title>
      <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css">
      <script src="assets/jquery/jquery-3.6.0.min.js"></script>
   </head>
   <body>
      <nav class="navbar navbar-light bg-light">
         <div class="container-fluid d-flex justify-content-center">
            <div>
               <a class="nav-link d-inline" href="staff_sales_dashboard.php"><i class="fa-solid fa-calendar"></i> Add Oders</a> |
               <a class="nav-link d-inline" href="staff_sales_orders.php"><i class="fas fa-box"></i> Order List</a> |
               <a class="nav-link d-inline" href="staff_sales_team.php"><i class="fa-solid fa-users"></i> Team</a> |
               <a class="nav-link d-inline" href="staff_login.php">
               <i class="fa-solid fa-right-from-bracket"></i> Logout
               </a>
            </div>
         </div>
      </nav>
      <div class="container mt-4">
         <h2>Order List</h2>
         <div class="mb-3">
            <div class="d-flex gap-3 mb-3">
               <input type="text" id="searchInput" class="form-control" placeholder="Search by Seed Name...">
               <select id="categoryFilter" class="form-select">
                  <option value="">All Categories</option>
                  <?php
                     $categoryQuery = "SELECT DISTINCT s.seed_name FROM seeds s";
                     $categoryResult = $conn->query($categoryQuery);
                     while ($cat = $categoryResult->fetch_assoc()) {
                         echo "<option value='" . $cat['seed_name'] . "'>" . $cat['seed_name'] . "</option>";
                     }
                     ?>
               </select>
            </div>
         </div>
         <table class="table table-bordered">
            <thead>
               <tr>
                  <th>Full Name</th>
                  <th>Contact</th>
                  <th>Address</th>
                  <th>Seed Name</th>
                  <th>Variety Name</th>
                  <th>Quantity</th>
                  <th>Total Price</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <?php while ($row = $result->fetch_assoc()) { ?>
               <tr>
                  <td><?php echo $row['fullname']; ?></td>
                  <td><?php echo $row['contact']; ?></td>
                  <td><?php echo $row['address']; ?></td>
                  <td><?php echo $row['seed_name']; ?></td>
                  <td><?php echo $row['variety_name']; ?></td>
                  <td><?php echo $row['quantity']; ?></td>
                  <td><?php echo $row['total_price']; ?></td>
                  <td>
                     <button class="btn btn-success btn-sm edit-btn" 
                        data-id="<?php echo $row['id']; ?>" 
                        data-contact="<?php echo $row['contact']; ?>" 
                        data-address="<?php echo $row['address']; ?>" 
                        data-quantity="<?php echo $row['quantity']; ?>" 
                        data-total_price="<?php echo $row['total_price']; ?>"
                        data-bs-toggle="modal" data-bs-target="#editModal">
                     <i class="fas fa-edit"></i>
                     </button>
                     <button class="btn btn-danger btn-sm delete-btn" 
                        data-id="<?php echo $row['id']; ?>" 
                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                     <i class="fas fa-trash"></i>
                     </button>
                  </td>
               </tr>
               <?php } ?>
            </tbody>
         </table>
      </div>
      <div class="modal fade" id="editModal" tabindex="-1">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title">Edit Order</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body">
                  <form id="editForm">
                     <input type="hidden" id="edit-id">
                     <div class="mb-3">
                        <label>Contact</label>
                        <input type="text" id="edit-contact" class="form-control">
                     </div>
                     <div class="mb-3">
                        <label>Address</label>
                        <input type="text" id="edit-address" class="form-control">
                     </div>
                     <div class="mb-3">
                        <label>Quantity</label>
                        <input type="number" id="edit-quantity" class="form-control">
                     </div>
                     <div class="mb-3">
                        <label>Total Price</label>
                        <input type="text" id="edit-total_price" class="form-control">
                     </div>
                     <button type="submit" class="btn btn-primary">Save Changes</button>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <div class="modal fade" id="deleteModal" tabindex="-1">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title">Delete Order</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
               </div>
               <div class="modal-body">
                  <p>Are you sure you want to delete this order?</p>
                  <input type="hidden" id="delete-id">
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
               </div>
            </div>
         </div>
      </div>
      <script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
</html>

<script>
        $(document).ready(function() {
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("table tbody tr").each(function() {
            $(this).toggle($(this).find("td:nth-child(4)").text().toLowerCase().indexOf(value) > -1);
        });
    });

    $("#categoryFilter").on("change", function() {
        var selectedCategory = $(this).val().toLowerCase();
        var hasVisibleRow = false;

        $("table tbody tr").each(function() {
            var seedName = $(this).find("td:nth-child(4)").text().toLowerCase();
            
            if (selectedCategory === "" || seedName === selectedCategory) {
                $(this).show();
                hasVisibleRow = true;
            } else {
                $(this).hide();
            }
        });


        if (!hasVisibleRow) {
            $("table tbody").append("<tr id='noData'><td colspan='8' class='text-center'>No records found</td></tr>");
        } else {
            $("#noData").remove();
        }
    });
});


   
        $(".edit-btn").click(function() {
            $("#edit-id").val($(this).data("id"));
            $("#edit-contact").val($(this).data("contact"));
            $("#edit-address").val($(this).data("address"));
            $("#edit-quantity").val($(this).data("quantity"));
            $("#edit-total_price").val($(this).data("total_price"));
        });


        $("#editForm").submit(function(e) {
            e.preventDefault();
            $.post("edit_order.php", {
                id: $("#edit-id").val(),
                contact: $("#edit-contact").val(),
                address: $("#edit-address").val(),
                quantity: $("#edit-quantity").val(),
                total_price: $("#edit-total_price").val()
            }, function(response) {
                alert(response);
                location.reload();
            });
        });

      
        $(".delete-btn").click(function() {
            $("#delete-id").val($(this).data("id"));
        });

    
        $("#confirmDelete").click(function() {
            $.post("delete_order.php", {
                id: $("#delete-id").val()
            }, function(response) {
                alert(response);
                location.reload();
            });
        });
    </script>
<?php $conn->close(); ?>
