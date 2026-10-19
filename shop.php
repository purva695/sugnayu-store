<?php
$pageTitle = "Shop SUGNAYU Hair Care Products";
$pageDescription = "Explore SUGNAYU shampoo and hair oil. Browse product information and shop the current hair-care collection.";
$activePage = "shop";
require_once __DIR__ . '/includes/bootstrap.php';
include __DIR__ . "/includes/header.php";
$products = sugnayuProducts();
?>
<main>
  <section class="page-hero"><span class="eyebrow">THE SUGNAYU COLLECTION</span>
    <h1>Find your place in<br><span>the everyday ritual.</span></h1>
    <p>Explore the SUGNAYU hair-care collection. Product information is based on the packaging details currently available.</p>
    <div class="breadcrumb"><a href="index.php">Home</a><span>/</span><span>Shop</span></div>
  </section>
  <section class="section shop-section">
    <div class="shop-toolbar"><span><strong>Shop all products</strong><small><?= count($products) ?> products in the current catalogue</small></span></div>
    <div class="product-grid">
      <?php foreach ($products as $product): ?>
        <article class="product-card">
          <a class="product-image-link" href="product.php?slug=<?= e($product['slug']) ?>">
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
          <div class="product-info">
            <span class="product-category"><?= e(strtoupper($product['category'])) ?></span>
            <h3><a href="product.php?slug=<?= e($product['slug']) ?>"><?= e($product['name']) ?></a></h3>
            <p><?= e($product['short_description']) ?></p>
            <div class="product-price"><?= sugnayuMoney($product['price']) ?> <small><?= e($product['pack_size']) ?></small></div>
            <div class="product-actions">
              <form method="post" action="cart-action.php">
                <?= csrfField() ?><input type="hidden" name="action" value="add"><input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>"><input type="hidden" name="quantity" value="1">
                <button class="btn btn-outline" type="submit">Add to Cart</button>
              </form>
              <form method="post" action="cart-action.php">
                <?= csrfField() ?><input type="hidden" name="action" value="buy"><input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>"><input type="hidden" name="quantity" value="1">
                <button class="btn btn-primary" type="submit">Buy Now</button>
              </form>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </section>
</main>
<?php include __DIR__ . "/includes/footer.php"; ?>