<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = "Your Shopping Cart | SUGNAYU";
$pageDescription = "Review your SUGNAYU products and quantities before checkout.";
$activePage = "shop";
$items = cartItems();
include __DIR__ . '/includes/header.php';
?>
<main class="commerce-page">
    <section class="page-hero compact-hero"><span class="eyebrow">YOUR BAG</span>
        <h1>Shopping <span>cart.</span></h1>
        <p>Review your items before proceeding to checkout.</p>
    </section>
    <section class="section commerce-wrap">
        <?php if (isset($_GET['added'])): ?><p class="notice success">Product added to your cart.</p><?php endif; ?>
        <?php if (!$items): ?>
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <h2>Your cart is empty</h2>
                <p>Explore the SUGNAYU collection and add a product to get started.</p><a class="btn btn-primary" href="shop.php">Continue Shopping</a>
            </div>
        <?php else: ?>
            <form method="post" action="update-cart.php" class="cart-layout">
                <div class="cart-items">
                    <?= csrfField() ?>
                    <?php foreach ($items as $item): ?>
                        <article class="cart-item">
                            <div class="cart-thumb"><span>✳</span><small>SUGNAYU</small></div>
                            <div class="cart-item-copy"><span class="product-category"><?= e($item['category']) ?></span>
                                <h3><?= e($item['name']) ?></h3>
                                <p><?= e($item['pack_size']) ?></p><strong><?= sugnayuMoney($item['price']) ?></strong>
                            </div>
                            <label class="qty-label">Qty <input type="number" name="qty[<?= (int)$item['id'] ?>]" value="<?= (int)$item['quantity'] ?>" min="1" max="20"></label>
                            <strong class="line-total"><?= sugnayuMoney($item['line_total']) ?></strong>
                            <button class="remove-button" type="submit" name="remove" value="<?= (int)$item['id'] ?>">Remove</button>
                        </article>
                    <?php endforeach; ?>
                    <button class="btn btn-outline" type="submit">Update Cart</button>
                </div>
                <aside class="order-summary">
                    <h2>Order summary</h2>
                    <div><span>Subtotal</span><strong><?= sugnayuMoney(cartSubtotal()) ?></strong></div>
                    <div><span>Shipping</span><span>Calculated at checkout</span></div>
                    <div><span>Taxes</span><span>Included if applicable</span></div>
                    <hr>
                    <div class="summary-total"><span>Total</span><strong><?= sugnayuMoney(cartSubtotal()) ?></strong></div>
                    <p class="small-note">Final shipping/tax rules should be confirmed before accepting live orders.</p><a class="btn btn-primary btn-full" href="checkout.php">Proceed to Checkout</a><a class="continue-link" href="shop.php">Continue shopping</a>
                </aside>
            </form>
        <?php endif; ?>
    </section>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>