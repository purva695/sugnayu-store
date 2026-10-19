<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($pdo) || !($pdo instanceof PDO)) { http_response_code(500); exit('Database connection is not configured.'); }
try { $pdo->exec("CREATE TABLE IF NOT EXISTS admin_users (id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, name VARCHAR(120) NOT NULL, email VARCHAR(190) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"); $count = (int)$pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn(); }
catch (PDOException $e) { http_response_code(500); exit('Could not create admin_users table. Check database permissions.'); }
$message = '';
if ($count > 0) { $message = 'An admin account already exists. For security, delete this setup.php file from the server now.'; }
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['setup_csrf'] ?? '', $_POST['csrf_token'] ?? '')) $message = 'Invalid request. Refresh and try again.';
    else {
        $name = trim($_POST['name'] ?? ''); $email = trim($_POST['email'] ?? ''); $password = $_POST['password'] ?? '';
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 10) $message = 'Enter a name, valid email, and password with at least 10 characters.';
        else { $stmt = $pdo->prepare('INSERT INTO admin_users (name,email,password_hash) VALUES (?,?,?)'); $stmt->execute([$name,$email,password_hash($password, PASSWORD_DEFAULT)]); $count = 1; $message = 'Admin account created. Delete setup.php from the server now, then sign in at login.php.'; }
    }
}
$_SESSION['setup_csrf'] = bin2hex(random_bytes(32));
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Setup | SUGNAYU</title><link rel="stylesheet" href="assets/admin.css"></head><body class="auth-page"><form class="auth-card" method="post"><div class="auth-brand">SUGNAYU<small>ONE-TIME ADMIN SETUP</small></div><h1>Create admin account</h1><p class="muted">Create the account you will use to manage products and orders.</p><div class="alert <?= $count > 0 ? 'success' : 'error' ?>"><?=htmlspecialchars($message ?: 'Choose a secure password. This page should be removed after setup.', ENT_QUOTES, 'UTF-8')?></div><?php if ($count === 0): ?><input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['setup_csrf'], ENT_QUOTES, 'UTF-8')?>"><label>Your name</label><input name="name" required maxlength="120"><label>Admin email</label><input type="email" name="email" required maxlength="190"><label>Password (minimum 10 characters)</label><input type="password" name="password" minlength="10" required><button class="btn primary full" type="submit">Create admin account</button><?php else: ?><a class="btn primary full" href="login.php">Go to login</a><?php endif; ?></form></body></html>
