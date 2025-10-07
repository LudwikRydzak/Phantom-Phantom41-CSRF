<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode([]);
    exit;
}


$stmt = $pdo->query('SELECT username, message, time FROM messages ORDER BY time DESC LIMIT 10');
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
$messages = array_reverse($messages); // Show oldest first
echo json_encode($messages);
