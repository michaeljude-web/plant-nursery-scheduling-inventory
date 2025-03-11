<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $age = intval($_POST['age']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $query = "UPDATE staff 
              SET firstname = '$firstname', lastname = '$lastname', 
                  age = $age, contact_number = '$contact_number', address = '$address' 
              WHERE id = $id";

if (mysqli_query($conn, $query)) {
    echo "<script>
        alert('Staff details updated successfully!');
        window.location.href = '../admin_employee_view.php';
    </script>";
} else {
    echo "<script>
        alert('Error updating record: " . mysqli_error($conn) . "');
        window.location.href = '../admin_employee_view.php';
    </script>";
}
}
?>
