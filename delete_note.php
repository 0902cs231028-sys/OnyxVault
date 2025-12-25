<?php
session_start();
require_once "config.php";

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
}
header("Location: dashboard.php");
exit();
