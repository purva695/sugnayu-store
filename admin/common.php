<?php
// Shared admin bootstrap. Uses the existing site's includes/db.php and its $pdo connection.
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/../includes/db.php';
if (!isset($pdo) || !($pdo instanceof PDO)) {
    http_response_code(500);
    exit('Database connection is not configured. Check includes/db.php.');
}
function admin_e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function admin_require_login(): void {
    if (empty($_SESSION['sugnayu_admin_id'])) {
        header('Location: login.php'); exit;
    }
}
function admin_csrf_token(): string {
    if (empty($_SESSION['sugnayu_admin_csrf'])) $_SESSION['sugnayu_admin_csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['sugnayu_admin_csrf'];
}
function admin_csrf_check(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals($_SESSION['sugnayu_admin_csrf'] ?? '', $_POST['csrf_token'] ?? '')) {
        http_response_code(403); exit('Invalid request token. Please go back and try again.');
    }
}
function admin_flash(string $message, string $type = 'success'): void { $_SESSION['sugnayu_admin_flash'] = ['message'=>$message, 'type'=>$type]; }
function admin_render_start(string $title): void {
    $flash = $_SESSION['sugnayu_admin_flash'] ?? null; unset($_SESSION['sugnayu_admin_flash']);
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.admin_e($title).' | SUGNAYU Admin</title><link rel="stylesheet" href="assets/admin.css"></head><body><div class="shell"><aside class="sidebar"><a class="brand" href="index.php">SUGNAYU <small>ADMIN PANEL</small></a><nav><a href="index.php">Dashboard</a><a href="products.php">Products</a><a href="orders.php">Orders</a><a href="customers.php">Customers</a><a href="messages.php">Contact Messages</a></nav><a class="logout" href="logout.php">Log out</a></aside><main class="main"><header class="topbar"><div><span class="eyebrow">STORE MANAGEMENT</span><h1>'.admin_e($title).'</h1></div><a class="site-link" href="../index.php" target="_blank" rel="noopener">View website ↗</a></header>';
    if ($flash) echo '<div class="alert '.admin_e($flash['type']).'">'.admin_e($flash['message']).'</div>';
}
function admin_render_end(): void { echo '</main></div></body></html>'; }
function admin_column_enum_values(PDO $pdo, string $table, string $column): array {
    // Identifiers here are fixed in our code, never user-supplied.
    try { $stmt = $pdo->query("SHOW COLUMNS FROM `$table` LIKE " . $pdo->quote($column)); $row = $stmt->fetch(PDO::FETCH_ASSOC); if (!$row || !preg_match('/^enum\((.*)\)$/i', $row['Type'], $m)) return []; preg_match_all("/'((?:\\\\'|[^'])*)'/", $m[1], $matches); return array_map(fn($v)=>str_replace("\\'", "'", $v), $matches[1]); } catch (Throwable $e) { return []; }
}
