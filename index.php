<?php
$pageTitle = "SUGNAYU | Natural Hair Care";
$pageDescription = "Discover SUGNAYU hair oil and shampoo collection.";
$activePage = "home";

require_once __DIR__ . "/includes/bootstrap.php";

$homeProducts = sugnayuProducts();

$slides = [
  [
    "image" => "assets/images/hero-image-1.jpg",
    "eyebrow" => "HAIR CARE RITUALS",
    "title" => "Good Hair<br>Takes Time",
    "description" => "Discover the SUGNAYU hair-care collection.",
    "button" => "Explore Collection"
  ],
  [
    "image" => "assets/images/hero-image-2.jpg",
    "eyebrow" => "NEW ARRIVAL",
    "title" => "Stronger Hair<br>Healthier You",
    "description" => "Find your everyday hair-care essentials.",
    "button" => "Shop Now"
  ],
  [
    "image" => "assets/images/hero-image-3.jpg",
    "eyebrow" => "NATURE'S CARE FOR YOUR HAIR",
    "title" => "Healthy Hair<br>Naturally",
    "description" => "Explore the SUGNAYU hair-care collection.",
    "button" => "Discover Products"
  ]
];

include __DIR__ . "/includes/header.php";
?>

<main class="reference-home">

  <!-- HERO SLIDER -->
  <section class="ref-hero" id="refHero">
    <?php foreach ($slides as $i => $slide): ?>
      <article class="ref-slide <?= $i === 0 ? 'is-active' : '' ?>">
        <img src="<?= e($slide['image']) ?>"
          alt="<?= e(strip_tags($slide['title'])) ?>"
          class="ref-slide-image">

        <div class="ref-slide-shade"></div>

        <div class="ref-slide-copy">
          <span><?= e($slide['eyebrow']) ?></span>
          <h1><?= $slide['title'] ?></h1>
          <p><?= e($slide['description']) ?></p>
          <a href="shop.php" class="ref-button">
            <?= e($slide['button']) ?> <span>→</span>
          </a>
        </div>
      </article>
    <?php endforeach; ?>

    <button class="ref-arrow ref-prev" type="button"
      aria-label="Previous banner">‹</button>
    <button class="ref-arrow ref-next" type="button"
      aria-label="Next banner">›</button>

    <div class="ref-dots">
      <?php foreach ($slides as $i => $slide): ?>
        <button type="button"
          class="<?= $i === 0 ? 'is-active' : '' ?>"
          data-index="<?= $i ?>"
          aria-label="Show banner <?= $i + 1 ?>"></button>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- WHO WE ARE -->
  <section class="ref-about" id="about">
    <div class="ref-about-photo">
      <img src="assets/images/combine.jpg"
        alt="SUGNAYU hair oil and shampoo collection">
    </div>

    <div class="ref-about-text">
      <span class="ref-kicker">WHO WE ARE</span>
      <h2>Pure Care. Real Results.</h2>
      <p>
        At SUGNAYU, we believe hair care is an important
        part of your everyday routine. Discover our hair
        oil and shampoo collection and learn more about
        our approach to personal care.
      </p>
      <p>
        Explore our products and find useful information
        to help you choose the right products for your routine.
      </p>
      <a href="about.php" class="ref-button">Our Story →</a>
    </div>

    <div class="ref-about-points">
      <div><span>✿</span>
        <p>Thoughtful<br>Hair Care</p>
      </div>
      <div><span>◉</span>
        <p>Everyday<br>Essentials</p>
      </div>
      <div><span>♡</span>
        <p>Care for Your<br>Routine</p>
      </div>
    </div>
  </section>

  <!-- PRODUCT COLLECTION -->
  <section class="ref-products" id="products">
    <header class="ref-section-heading">
      <span class="ref-kicker">OUR HAIR CARE COLLECTION</span>
      <h2>Natural Solutions for Healthier Hair</h2>
    </header>

    <div class="ref-product-grid">
      <?php foreach ($homeProducts as $product): ?>
        <article class="ref-product-card">
          <a href="product.php?slug=<?= e($product['slug']) ?>"
            class="ref-product-photo">
            <?php if (!empty($product['image'])): ?>
              <img src="<?= e($product['image']) ?>"
                alt="<?= e($product['name']) ?>"
                loading="lazy">
            <?php else: ?>
              <span class="ref-image-placeholder">SUGNAYU</span>
            <?php endif; ?>
          </a>

          <div class="ref-product-info">
            <span class="ref-product-category">
              <?= e(strtoupper($product['category'])) ?>
            </span>

            <h3>
              <a href="product.php?slug=<?= e($product['slug']) ?>">
                <?= e($product['name']) ?>
              </a>
            </h3>

            <p><?= e($product['short_description']) ?></p>

            <div class="ref-price">
              <?= sugnayuMoney($product['price']) ?>
              <small><?= e($product['pack_size']) ?></small>
            </div>

            <div class="ref-product-buttons">
              <form method="post" action="cart-action.php">
                <?= csrfField() ?>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id"
                  value="<?= (int)$product['id'] ?>">
                <input type="hidden" name="quantity" value="1">
                <button class="ref-add-button" type="submit">
                  ♧ Add to Cart
                </button>
              </form>

              <form method="post" action="cart-action.php">
                <?= csrfField() ?>
                <input type="hidden" name="action" value="buy">
                <input type="hidden" name="product_id"
                  value="<?= (int)$product['id'] ?>">
                <input type="hidden" name="quantity" value="1">
                <button class="ref-buy-button" type="submit">
                  Buy Now →
                </button>
              </form>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <div class="ref-view-all">
      <a href="shop.php" class="ref-button">View All Products →</a>
    </div>
  </section>


  <!-- WHAT OUR CLIENTS SAY -->
  <section class="ref-reviews" id="reviews">
    <div class="ref-review-intro">
      <span class="ref-kicker">WHAT OUR CLIENTS SAY</span>
      <h2>Real People. Real Results.</h2>
      <p>Discover customer experiences with SUGNAYU hair-care products.</p>
      <a href="contact.php" class="ref-button">Share Your Experience →</a>
    </div>

    <article class="ref-review-card">
      <div class="ref-review-top">
        <img src="assets/images/customer-1.png" alt="Customer portrait">
        <div>
          <div class="ref-stars" aria-label="5 out of 5 stars">★★★★★</div>
        </div>
      </div>
      <p>“SUGNAYU has a clean and appealing presentation. I like having hair-care essentials together in one collection, making it easier to explore products for my routine.”</p>
      <div class="ref-review-author">
        <strong>Priti</strong>
        <small>Gurugram</small>
      </div>
    </article>

    <article class="ref-review-card">
      <div class="ref-review-top">
        <img src="assets/images/customer-2.png" alt="Customer portrait">
        <div>
          <div class="ref-stars" aria-label="5 out of 5 stars">★★★★★</div>
        </div>
      </div>
      <p>“I appreciate the elegant look of the SUGNAYU collection. The website makes it easy to browse the available hair-care products and learn more about the brand.”</p>
      <div class="ref-review-author">
        <strong>Sunita Yadav</strong>
        <small>Delhi</small>
      </div>
    </article>

    <article class="ref-review-card">
      <div class="ref-review-top">
        <img src="assets/images/customer-3.png" alt="Customer portrait">
        <div>
          <div class="ref-stars" aria-label="5 out of 5 stars">★★★★★</div>
        </div>
      </div>
      <p>“I've been looking for a simple hair-care routine, and SUGNAYU caught my attention. The packaging looks great, and I like the overall product presentation.”</p>
      <div class="ref-review-author">
        <strong>Umag Verma</strong>
        <small>Mumbai</small>
      </div>
    </article>
  </section>


  <!-- CUSTOMER STATISTICS -->
  <section class="ref-stats">
    <div class="ref-stat-item">
      <span class="ref-stat-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"
            stroke="currentColor" stroke-width="1.7"
            stroke-linecap="round" />
          <circle cx="10" cy="7" r="4"
            stroke="currentColor" stroke-width="1.7" />
          <path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"
            stroke="currentColor" stroke-width="1.7"
            stroke-linecap="round" />
        </svg>
      </span>
      <div>
        <strong>10K+</strong>
        <small>Happy Customers</small>
      </div>
    </div>

    <div class="ref-stat-item">
      <span class="ref-stat-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none">
          <path d="M20 4C11 4 5 7 5 13a5 5 0 0 0 5 5c6 0 9-6 10-14Z"
            stroke="currentColor" stroke-width="1.7"
            stroke-linejoin="round" />
          <path d="M3 21c3-6 7-9 13-13"
            stroke="currentColor" stroke-width="1.7"
            stroke-linecap="round" />
        </svg>
      </span>
      <div>
        <strong>100%</strong>
        <small>Natural Products</small>
      </div>
    </div>

    <div class="ref-stat-item">
      <span class="ref-stat-icon" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none">
          <rect x="3" y="5" width="18" height="16" rx="2"
            stroke="currentColor" stroke-width="1.7" />
          <path d="M7 3v4M17 3v4M3 10h18"
            stroke="currentColor" stroke-width="1.7"
            stroke-linecap="round" />
          <path d="m8 15 2 2 5-5"
            stroke="currentColor" stroke-width="1.7"
            stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </span>
      <div>
        <strong>5+</strong>
        <small>Years of Wellness</small>
      </div>
    </div>
  </section>


  <!-- TRUSTED BY LEADING BRANDS -->
  <section class="ref-trusted">
    <span class="ref-kicker">
      <h2>TRUSTED BY LEADING BRANDS</h2>
    </span>

    <div class="ref-brand-logos">
      <img src="assets/images/brand-amazon.jpg" alt="Amazon">
      <img src="assets/images/brand-flipkart.png" alt="Flipkart">
      <img src="assets/images/brand-nykaa.png" alt="Nykaa">
      <img src="assets/images/brand-bigbasket.png" alt="BigBasket">
      <img src="assets/images/brand-purple.jpg" alt="Purplle">
      <img src="assets/images/brand-meesho.png" alt="Meesho">
    </div>
  </section>

  <!-- SUGNAYU BENEFITS SECTION -->
  <section class="sugnayu-benefits">
    <div class="benefits-grid">
      <div class="benefit-item">
        <div class="benefit-icon">
          <svg viewBox="0 0 48 48" aria-hidden="true">
            <path d="M38 8C20 8 9 17 10 29c1 7 7 11 14 8C35 32 38 8 38 8Z" />
            <path d="M8 41c6-11 13-17 23-24" />
          </svg>
        </div>
        <h3>Thoughtful Hair Care</h3>
        <p>Explore hair-care essentials for your everyday routine.</p>
      </div>

      <div class="benefit-item">
        <div class="benefit-icon">
          <svg viewBox="0 0 48 48" aria-hidden="true">
            <path d="M5 13h25v23H5zM30 21h8l6 8v7H30z" />
            <circle cx="13" cy="38" r="4" />
            <circle cx="37" cy="38" r="4" />
            <path d="M9 19h16M9 25h12" />
          </svg>
        </div>
        <h3>Convenient Shopping</h3>
        <p>Browse the SUGNAYU collection in one simple place.</p>
      </div>

      <div class="benefit-item">
        <div class="benefit-icon">
          <svg viewBox="0 0 48 48" aria-hidden="true">
            <path d="M24 41S7 31 7 18a10 10 0 0 1 17-7 10 10 0 0 1 17 7c0 13-17 23-17 23Z" />
            <path d="m17 23 5 5 10-11" />
          </svg>
        </div>
        <h3>Carefully Selected</h3>
        <p>Find product details to help guide your choices.</p>
      </div>

      <div class="benefit-item">
        <div class="benefit-icon">
          <svg viewBox="0 0 48 48" aria-hidden="true">
            <path d="M8 16h32l-3 26H11L8 16Z" />
            <path d="M17 17v-5a7 7 0 0 1 14 0v5" />
            <path d="M18 27h12" />
          </svg>
        </div>
        <h3>Everyday Essentials</h3>
        <p>Discover products to explore for your personal-care routine.</p>
      </div>
    </div>
  </section>

