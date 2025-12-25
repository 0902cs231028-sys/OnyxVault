<?php
session_start();
require_once "config.php";
require_once "includes/Auth.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $auth = new Auth($pdo);
    
    if ($auth->login($username, $password)) {
        // Redirect to supreme dashboard on success
        header("Location: dashboard.php");
        exit();
    } else {
        // Redirect back to login with an error flag
        header("Location: login.php?error=invalid_credentials");
        exit();
    }
} else {
    // Prevent direct access to this script
    header("Location: login.php");
    exit();
}
