<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = "DELETE FROM staff WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Staff deleted successfully!');
                window.location.href = '../admin_employee_view.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deleting staff: " . mysqli_error($conn) . "');
                window.location.href = '../admin_employee_view.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid request!');
            window.location.href = '../admin_employee_view.php';
          </script>";
}
?>
