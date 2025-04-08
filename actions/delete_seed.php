<?php
include '../includes/db.php';
file_put_contents('log.txt', print_r($_POST, true));

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $sql = "DELETE FROM seedling_variety WHERE id = $id";

    if ($conn->query($sql)) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
} else {
    echo "invalid";
}
?>

