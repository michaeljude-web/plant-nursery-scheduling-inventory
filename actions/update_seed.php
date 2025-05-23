<?php
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['edit_id'];
    $seed_name = $_POST['edit_seed_name'];
    $price = $_POST['edit_price'];

    $sql = "UPDATE seedling_variety sv
    JOIN seedling_info si ON sv.seed_id = si.id
    SET si.seed_name = ?, sv.price = ?
    WHERE sv.id = ?";


    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $seed_name, $price, $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    $conn->close();
}
?>
