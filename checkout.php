<?php
require_once __DIR__ . '/includes/auth.php';
requireCustomerLogin();
?>
<?php
require_once __DIR__ . '/includes/bootstrap.php';
$pageTitle = "Checkout | SUGNAYU";
$pageDescription = "Enter your delivery details and choose a payment method for your SUGNAYU order.";
$items = cartItems();
if (!$items) {
  header('Location: cart.php');
  exit;
}
// Fetch saved addresses for the logged-in customer.
$customer = currentCustomer();
$email = $customer['email'];

$addressStmt = $pdo->prepare("
    SELECT id, full_name, phone, address_line, landmark,
           city, state, postal_code, address_type, is_default
    FROM customer_addresses
    WHERE customer_email = ?
    ORDER BY is_default DESC, id DESC
");
$addressStmt->execute([$email]);
$savedAddresses = $addressStmt->fetchAll(PDO::FETCH_ASSOC);
include __DIR__ . '/includes/header.php';
?>
<main class="commerce-page">
  <section class="page-hero compact-hero"><span class="eyebrow">ALMOST THERE</span>
    <h1>Secure <span>checkout.</span></h1>
    <p>Enter your delivery details and choose how you would like to pay.</p>
  </section>
  <section class="section commerce-wrap">
    <form class="checkout-layout" method="post" action="place-order.php" id="checkoutForm">
      <div class="checkout-fields">
        <h2>Delivery information</h2>
        <?= csrfField() ?>

        <?php if (!empty($savedAddresses)): ?>

          <h2>Choose Delivery Address</h2>
          <p class="small-note">Select a saved address for faster checkout.</p>

          <?php foreach ($savedAddresses as $savedAddress): ?>
            <label class="payment-option saved-address-option">
              <input
                type="radio"
                name="saved_address_id"
                value="<?= (int)$savedAddress['id'] ?>"
                <?= !empty($savedAddress['is_default']) ? 'checked' : '' ?>
                required
                onchange="toggleNewAddress(false)">

              <span>
                <strong>
                  <?= e($savedAddress['address_type']) ?>
                  <?= !empty($savedAddress['is_default']) ? ' (Default)' : '' ?>
                </strong>
                <br>
                <?= e($savedAddress['full_name']) ?> ·
                <?= e($savedAddress['phone']) ?>
                <br>
                <?= e($savedAddress['address_line']) ?>
                <?php if (!empty($savedAddress['landmark'])): ?>
                  , <?= e($savedAddress['landmark']) ?>
                <?php endif; ?>
                <br>
                <?= e($savedAddress['city']) ?>,
                <?= e($savedAddress['state']) ?> -
                <?= e($savedAddress['postal_code']) ?>
              </span>
            </label>
          <?php endforeach; ?>

          <label class="payment-option">
            <input
              type="radio"
              name="saved_address_id"
              value="new"
              required
              onchange="toggleNewAddress(true)">
            <span><strong>+ Add New Address</strong></span>
          </label>

        <?php endif; ?>

        <div id="newAddressFields"
          <?= !empty($savedAddresses) ? 'hidden' : '' ?>>

          <h2><?= !empty($savedAddresses) ? 'New Delivery Address' : 'Delivery Information' ?></h2>

          <div class="form-grid">
            <label>Full name
              <input type="text" name="customer_name"
                maxlength="120" autocomplete="name"
                <?= empty($savedAddresses) ? 'required' : '' ?>>
            </label>

            <label>Mobile number
              <input type="tel" name="phone"
                pattern="[6-9][0-9]{9}" minlength="10"
                maxlength="10" autocomplete="tel"
                placeholder="10-digit mobile number"
                <?= empty($savedAddresses) ? 'required' : '' ?>>
            </label>

            <label>PIN code
              <input type="text" name="pincode"
                pattern="[0-9]{6}" maxlength="6"
                autocomplete="postal-code"
                <?= empty($savedAddresses) ? 'required' : '' ?>>
            </label>

            <label class="full-field">House no., street, area
              <textarea name="address" rows="3"
                maxlength="500" autocomplete="street-address"
                <?= empty($savedAddresses) ? 'required' : '' ?>></textarea>
            </label>

            <label>City
              <input type="text" name="city" maxlength="100"
                autocomplete="address-level2"
                <?= empty($savedAddresses) ? 'required' : '' ?>>
            </label>

            <label>State
              <input type="text" name="state" maxlength="100"
                autocomplete="address-level1"
                <?= empty($savedAddresses) ? 'required' : '' ?>>
            </label>
          </div>
        </div>

        <script>
          function toggleNewAddress(show) {
            const section = document.getElementById('newAddressFields');
            section.hidden = !show;

            section.querySelectorAll('input, textarea').forEach(function(field) {
              field.required = show;
            });
          }
        </script>

        <h2 class="payment-heading">Payment method</h2>
        <label class="payment-option"><input type="radio" name="payment_method" value="cod" checked><span><strong>Cash on Delivery</strong><small>Pay when your order is delivered.</small></span></label>
        <label class="payment-option"><input type="radio" name="payment_method" value="razorpay"><span><strong>Pay Online</strong><small>UPI, cards and other methods supported by the payment gateway.</small></span></label>
        <p class="small-note">Online payments remain disabled until valid Razorpay credentials are configured and tested.</p>
        <button class="btn btn-primary btn-full" type="submit">Place Order / Continue to Payment</button>
      </div>
      <aside class="order-summary">
        <h2>Your order</h2>
        <?php foreach ($items as $item): ?><div class="checkout-line"><span><?= e($item['name']) ?> × <?= (int)$item['quantity'] ?></span><strong><?= sugnayuMoney($item['line_total']) ?></strong></div><?php endforeach; ?>
        <hr>
        <div><span>Subtotal</span><strong><?= sugnayuMoney(cartSubtotal()) ?></strong></div>
        <div><span>Shipping</span><span>To be confirmed</span></div>
        <div class="summary-total"><span>Current total</span><strong><?= sugnayuMoney(cartSubtotal()) ?></strong></div>
        <p class="small-note">This prototype currently uses zero shipping charge and no separately calculated tax. Confirm your business rules before launch.</p>
      </aside>
    </form>
  </section>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>