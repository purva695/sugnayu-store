
<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/db.php';

function isCustomerLoggedIn(): bool {
    return isset($_SESSION['customer']['id']);
}

function requireCustomerLogin(): void {
    if (!isCustomerLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? 'checkout.php';
        header('Location: login.php');
        exit;
    }
}

function currentCustomer(): ?array {
    return $_SESSION['customer'] ?? null;
}
