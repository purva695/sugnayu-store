<?php
require_once __DIR__ . '/includes/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: shop.php');
    exit;
}
verifyCsrf();
$action = $_POST['action'] ?? '';
$id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$qty = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
$product = $id ? sugnayuGetProductById((int)$id) : null;
if (!$product || empty($product['status'])) {
    header('Location: shop.php?error=product');
    exit;
}
$qty = max(1, min(20, (int)($qty ?: 1)));
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
if ($action === 'add' || $action === 'buy') {
    $_SESSION['cart'][$id] = min(20, (int)($_SESSION['cart'][$id] ?? 0) + $qty);
    header('Location: ' . ($action === 'buy' ? 'checkout.php' : 'cart.php?added=1'));
    exit;
}
header('Location: shop.php');
exit;
