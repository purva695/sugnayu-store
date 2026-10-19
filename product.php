<?php
require_once __DIR__ . '/includes/bootstrap.php';
$slug = trim($_GET['slug'] ?? '');
$product = null;
foreach (sugnayuProducts() as $p) if (hash_equals($p['slug'], $slug)) {
    $product = $p;
    break;
}
if (!$product) {
    http_response_code(404);
    $pageTitle = 'Product not found | SUGNAYU';
} else {
    $pageTitle = $product['name'] . ' | SUGNAYU';
}
$pageDescription = $product ? $product['short_description'] : 'The requested SUGNAYU product could not be found.';
$activePage = 'shop';
include __DIR__ . '/includes/header.php';
?>
<main class="commerce-page">
    <?php if (!$product): ?><section class="section commerce-wrap">
            <h1>Product not found</h1><a class="btn btn-primary" href="shop.php">Return to Shop</a>
        </section>
    <?php else: ?>
        <section class="section product-detail-layout">
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
            <div class="product-detail-copy">
                <div class="breadcrumb"><a href="index.php">Home</a><span>/</span><a href="shop.php">Shop</a><span>/</span><span><?= e($product['name']) ?></span></div>
                <span class="product-category"><?= e(strtoupper($product['category'])) ?></span>
                <h1><?= e($product['name']) ?></h1>
                <div class="detail-price"><?= sugnayuMoney($product['price']) ?></div>
                <p class="detail-pack"><?= e($product['pack_size']) ?></p>
                <p><?= e($product['description']) ?></p>
                <form method="post" action="cart-action.php" class="detail-buy-form"><?= csrfField() ?><input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>"><label>Quantity <input type="number" name="quantity" value="1" min="1" max="20"></label>
                    <div class="product-actions"><button class="btn btn-outline" name="action" value="add" type="submit">Add to Cart</button><button class="btn btn-primary" name="action" value="buy" type="submit">Buy Now</button></div>
                </form>
                <p class="small-note">Product image and final label information will be updated when approved brand assets are available.</p>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php include __DIR__ . '/includes/footer.php'; ?>