<?php
session_start();
session_destroy();
header("Location: stafflogin.php");
exit();
?>
