<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $age = trim($_POST['age']);
    $contact_number = trim($_POST['contact_number']);
    $address = trim($_POST['address']);
    $role = trim($_POST['role']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $errors = [];

    if (!preg_match("/^[a-zA-Z ]*$/", $firstname)) {
        $errors[] = "First name should contain only letters and spaces.";
    }

    if (!preg_match("/^[a-zA-Z ]*$/", $lastname)) {
        $errors[] = "Last name should contain only letters and spaces.";
    }

    if (!is_numeric($age) || $age < 18) {
        $errors[] = "Age must be at least 18 years old.";
    }

    if (!preg_match("/^[0-9]+$/", $contact_number)) {
        $errors[] = "Contact number should contain only numbers.";
    }

    // if (strlen($username) < 5) {
    //     $errors[] = "Username must be at least 5 characters long.";
    // }

    // if (strlen($password) < 6) {
    //     $errors[] = "Password must be at least 6 characters long.";
    // }

    $stmt = $conn->prepare("SELECT id FROM staff WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = "Username already taken.";
    }
    $stmt->close();

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO staff (firstname, lastname, age, contact_number, address, role, username, password)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisssss", $firstname, $lastname, $age, $contact_number, $address, $role, $username, $hashed_password);

        if ($stmt->execute()) {
            $message = "New staff added successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = implode("<br>", $errors);
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Staff</title>
    <link rel="stylesheet" href="assets/bootstrap-5/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fontawesome-6.7/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .staff-button {
            padding: 9px 20px;
            width: 48%;
            border: none;
            cursor: pointer;
            margin-right: 15px; 
            display: inline-block;
        }

        .staff-button .btn-secondary {
            background-color: #6c757d; 
        }

        .staff-button a {
            color: white; 
            text-decoration: none;
        }

        .staff-button:hover {
            opacity: 0.9; 
        }

        @media (max-width: 1168px) {
            .staff-button {
                width: 100%;
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="content">
        <h1>Manage Staff</h1>
        <?php if (isset($message)) : ?>
            <div class="alert alert-info mt-3"><?php echo $message; ?></div>
        <?php endif; ?><br>
        
        <form method="POST" action="">
            <div class="row mb-3">
                <div class="col">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="firstname" name="firstname" required>
                </div>
                <div class="col">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lastname" name="lastname" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="contact_number" class="form-label">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                </div>
                <div class="col">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="1" required></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" class="form-control" id="age" name="age" required>
                </div>
                <div class="col">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="Sales Team">Sales Team</option>
                        <option value="Delivery Staff">Delivery Staff</option>
                        <option value="Nutritionist">Nutritionist</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
    <div class="col">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="col">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
</div>


            <div class="d-flex justify-content-between gap-4">
                <button type="submit" class="btn btn-primary w-50">Add Staff</button>
                <a href="admin_employee_view.php" class="btn btn-secondary w-50 text-white text-decoration-none text-center">View Staff</a>
            </div>
        </form>
    </div>
    
</body>
</html>