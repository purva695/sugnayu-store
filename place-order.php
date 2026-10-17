
<?php
require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit;
}

verifyCsrf();

$items = cartItems();
if (!$items) {
    header('Location: cart.php');
    exit;
}

$name = trim($_POST['customer_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$pincode = trim($_POST['pincode'] ?? '');
$address = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');
$state = trim($_POST['state'] ?? '');
$method = $_POST['payment_method'] ?? 'cod';

if (
    $name === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    !preg_match('/^[6-9][0-9]{9}$/', $phone) ||
    !preg_match('/^[0-9]{6}$/', $pincode) ||
    $address === '' || $city === '' || $state === ''
) {
    http_response_code(422);
    exit('Please check your details and try again.');
}

if ($method !== 'cod') {
    http_response_code(503);
    exit('Online payment is not enabled yet. Please choose Cash on Delivery.');
}

$subtotal = cartSubtotal();
$shipping = 0;
$total = $subtotal;
$orderNumber = 'SUG-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        'INSERT INTO orders
        (order_number, customer_name, email, phone, address, city, state,
         postal_code, subtotal, shipping_fee, total, payment_method,
         payment_status, order_status)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );

    $stmt->execute([
        $orderNumber,
        $name,
        $email,
        $phone,
        $address,
        $city,
        $state,
        $pincode,
        $subtotal,
        $shipping,
        $total,
        'cod',
        'pending',
        'pending'
    ]);

    $orderId = (int)$pdo->lastInsertId();

    $itemStmt = $pdo->prepare(
        'INSERT INTO order_items
        (order_id, product_id, product_name, unit_price, quantity, line_total)
        VALUES (?, ?, ?, ?, ?, ?)'
    );

    foreach ($items as $item) {
        $itemStmt->execute([
            $orderId,
            $item['id'],
            $item['name'],
            $item['price'],
            $item['quantity'],
            $item['line_total']
        ]);
    }

    $pdo->commit();

    $_SESSION['last_order_id'] = $orderId;
    $_SESSION['last_order_number'] = $orderNumber;
    unset($_SESSION['cart']);

    header('Location: order-success.php?order=' . $orderId);
    exit;
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log('SUGNAYU order error: ' . $e->getMessage());
    http_response_code(500);
    exit('Order could not be saved. Please contact the store.');
}
