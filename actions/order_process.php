<?php
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['selected_products'])) {

    $firstname = $conn->real_escape_string($_POST['firstname']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $address = $conn->real_escape_string($_POST['address']);

    foreach ($_POST['selected_products'] as $variety_id) {
        $quantity = $_POST['quantity'][$variety_id];
        $price_query = $conn->query("SELECT price FROM seed_varieties WHERE id = $variety_id");

        if ($price_query->num_rows > 0) {
            $price = $price_query->fetch_assoc()['price'];
            $total_price = $price * $quantity;

            $sql = "INSERT INTO orders (seed_id, variety_id, quantity, total_price, firstname, lastname, contact, address) 
                    VALUES ((SELECT seed_id FROM seed_varieties WHERE id = $variety_id), $variety_id, $quantity, $total_price, '$firstname', '$lastname', '$contact', '$address')";
            $conn->query($sql);
        }
    }

    echo "<script>alert('Order placed successfully!'); window.location.href='../staff_sales_dashboard.php';</script>";

} else {
    echo "<script>alert('No items selected!'); window.history.back();</script>";
}

$conn->close();
?>
