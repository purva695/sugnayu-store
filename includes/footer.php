<footer class="site-footer">
  <div class="footer-main">

    <div class="footer-brand">
      <a class="brand brand-footer" href="index.php">
        <img src="assets/images/sugnayu-logo.jpg" alt="SUGNAYU logo">
        <span class="brand-word">
          SUGNAYU
          <small>CARE FOR EVERYDAY</small>
        </span>
      </a>

      <p>
        Discover the SUGNAYU hair-care collection
        and find products for your everyday routine.
      </p>

      <div class="footer-socials">
        <a href="https://www.instagram.com/sugnayu.herbal/"
          target="_blank" rel="noopener noreferrer"
          aria-label="Instagram" title="Instagram">
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <rect x="3" y="3" width="18" height="18" rx="5"></rect>
            <circle cx="12" cy="12" r="4"></circle>
            <circle cx="17.5" cy="6.8" r="1"></circle>
          </svg>
        </a>

        <a href="mailto:contact@sugnayu.com"
          aria-label="Email SUGNAYU" title="Email">
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
            <path d="m3 6 9 7 9-7"></path>
          </svg>
        </a>

        <a href="https://wa.me/+91 70143 72162"
          target="_blank" rel="noopener noreferrer"
          aria-label="WhatsApp" title="WhatsApp">
          <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M20.5 11.7a8.5 8.5 0 0 1-12.6 7.5L3 20.5l1.3-4.7a8.5 8.5 0 1 1 16.2-4.1Z"></path>
            <path d="M8.5 8.2c.5 3 2.3 4.8 5.3 5.9l1.5-1.4 2 1c-.2 1.5-1.2 2.2-2.7 2.2-3.6-.3-7.8-4.5-8-8.1 0-1.4.7-2.4 2.1-2.6l1.1 2z"></path>
          </svg>
        </a>
      </div>
    </div>

    <div class="footer-links">
      <h3>Explore</h3>
      <a href="index.php">Home</a>
      <a href="shop.php">Shop All</a>
      <a href="about.php">Our Story</a>
      <a href="contact.php">Contact Us</a>
    </div>

    <div class="footer-links">
      <h3>Customer Care</h3>
      <a href="contact.php">Product Enquiries</a>
      <a href="shipping.php">Shipping Information</a>
      <a href="returns.php">Returns &amp; Refunds</a>
      <?php if (isCustomerLoggedIn()): ?>
        <a href="account.php">My Account</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="signup.php">Sign Up</a>
      <?php endif; ?>
    </div>

    <div class="footer-links">
      <h3>Information</h3>
      <a href="privacy.php">Privacy Policy</a>
      <a href="terms.php">Terms &amp; Conditions</a>
      <a href="cart.php">Shopping Cart</a>
    </div>

  </div>

  <div class="footer-bottom">
    <span>© <?= date('Y') ?> SUGNAYU. All rights reserved.</span>
    <span>Made with care for your everyday routine.</span>
  </div>
</footer>

<script src="assets/js/script.js?v=<?= filemtime(__DIR__ . '/../assets/js/script.js') ?>"></script>
</body>

</html>