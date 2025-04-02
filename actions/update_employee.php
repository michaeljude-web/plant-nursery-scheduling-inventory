<?php
ob_start();
include '../includes/db.php';
header("Content-Type: text/plain");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $age = $_POST['age'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    $sql = "UPDATE employee_info SET firstname=?, lastname=?, age=?, contact_number=?, address=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissi", $firstname, $lastname, $age, $contact, $address, $id);

    if ($stmt->execute()) {
        ob_end_clean(); 
        echo "success";
    } else {
        ob_end_clean();
        echo "error";
    }

    $stmt->close();
    $conn->close();
    exit(); 
}
?>
