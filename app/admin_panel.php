<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['isAdmin']) {
    http_response_code(403);
    echo 'Access denied.';
    exit;
}

// Handle admin status change
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_id'], $_POST['isAdmin'])) {
        $user_id = (int)$_POST['user_id'];
        $isAdmin = (int)$_POST['isAdmin'];
        $stmt = $pdo->prepare('UPDATE users SET isAdmin = ? WHERE id = ?');
        $stmt->execute([$isAdmin, $user_id]);
    }
    if (isset($_POST['new_login'], $_POST['new_password'], $_POST['new_isAdmin'])) {
        $new_login = trim($_POST['new_login']);
        $new_password = trim($_POST['new_password']);
        $new_isAdmin = (int)$_POST['new_isAdmin'];
        if ($new_login && $new_password) {
            $stmt = $pdo->prepare('INSERT INTO users (login, password, isAdmin, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())');
            $stmt->execute([$new_login, $new_password, $new_isAdmin]);
        }
    }
}

// Fetch all users
$stmt = $pdo->query('SELECT id, login, isAdmin FROM users ORDER BY login ASC');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .admin-table th, .admin-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .admin-table th { background: #f0f0f0; }
    </style>
</head>
<body>
    <div class="chat-container">
        <h2>Admin Panel</h2>
        <h3>Create New User</h3>
        <form method="POST" style="margin-bottom:20px;">
            <label>Login: <input type="text" name="new_login" required></label>
            <label>Password: <input type="text" name="new_password" required></label>
            <label>Admin:
                <select name="new_isAdmin">
                    <option value="1">Yes</option>
                    <option value="0" selected>No</option>
                </select>
            </label>
            <button type="submit">Create User</button>
        </form>
        <table class="admin-table">
            <tr><th>ID</th><th>Login</th><th>Admin</th><th>Change Status</th></tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['login']) ?></td>
                <td><?= $user['isAdmin'] ? 'Yes' : 'No' ?></td>
                <td>
                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <select name="isAdmin">
                            <option value="1" <?= $user['isAdmin'] ? 'selected' : '' ?>>Yes</option>
                            <option value="0" <?= !$user['isAdmin'] ? 'selected' : '' ?>>No</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                    <?php else: ?>
                    (You)
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <a href="index.html">Back to Chat</a>
    </div>
</body>
</html>
