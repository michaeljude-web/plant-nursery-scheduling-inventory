<?php
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = intval($_POST["id"]);
    
    $stmt = $conn->prepare("DELETE FROM seedling_variety WHERE id = ?");
    $stmt->bind_param("i", $id);
    

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    
    $stmt->close();
    $conn->close();
}
?>
