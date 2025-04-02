<?php
$conn = new mysqli('localhost', 'root', '', 'sads');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>