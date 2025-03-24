<?php
$conn = new mysqli("localhost", "root", "", "sad");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['id'];

$sql = "DELETE FROM orders WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Order deleted successfully.";
} else {
    echo "Error deleting order.";
}

$stmt->close();
$conn->close();
?>
