<?php
$pageTitle = "SUGNAYU | Thoughtful Hair Care & Everyday Wellness";
$pageDescription = "Explore SUGNAYU, a hair-care and wellness brand. Discover our brand story and browse our growing collection as product details become available.";
$activePage = "home";
include __DIR__ . "/includes/header.php";
?>
<main>
  <section class="hero">
    <div class="hero-copy">
      <span class="eyebrow"><span class="eyebrow-dot"></span> MEET SUGNAYU</span>
      <h1>Care that feels<br><span>closer to nature.</span></h1>
      <p>Get to know SUGNAYU — a beauty and personal-care brand with a fresh perspective on everyday hair care. We're preparing our online store so you can explore our products in one place.</p>
      <div class="hero-actions">
        <a class="btn btn-primary" href="shop.php">Explore the collection <span aria-hidden="true">→</span></a>
        <a class="text-link" href="about.php">Discover our story</a>
      </div>
      <div class="hero-note"><span class="note-icon">✦</span><span>Our online collection is taking shape.<br><strong>More product details coming soon.</strong></span></div>
    </div>
    <div class="hero-art">
      <div class="art-orbit orbit-one"></div>
      <div class="art-orbit orbit-two"></div>
      <div class="art-leaf leaf-one"></div>
      <div class="art-leaf leaf-two"></div>
      <div class="hero-product-card">
        <div class="product-glow"></div>
        <span class="product-tag">SUGNAYU HAIR CARE</span>
      </div>
      <div class="floating-note"><span class="floating-star">✳</span><span><strong>Made for your routine</strong><small>Discover the SUGNAYU story</small></span></div>
      <span class="art-caption">CARE • CONFIDENCE • EVERYDAY</span>
    </div>
  </section>

  <section class="trust-strip" aria-label="Store information">
    <div><span class="trust-icon">✧</span><span><strong>A fresh brand experience</strong><small>Meet SUGNAYU</small></span></div>
    <div><span class="trust-icon">⌕</span><span><strong>Product details coming soon</strong><small>Clear information matters</small></span></div>
    <div><span class="trust-icon">♡</span><span><strong>Here to help</strong><small>Questions? Contact us</small></span></div>
  </section>

  <section class="section section-soft" id="collection">
    <div class="section-heading">
      <div><span class="eyebrow">THE SUGNAYU EDIT</span>
        <h2>Explore the SUGNAYU collection</h2>
      </div>
      <a class="text-link" href="shop.php">View all products →</a>
    </div>
    <?php $homeProducts = sugnayuProducts(); ?>
    <div class="product-grid">
      <?php foreach ($homeProducts as $product): ?>
        <article class="product-card">

          <a class="product-image-link"
            href="product.php?slug=<?= e($product['slug']) ?>">

            <?php if (!empty($product['image'])): ?>
              <img
                src="<?= e($product['image']) ?>"
                alt="<?= e($product['name']) ?>"
                class="product-image"
                loading="lazy">
            <?php else: ?>
              <div class="placeholder-art <?= $product['id'] % 2 ? 'placeholder-blue' : '' ?>">
                <span class="placeholder-symbol">✳</span>
                <span>SUGNAYU<br>PRODUCT IMAGE<br>COMING SOON</span>
              </div>
            <?php endif; ?>

          </a>

          <div class="product-info"><span class="product-category"><?= e(strtoupper($product['category'])) ?></span>
            <h3><a href="product.php?slug=<?= e($product['slug']) ?>"><?= e($product['name']) ?></a></h3>
            <p><?= e($product['short_description']) ?></p>
            <div class="product-price"><?= sugnayuMoney($product['price']) ?> <small><?= e($product['pack_size']) ?></small></div>
            <div class="product-actions">
              <form method="post" action="cart-action.php"><?= csrfField() ?><input type="hidden" name="action" value="add"><input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>"><input type="hidden" name="quantity" value="1"><button class="btn btn-outline" type="submit">Add to Cart</button></form>
              <form method="post" action="cart-action.php"><?= csrfField() ?><input type="hidden" name="action" value="buy"><input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>"><input type="hidden" name="quantity" value="1"><button class="btn btn-primary" type="submit">Buy Now</button></form>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="brand-story">
    <div class="story-visual"><img src="assets/images/sugnayu-logo.jpg" alt="SUGNAYU brand logo" class="story-logo"><span class="story-ring"></span></div>
    <div class="story-copy"><span class="eyebrow">A LITTLE ABOUT US</span>
      <h2>Getting to know<br><span>SUGNAYU.</span></h2>
      <p>Every personal-care routine is personal. SUGNAYU is building a space where you can learn about the brand, explore its products and find the information you need before making a choice.</p>
      <p>As our store develops, we'll add confirmed product details, usage information and ways to get in touch — all in one easy-to-navigate place.</p><a class="btn btn-outline" href="about.php">Read our story <span aria-hidden="true">→</span></a>
    </div>
  </section>

  <section class="help-banner">
    <div><span class="eyebrow">WE’RE HERE TO HELP</span>
      <h2>Have a question for SUGNAYU?</h2>
      <p>Contact us for brand or product enquiries. Our contact details can be finalised before the store goes live.</p>
    </div><a class="btn btn-light" href="contact.php">Get in touch <span aria-hidden="true">→</span></a>
  </section>
</main>
<?php include __DIR__ . "/includes/footer.php"; ?>