</main>


<script>
  document.addEventListener("DOMContentLoaded", function() {
    const root = document.getElementById("refHero");
    if (!root) return;

    const slides = [...root.querySelectorAll(".ref-slide")];
    const dots = [...root.querySelectorAll(".ref-dots button")];
    const prev = root.querySelector(".ref-prev");
    const next = root.querySelector(".ref-next");

    if (slides.length < 2) return;

    let current = 0;
    let timer;

    function showSlide(index) {
      current = (index + slides.length) % slides.length;

      slides.forEach((slide, i) => {
        slide.classList.toggle("is-active", i === current);
        slide.setAttribute("aria-hidden", i !== current ? "true" : "false");
      });

      dots.forEach((dot, i) => {
        dot.classList.toggle("is-active", i === current);
        dot.setAttribute("aria-current", i === current ? "true" : "false");
      });
    }

    function startAutoplay() {
      clearInterval(timer);
      timer = setInterval(() => {
        showSlide(current + 1);
      }, 3000);
    }

    if (prev) {
      prev.addEventListener("click", () => {
        showSlide(current - 1);
        startAutoplay();
      });
    }

    if (next) {
      next.addEventListener("click", () => {
        showSlide(current + 1);
        startAutoplay();
      });
    }

    dots.forEach((dot, index) => {
      dot.addEventListener("click", () => {
        showSlide(index);
        startAutoplay();
      });
    });

    root.addEventListener("mouseenter", () => clearInterval(timer));
    root.addEventListener("mouseleave", startAutoplay);

    showSlide(0);
    startAutoplay();
  });
</script>

<?php include __DIR__ . "/includes/footer.php"; ?>