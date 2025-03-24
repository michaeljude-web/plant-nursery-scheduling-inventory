<?php
$conn = new mysqli("localhost", "root", "", "sad");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['id'];
$contact = $_POST['contact'];
$address = $_POST['address'];
$quantity = $_POST['quantity'];
$total_price = $_POST['total_price'];

$sql = "UPDATE orders SET contact=?, address=?, quantity=?, total_price=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssidi", $contact, $address, $quantity, $total_price, $id);

if ($stmt->execute()) {
    echo "Order updated successfully.";
} else {
    echo "Error updating order.";
}

$stmt->close();
$conn->close();
?>
