<?php
session_start();
require_once 'db.php';
header('Content-Type: text/plain');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo 'Unauthorized.';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['login'];
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($message)) {
        $stmt = $pdo->prepare('INSERT INTO messages (user_id, username, message, time) VALUES (?, ?, ?, NOW())');
        $stmt->execute([$user_id, $username, $message]);
        echo 'Wiadomość została wysłana.';
    } else {
        echo 'Wiadomość nie może być pusta.';
    }
}
?>