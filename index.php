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
        <h2>A closer look at our collection</h2>
      </div>
    </div>
    <div class="product-grid">
      <article class="product-card product-card-featured">
        <div class="placeholder-art placeholder-blue"><span class="placeholder-symbol">❋</span><span>THE COLLECTION<br>IS GROWING</span></div>
        <div class="product-info"><span class="product-category">HAIR CARE</span>
          <h3>SUGNAYU Shampoo</h3>
          <p>Product information and available pack sizes will be added here once confirmed.</p><span class="price-placeholder">Price to be announced</span>
        </div>
      </article>
      <article class="product-card product-card-placeholder">
        <div class="placeholder-art"><span class="placeholder-symbol">✳</span><span>YOUR NEXT<br>FAVOURITE</span></div>
        <div class="product-info"><span class="product-category">COMING SOON</span>
          <h3>More from SUGNAYU</h3>
          <p>This space is ready for another product when the catalogue is finalised.</p><span class="price-placeholder">Details coming soon</span>
        </div>
      </article>
      <article class="product-card product-card-placeholder">
        <div class="placeholder-art placeholder-blue"><span class="placeholder-symbol">❋</span><span>THE COLLECTION<br>IS GROWING</span></div>
        <div class="product-info"><span class="product-category">COMING SOON</span>
          <h3>Explore what’s next</h3>
          <p>We’ll share accurate product details here as they become available.</p><span class="price-placeholder">Details coming soon</span>
        </div>
      </article>
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