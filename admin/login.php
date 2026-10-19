<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($pdo) || !($pdo instanceof PDO)) { http_response_code(500); exit('Database connection is not configured.'); }
if (!empty($_SESSION['sugnayu_admin_id'])) { header('Location: index.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['login_csrf'] ?? '', $_POST['csrf_token'] ?? '')) { $error = 'Please refresh the page and try again.'; }
    else {
        $email = trim($_POST['email'] ?? ''); $password = $_POST['password'] ?? '';
        try {
            $stmt = $pdo->prepare('SELECT id, name, email, password_hash FROM admin_users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]); $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($admin && password_verify($password, $admin['password_hash'])) {
                session_regenerate_id(true); $_SESSION['sugnayu_admin_id'] = (int)$admin['id']; $_SESSION['sugnayu_admin_name'] = $admin['name']; $_SESSION['sugnayu_admin_email'] = $admin['email']; $_SESSION['sugnayu_admin_csrf'] = bin2hex(random_bytes(32)); header('Location: index.php'); exit;
            }
            $error = 'Email or password is incorrect.';
        } catch (PDOException $e) { $error = 'Admin login is not set up yet. Open admin/setup.php once to create the first admin account.'; }
    }
}
$_SESSION['login_csrf'] = bin2hex(random_bytes(32));
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Login | SUGNAYU</title><link rel="stylesheet" href="assets/admin.css"></head><body class="auth-page"><form class="auth-card" method="post"><div class="auth-brand">SUGNAYU<small>STORE ADMINISTRATION</small></div><h1>Welcome back</h1><p class="muted">Sign in to manage your store.</p><?php if ($error): ?><div class="alert error"><?=htmlspecialchars($error, ENT_QUOTES, 'UTF-8')?></div><?php endif; ?><input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['login_csrf'], ENT_QUOTES, 'UTF-8')?>"><label>Email address</label><input type="email" name="email" required autocomplete="username"><label>Password</label><input type="password" name="password" required autocomplete="current-password"><button class="btn primary full" type="submit">Sign in</button><p class="tiny">Authorized store administrators only.</p></form></body></html>
