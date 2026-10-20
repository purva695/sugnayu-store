<?php
require_once __DIR__ . '/includes/auth.php';
requireCustomerLogin();

$customer = currentCustomer();


$stmt = $pdo->prepare(
    'SELECT id, order_number, subtotal, shipping_fee, total,
            payment_status, order_status, created_at
     FROM orders
     WHERE email = ?
     ORDER BY id DESC
     LIMIT 50'
);

$stmt->execute([$customer['email']]);
$orders = $stmt->fetchAll();


$pageTitle = 'My Account | SUGNAYU';
require __DIR__ . '/includes/header.php';
?>

<main class="auth-page account-page">
    <section class="account-card">
        <h1>My Account</h1>
        <p>Welcome, <?= e($customer['full_name']) ?>!</p>

        <div class="account-details">
            <p><strong>Name:</strong> <?= e($customer['full_name']) ?></p>
            <p><strong>Email:</strong> <?= e($customer['email']) ?></p>
            <p><strong>Mobile:</strong> <?= e($customer['phone']) ?></p>
        </div>

        <section class="saved-addresses">
            <h2>My Delivery Addresses</h2>
            <p>Manage your saved delivery addresses.</p>

            <a class="btn btn-primary" href="address.php">
                Manage Addresses
            </a>
        </section>


        <form method="post" action="logout.php">
            <?= csrfField() ?>
            <button class="btn btn-primary" type="submit">Logout</button>
        </form>

        <h2>My Orders</h2>

        <?php if (!$orders): ?>
            <p>You haven't placed any orders yet.</p>
            <a class="btn btn-primary" href="shop.php">Explore Products</a>
        <?php else: ?>
            <div class="order-list">

                <?php foreach ($orders as $order): ?>
                    <article class="order-card">
                        <h3>Order #<?= e($order['order_number']) ?></h3>

                        <p>
                            Subtotal: ₹<?= number_format((float)$order['subtotal'], 2) ?>
                        </p>

                        <p>
                            Shipping: ₹<?= number_format((float)$order['shipping_fee'], 2) ?>
                        </p>

                        <p>
                            Total: ₹<?= number_format((float)$order['total'], 2) ?>
                        </p>

                        <p>
                            Order Status: <?= e($order['order_status']) ?>
                        </p>

                        <p>
                            Payment Status: <?= e($order['payment_status']) ?>
                        </p>

                        <p>
                            Date: <?= e($order['created_at']) ?>
                        </p>
                    </article>
                <?php endforeach; ?>

            </div>
        <?php endif; ?>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>