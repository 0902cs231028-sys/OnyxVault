<?php
session_start();
require_once "config.php";
require_once "includes/Cipher.php";

if (!isset($_SESSION['user_id'])) exit;

$stmt = $pdo->prepare("SELECT note_content, category, created_at FROM notes WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$data = $stmt->fetchAll();

header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="vault_export_'.date('Y-m-d').'.json"');

echo json_encode([
    "vault_owner" => $_SESSION['username'],
    "export_date" => date('Y-m-d H:i:s'),
    "encryption_standard" => "AES-256-CBC",
    "records" => $data
], JSON_PRETTY_PRINT);
