<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = 'Order received | SUGNAYU';
$pageDescription = 'Your SUGNAYU order confirmation.';
$orderId = (int)($_GET['order'] ?? 0);
if (empty($_SESSION['last_order_id']) || $orderId !== (int)$_SESSION['last_order_id']) {
    $orderId = 0;
}
include __DIR__ . '/includes/header.php';
?>
<main class="commerce-page">
    <section class="section commerce-wrap">
        <div class="order-success-card">
            <div class="success-mark">✓</div><span class="eyebrow">THANK YOU</span>
            <h1>Your order has been received.</h1><?php if ($orderId): ?><p>Your order reference is <strong>#<?= (int)$orderId ?></strong>. Keep this number for order-related enquiries.</p><?php else: ?><p>If you have just placed an order, please check your confirmation details or contact the store.</p><?php endif; ?><p>For online payments, this page appears after the payment signature is verified. COD orders remain payable on delivery.</p><a class="btn btn-primary" href="shop.php">Continue Shopping</a>
        </div>
    </section>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>