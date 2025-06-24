<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /inventory_system/auth/login.php");
    exit;
}
?>
