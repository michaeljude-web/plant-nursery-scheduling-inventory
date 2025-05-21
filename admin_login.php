<?php
include 'includes/db.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $passcode = $_POST['passcode'];
    
//     $stmt = $conn->prepare("SELECT id FROM admin WHERE passcode = ?");
//     $stmt->bind_param("s", $passcode);
//     $stmt->execute();
//     $result = $stmt->get_result();
    
//     if ($result->num_rows > 0) {
//         $admin = $result->fetch_assoc();
//         $_SESSION['admin_id'] = $admin['id'];
//         header("Location: admin_dashboard.php");
//         exit();
//     } else {
//         $error = "Invalid passcode!";
//     }
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $passcode = $_POST['passcode'];
    
    $query = "SELECT id FROM admin WHERE passcode = '$passcode'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid passcode!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h2 class="text-center">EJ'S Plant Nursery</h2><br>
                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Enter Passcode:</label>
                        <input type="password" name="passcode" class="form-control underline-input" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
