<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['isAdmin']) {
    http_response_code(403);
    echo 'Forbidden';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';
    $isAdmin = isset($_POST['isAdmin']) ? (int)$_POST['isAdmin'] : 0;

    if ($login && $password) {
        $stmt = $pdo->prepare('INSERT INTO users (login, password, isAdmin, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())');
        $stmt->execute([$login, $password, $isAdmin]);
        echo 'User created.';
    } else {
        echo 'Missing login or password.';
    }
}
?>
