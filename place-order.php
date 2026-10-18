
<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
require_once __DIR__ . '/includes/auth.php';
requireCustomerLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit;
}

verifyCsrf();

$customer = currentCustomer();
$items = cartItems();

if (!$items) {
    header('Location: cart.php');
    exit;
}

// Customer identity comes from the logged-in account.
$name = trim($customer['full_name'] ?? '');
$email = trim($customer['email'] ?? '');

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
    exit('Please check your delivery details and try again.');
}

if ($method !== 'cod') {
    http_response_code(422);
    exit('Please choose Cash on Delivery.');
}

// Recalculate prices from the trusted PHP product catalogue.
$productsById = [];

foreach (sugnayuProducts() as $product) {
    $productsById[(int)$product['id']] = $product;
}

$orderItems = [];
$subtotal = 0.00;

foreach ($items as $item) {
    $productId = (int)($item['id'] ?? 0);
    $quantity = (int)($item['quantity'] ?? 0);

    if (
        $productId < 1 ||
        $quantity < 1 ||
        !isset($productsById[$productId])
    ) {
        http_response_code(422);
        exit('A cart item is invalid. Please update your cart.');
    }

    $product = $productsById[$productId];

    if (empty($product['status'])) {
        http_response_code(422);
        exit('A product in your cart is unavailable.');
    }

    if ($quantity > (int)$product['stock']) {
        http_response_code(422);
        exit('Requested quantity is unavailable.');
    }

    $unitPrice = (float)$product['price'];
    $lineTotal = round($unitPrice * $quantity, 2);
    $subtotal = round($subtotal + $lineTotal, 2);

    $orderItems[] = [
        'id' => $productId,
        'name' => $product['name'],
        'price' => $unitPrice,
        'quantity' => $quantity,
        'line_total' => $lineTotal
    ];
}

$shipping = 0.00;
$total = round($subtotal + $shipping, 2);

$orderNumber = 'SUG-' . date('Ymd') . '-'
    . strtoupper(bin2hex(random_bytes(4)));

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        'INSERT INTO orders
        (
            order_number, customer_name, email, phone,
            address, city, state, postal_code,
            subtotal, shipping_fee, total,
            payment_method, payment_status, order_status
        )
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
        (
            order_id, product_id, product_name,
            unit_price, quantity, line_total
        )
        VALUES (?, ?, ?, ?, ?, ?)'
    );

    foreach ($orderItems as $item) {
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

}catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    http_response_code(500);

    exit(
        'Error: ' .
        htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
    );
}
