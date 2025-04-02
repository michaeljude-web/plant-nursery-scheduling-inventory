<?php
ob_start(); 
include '../includes/db.php';
header("Content-Type: text/plain"); 
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM staff WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

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
