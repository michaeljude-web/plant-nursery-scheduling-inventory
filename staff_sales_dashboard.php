
<?php
include 'includes/db.php';

$category_query = "SELECT * FROM categories";
$category_result = $conn->query($category_query);

$sql = "SELECT seeds.seed_name, categories.category_name, seed_varieties.id, 
               seed_varieties.variety_name, seed_varieties.price 
        FROM seed_varieties 
        INNER JOIN seeds ON seed_varieties.seed_id = seeds.id
        INNER JOIN categories ON seeds.category_id = categories.id";
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
      <style>
       body {
    font-family: Arial, sans-serif;
}

.sales-table {
    margin: 30px;
}

.container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #fff;
    min-width: 600px; 
}

th, td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: left;
    white-space: nowrap; 
}

th {
    background-color: #f4f4f4;
    font-weight: bold;
}

input[type="number"] {
    width: 60px;
    text-align: center;
    border: 1px solid #ccc;
    padding: 5px;
}

select, input[type="text"] {
    padding: 8px;
    width: 100%;
    max-width: 220px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    padding: 7px 10px;
    margin: 3px;
    background-color:rgb(22, 118, 219);
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 4px;
    transition: background 0.3s;
    width: 40%;
    max-width: 219px;
    /* max-width: 200px; */
}

button:hover {
    background-color: #0056b3;
}

button:disabled {
    background-color: gray;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .sales-table {
        margin: 10px;
    }

    table {
        width: 100%;
        min-width: 100%;
        display: block;
        overflow-x: auto;
    }

    th, td {
        padding: 8px;
        font-size: 14px;
    }

    input[type="number"], select, input[type="text"] {
        width: 100%;
        max-width: none;
    }

    button {
        width: 100%;
    }
}
.filter-container {
    display: flex;
    align-items: center;
    gap: 10px; 
    margin-bottom: 15px;
    flex-wrap: wrap; 
}

