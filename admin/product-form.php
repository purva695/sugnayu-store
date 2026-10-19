<?php
require_once __DIR__ . '/common.php';
admin_require_login();

$id = (int) ($_GET['id'] ?? 0);
$isEdit = $id > 0;
$error = '';

$product = [
    'name' => '',
    'slug' => '',
    'category_id' => '',
    'category_name' => '',
    'description' => '',
    'price' => '',
    'stock' => 0,
    'image' => '',
    'status' => 'active'
];

/* Load existing product */
if ($isEdit) {
    $stmt = $pdo->prepare(
        'SELECT p.*, c.name AS category_name
         FROM products p
         LEFT JOIN categories c ON c.id = p.category_id
         WHERE p.id = ?'
    );

    $stmt->execute([$id]);
    $found = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$found) {
        admin_flash('Product not found.', 'error');
        header('Location: products.php');
        exit;
    }

    $product = $found;
}

/* Handle form submission */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_csrf_check();

    $product['name'] = trim($_POST['name'] ?? '');
    $product['slug'] = trim($_POST['slug'] ?? '');
    $product['category_name'] = trim($_POST['category_name'] ?? '');
    $product['description'] = trim($_POST['description'] ?? '');
    $product['price'] = (float) ($_POST['price'] ?? 0);
    $product['stock'] = (int) ($_POST['stock'] ?? 0);
    $product['image'] = trim($_POST['image'] ?? '');
    $product['status'] = $_POST['status'] ?? 'active';

    if (
        $product['name'] === '' ||
        $product['slug'] === '' ||
        !is_numeric($_POST['price'] ?? '') ||
        $product['price'] < 0 ||
        !ctype_digit((string) ($_POST['stock'] ?? '')) ||
        !in_array($product['status'], ['active', 'inactive'], true)
    ) {
        $error = 'Please complete required fields and enter valid price and stock values.';
    } else {
        try {
            $pdo->beginTransaction();

            /* Find or create category */
            $categoryId = null;
            $categoryName = $product['category_name'];

            if ($categoryName !== '') {
                $stmt = $pdo->prepare(
                    'SELECT id FROM categories
                     WHERE LOWER(name) = LOWER(?)
                     LIMIT 1'
                );
                $stmt->execute([$categoryName]);

                $categoryId = $stmt->fetchColumn();

                /* Create a new category if it does not exist */
                if (!$categoryId) {
                    $categorySlug = strtolower($categoryName);
                    $categorySlug = preg_replace(
                        '/[^a-z0-9]+/',
                        '-',
                        $categorySlug
                    );
                    $categorySlug = trim($categorySlug, '-');

                    if ($categorySlug === '') {
                        $categorySlug = 'category';
                    }

                    $baseSlug = $categorySlug;
                    $counter = 1;

                    /* Ensure category slug is unique */
                    while (true) {
                        $check = $pdo->prepare(
                            'SELECT id FROM categories WHERE slug = ? LIMIT 1'
                        );
                        $check->execute([$categorySlug]);

                        if (!$check->fetchColumn()) {
                            break;
                        }

                        $categorySlug = $baseSlug . '-' . $counter;
                        $counter++;
                    }

                    $stmt = $pdo->prepare(
                        'INSERT INTO categories (name, slug, description)
                         VALUES (?, ?, ?)'
                    );

                    $stmt->execute([
                        $categoryName,
                        $categorySlug,
                        ''
                    ]);

                    $categoryId = (int) $pdo->lastInsertId();
                } else {
                    $categoryId = (int) $categoryId;
                }
            }

            /* Update existing product */
            if ($isEdit) {
                $stmt = $pdo->prepare(
                    'UPDATE products
                     SET category_id = ?,
                         name = ?,
                         slug = ?,
                         description = ?,
                         price = ?,
                         stock = ?,
                         image = ?,
                         status = ?
                     WHERE id = ?'
                );

                $stmt->execute([
                    $categoryId,
                    $product['name'],
                    $product['slug'],
                    $product['description'],
                    $product['price'],
                    $product['stock'],
                    $product['image'],
                    $product['status'],
                    $id
                ]);

            } else {
                /* Insert new product */
                $stmt = $pdo->prepare(
                    'INSERT INTO products
                     (category_id, name, slug, description, price, stock, image, status)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
                );

                $stmt->execute([
                    $categoryId,
                    $product['name'],
                    $product['slug'],
                    $product['description'],
                    $product['price'],
                    $product['stock'],
                    $product['image'],
                    $product['status']
                ]);
            }

            $pdo->commit();

            admin_flash(
                $isEdit
                    ? 'Product updated successfully.'
                    : 'Product added successfully.'
            );

            header('Location: products.php');
            exit;

        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            error_log($e->getMessage());

            $error = $e instanceof RuntimeException
                ? $e->getMessage()
                : 'Could not save product. Check that the slug is unique and the database fields match.';
        }
    }
}

/* Render page */
admin_render_start($isEdit ? 'Edit product' : 'Add product');

echo '<section class="panel form-panel">';

if ($error !== '') {
    echo '<div class="alert error">' . admin_e($error) . '</div>';
}

echo '<form method="post" class="admin-form">';

echo '<input type="hidden" name="csrf_token" value="' .
    admin_e(admin_csrf_token()) . '">';

echo '<div class="form-grid">';

/* Product name */
echo '<div class="field wide">
<label>Product name *</label>
<input name="name" required maxlength="200" value="' .
    admin_e($product['name']) . '">
</div>';

/* Slug */
echo '<div class="field">
<label>URL slug *</label>
<input name="slug" required maxlength="220" value="' .
    admin_e($product['slug']) . '"
    placeholder="sugnayu-product-name">

</div>';

/* Category text input */
echo '<div class="field">
<label>Category</label>
<input type="text" name="category_name" maxlength="150"
    value="' . admin_e($product['category_name'] ?? '') . '"
    placeholder="e.g. Shapoo, Hair Oil,">

</div>';

/* Price */
echo '<div class="field">
<label>Price (₹) *</label>
<input type="number" name="price" min="0" step="0.01"
    required value="' . admin_e($product['price']) . '">
</div>';

/* Stock */
echo '<div class="field">
<label>Stock quantity *</label>
<input type="number" name="stock" min="0" step="1"
    required value="' . admin_e($product['stock']) . '">
</div>';

/* Image */
echo '<div class="field wide">
<label>Image path / URL</label>
<input name="image" maxlength="255"
    value="' . admin_e($product['image']) . '"
    placeholder="assets/images/product.jpg">
<small>Use an image path or URL supported by your storefront.</small>
</div>';

/* Description */
echo '<div class="field wide">
<label>Description</label>
<textarea name="description" rows="5">' .
    admin_e($product['description']) . '</textarea>
</div>';

/* Status */
echo '<div class="field">
<label>Visibility</label>
<select name="status">
<option value="active" ' .
    ($product['status'] === 'active' ? 'selected' : '') .
    '>Active</option>
<option value="inactive" ' .
    ($product['status'] === 'inactive' ? 'selected' : '') .
    '>Inactive</option>
</select>
</div>';

echo '</div>';

echo '<div class="form-actions">
<a class="btn secondary" href="products.php">Cancel</a>
<button class="btn primary" type="submit">' .
    ($isEdit ? 'Save changes' : 'Add product') .
    '</button>
</div>';

echo '</form>';
echo '</section>';

admin_render_end();
