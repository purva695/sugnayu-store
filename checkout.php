<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = "Checkout | SUGNAYU";
$pageDescription = "Enter your delivery details and choose a payment method for your SUGNAYU order.";
$items = cartItems();
if (!$items) { header('Location: cart.php'); exit; }
include __DIR__ . '/includes/header.php';
?>
<main class="commerce-page">
  <section class="page-hero compact-hero"><span class="eyebrow">ALMOST THERE</span><h1>Secure <span>checkout.</span></h1><p>Enter your delivery details and choose how you would like to pay.</p></section>
  <section class="section commerce-wrap">
    <form class="checkout-layout" method="post" action="place-order.php" id="checkoutForm">
      <div class="checkout-fields">
        <h2>Delivery information</h2>
        <?= csrfField() ?>
        <div class="form-grid">
          <label>Full name<input type="text" name="customer_name" required maxlength="120" autocomplete="name"></label>
          <label>Email address<input type="email" name="email" required maxlength="190" autocomplete="email"></label>
          <label>Mobile number<input type="tel" name="phone" required pattern="[6-9][0-9]{9}" minlength="10" maxlength="10" autocomplete="tel" placeholder="10-digit mobile number"></label>
          <label>PIN code<input type="text" name="pincode" required pattern="[0-9]{6}" maxlength="6" autocomplete="postal-code"></label>
          <label class="full-field">House no., street, area<textarea name="address" rows="3" required maxlength="500" autocomplete="street-address"></textarea></label>
          <label>City<input type="text" name="city" required maxlength="100" autocomplete="address-level2"></label>
          <label>State<input type="text" name="state" required maxlength="100" autocomplete="address-level1"></label>
        </div>
        <h2 class="payment-heading">Payment method</h2>
        <label class="payment-option"><input type="radio" name="payment_method" value="cod" checked><span><strong>Cash on Delivery</strong><small>Pay when your order is delivered.</small></span></label>
        <label class="payment-option"><input type="radio" name="payment_method" value="razorpay"><span><strong>Pay Online</strong><small>UPI, cards and other methods supported by the payment gateway.</small></span></label>
        <p class="small-note">Online payments remain disabled until valid Razorpay credentials are configured and tested.</p>
        <button class="btn btn-primary btn-full" type="submit">Place Order / Continue to Payment</button>
      </div>
      <aside class="order-summary"><h2>Your order</h2>
        <?php foreach ($items as $item): ?><div class="checkout-line"><span><?= e($item['name']) ?> × <?= (int)$item['quantity'] ?></span><strong><?= sugnayuMoney($item['line_total']) ?></strong></div><?php endforeach; ?>
        <hr><div><span>Subtotal</span><strong><?= sugnayuMoney(cartSubtotal()) ?></strong></div><div><span>Shipping</span><span>To be confirmed</span></div><div class="summary-total"><span>Current total</span><strong><?= sugnayuMoney(cartSubtotal()) ?></strong></div>
        <p class="small-note">This prototype currently uses zero shipping charge and no separately calculated tax. Confirm your business rules before launch.</p>
      </aside>
    </form>
  </section>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>