.filter-container input,
.filter-container select {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.filter-container input {
    width: 250px;
}

.filter-container select {
    width: 250px;
}

.total-price {
    background-color: #f4f4f4;
    padding: 8px 12px;
    border-radius: 4px;
    font-weight: bold;
}
    </style>
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
      <section class="sales-table">
         <h2>Select seeds Product</h2>
         <div class="filter-container">
            <input type="text" id="searchInput" placeholder="Search seed name...">
            <select id="categoryFilter">
               <option value="">All Categories</option>
               <?php while ($cat = $category_result->fetch_assoc()) { ?>
               <option value="<?= $cat['category_name']; ?>"><?= $cat['category_name']; ?></option>
               <?php } ?>
            </select>
            <span class="total-price">Total Order Price: <span id="grandTotal">0.00</span></span>
            <button type="submit" disabled id="placeOrder">Place Order</button>
         </div>
         <form id="orderForm" method="POST" action="actions/order_process.php">
            <table id="productTable">
               <thead>
                  <tr>
                     <th>Select</th>
                     <th>Seed</th>
                     <th>Category</th>
                     <th>Variety</th>
                     <th>Price</th>
                     <th>Quantity</th>
                     <th>Total</th>
                  </tr>
               </thead>
               <tbody>
                  <?php while ($row = $result->fetch_assoc()) { ?>
                  <tr>
                     <td><input type="checkbox" class="productCheckbox" name="selected_products[]" value="<?= $row['id']; ?>" data-price="<?= $row['price']; ?>"></td>
                     <td class="seed"><?= $row['seed_name']; ?></td>
                     <td class="category"><?= $row['category_name']; ?></td>
                     <td><?= $row['variety_name']; ?></td>
                     <td class="price"><?= number_format($row['price'], 2); ?></td>
                     <td><input type="number" class="quantity" name="quantity[<?= $row['id']; ?>]" min="1" value="1"></td>
                     <td class="totalPrice">0.00</td>
                  </tr>
                  <?php } ?>
               </tbody>
            </table>
            <!-- <h3>Total Order Price: <span id="grandTotal">0.00</span></h3> -->
            <!-- <button type="submit" disabled id="placeOrder">Place Order</button> -->
            <div id="customerModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
               <div style="background:white; padding:20px; width:270px; margin:100px auto; border-radius:10px;">
                  <h4>Enter Customer Details</h4>
                  <label>First Name:</label>
                  <input type="text" id="firstname" required><br>
                  <label>Last Name:</label>
                  <input type="text" id="lastname" required><br>
                  <label>Contact:</label>
                  <input type="text" id="contact" required><br>
                  <label>Address:</label>
                  <input type="text" id="address" required><br>
                  <hr>
                  <button onclick="submitOrder()" class="bg-success">Confirm</button>
                  <button onclick="closeModal()" class="bg-danger">Cancel</button>
               </div>
            </div>
      </section>
      </form>
      <script src="assets/bootstrap-5/js/bootstrap.bundle.min.js"></script>
</html>

<script>
          document.getElementById('placeOrder').addEventListener('click', function (event) {
        event.preventDefault();
        document.getElementById('customerModal').style.display = 'block';
    });
    
    function closeModal() {
        document.getElementById('customerModal').style.display = 'none';
    }
    
    function submitOrder() {
        let firstname = document.getElementById('firstname').value;
        let lastname = document.getElementById('lastname').value;
        let contact = document.getElementById('contact').value;
        let address = document.getElementById('address').value;
    
        if (firstname && lastname && contact && address) {
            let form = document.getElementById('orderForm');
            let inputFirstName = document.createElement("input");
            inputFirstName.type = "hidden";
            inputFirstName.name = "firstname";
            inputFirstName.value = firstname;
            form.appendChild(inputFirstName);
    
            let inputLastName = document.createElement("input");
            inputLastName.type = "hidden";
            inputLastName.name = "lastname";
            inputLastName.value = lastname;
            form.appendChild(inputLastName);
    
            let inputContact = document.createElement("input");
            inputContact.type = "hidden";
            inputContact.name = "contact";
            inputContact.value = contact;
            form.appendChild(inputContact);
    
            let inputAddress = document.createElement("input");
            inputAddress.type = "hidden";
            inputAddress.name = "address";
            inputAddress.value = address;
            form.appendChild(inputAddress);
    
            form.submit();
        } else {
            alert("Please fill out all fields.");
        }
    }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('#productTable tbody tr').forEach(row => {
                let checkbox = row.querySelector('.productCheckbox');
                let quantityInput = row.querySelector('.quantity');
                let totalPriceCell = row.querySelector('.totalPrice');
                let price = parseFloat(checkbox.dataset.price);
                let quantity = parseInt(quantityInput.value) || 1;
    
                if (checkbox.checked) {
                    let itemTotal = price * quantity;
                    totalPriceCell.textContent = itemTotal.toFixed(2);
                    total += itemTotal;
                } else {
                    totalPriceCell.textContent = '0.00';
                }
            });
    
            document.getElementById('grandTotal').textContent = total.toFixed(2);
            updateSubmitButton();
        }
    

        function updateSubmitButton() {
            let anyChecked = document.querySelectorAll('.productCheckbox:checked').length > 0;
            document.getElementById('placeOrder').disabled = !anyChecked;
        }

        document.querySelectorAll('.quantity').forEach(input => {
            input.addEventListener('input', function () {
                let row = this.closest('tr');
                let checkbox = row.querySelector('.productCheckbox');
                checkbox.checked = true;
                updateTotal();
            });
        });
    
        document.querySelectorAll('.productCheckbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateTotal);
        });
    
        document.getElementById('searchInput').addEventListener('input', function () {
            let searchValue = this.value.toLowerCase();
            document.querySelectorAll('#productTable tbody tr').forEach(row => {
                let seedName = row.querySelector('.seed').textContent.toLowerCase();
                row.style.display = seedName.includes(searchValue) ? '' : 'none';
            });
        });
    

        document.getElementById('categoryFilter').addEventListener('change', function () {
            let filterValue = this.value.toLowerCase();
            document.querySelectorAll('#productTable tbody tr').forEach(row => {
                let category = row.querySelector('.category').textContent.toLowerCase();
                row.style.display = (filterValue === '' || category === filterValue) ? '' : 'none';
            });
        });
    

        updateTotal();
    </script>
<?php $conn->close(); ?>