
<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'httponly' => true,
        'secure' => $secure,
        'samesite' => 'Lax'
    ]);
    session_start();
}

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/products.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function csrfField(): string {
    return '<input type="hidden" name="csrf_token" value="' .
        htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') . '">';
}

function verifyCsrf(): void {
    $sent = $_POST['csrf_token'] ?? '';
    if (!is_string($sent) ||
        !hash_equals($_SESSION['csrf_token'] ?? '', $sent)) {
        http_response_code(419);
        exit('Session expired. Please go back and try again.');
    }
}

function cartCount(): int {
    $count = 0;
    foreach (($_SESSION['cart'] ?? []) as $qty) {
        $count += max(0, (int)$qty);
    }
    return $count;
}

function cartItems(): array {
    $items = [];
    foreach (($_SESSION['cart'] ?? []) as $id => $qty) {
        $p = sugnayuGetProductById((int)$id);
        $qty = (int)$qty;
        if ($p && $qty > 0 && !empty($p['status'])) {
            $p['quantity'] = min($qty, 20);
            $p['line_total'] = (float)$p['price'] * $p['quantity'];
            $items[] = $p;
        }
    }
    return $items;
}

function cartSubtotal(): float {
    return array_reduce(
        cartItems(),
        fn($sum, $p) => $sum + $p['line_total'],
        0.0
    );
}

function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
