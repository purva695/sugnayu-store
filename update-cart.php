<?php
require_once __DIR__ . '/includes/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}
verifyCsrf();
if (isset($_POST['remove'])) {
    unset($_SESSION['cart'][(int)$_POST['remove']]);
} else {
    foreach (($_POST['qty'] ?? []) as $id => $qty) {
        $id = (int)$id;
        if (!sugnayuGetProductById($id)) continue;
        $qty = filter_var($qty, FILTER_VALIDATE_INT);
        if ($qty === false || $qty < 1) unset($_SESSION['cart'][$id]);
        else $_SESSION['cart'][$id] = min(20, $qty);
    }
}
header('Location: cart.php');
exit;
