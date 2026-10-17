<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/payment.php';
require_once __DIR__ . '/includes/db.php';
$orderId = (int)($_SESSION['payment_order_id'] ?? 0);
$gatewayOrderId = trim($_POST['razorpay_order_id'] ?? '');
$paymentId = trim($_POST['razorpay_payment_id'] ?? '');
$signature = trim($_POST['razorpay_signature'] ?? '');
if (!$orderId || !$gatewayOrderId || !$paymentId || !$signature) {
    http_response_code(400);
    exit('Payment verification details are incomplete. Please contact the store with your order reference.');
}
$expected = hash_hmac('sha256', $gatewayOrderId . '|' . $paymentId, RAZORPAY_KEY_SECRET);
if (!hash_equals($expected, $signature)) {
    try {
        $stmt = $pdo->prepare(
            "UPDATE orders SET payment_status = 'failed'
     WHERE id = ?"
        );
        $stmt->execute([$orderId]);
    } catch (Throwable $e) {
        error_log($e->getMessage());
    }
    http_response_code(400);
    exit('Payment signature could not be verified. Do not place the same order again; please contact the store.');
}
// $pdo is already created by includes/db.php
$stmt = $pdo->prepare("UPDATE orders SET payment_status='paid', gateway_payment_id=? WHERE id=? AND gateway_order_id=? AND payment_status='pending'");
$stmt->execute([$paymentId, $orderId, $gatewayOrderId]);
if ($stmt->rowCount() < 1) {
    $check = $pdo->prepare("SELECT id,payment_status FROM orders WHERE id=? AND gateway_order_id=?");
    $check->execute([$orderId, $gatewayOrderId]);
    $existing = $check->fetch();
    if (!$existing || $existing['payment_status'] !== 'paid') {
        http_response_code(400);
        exit('We could not confirm this payment status. Please contact the store before retrying.');
    }
}
unset($_SESSION['cart']);
$_SESSION['last_order_id'] = $orderId;
header('Location: order-success.php?order=' . $orderId);
exit;
