
<?php

/**
 * SUGNAYU product catalogue.
 * Demo catalogue for the cart and checkout prototype.
 * Products are currently stored in PHP, not loaded from MySQL.
 */

function sugnayuProducts(): array
{
    return [
        [
            'id' => 1,
            'name' => 'SUGNAYU Hair Fall Shampoo',
            'slug' => 'sugnayu-hair-fall-shampoo',
            'price' => 315.00,
            'pack_size' => '200 ml',
            'category' => 'Shampoo',
            'short_description' => 'Sulphate and paraben free shampoo.',
            'description' => 'SUGNAYU Hair Fall Shampoo, 200 ml. The packaging describes this product as sulphate and paraben free.',
            'image' => '',
            'status' => 1,
            'stock' => 100
        ],
        [
            'id' => 2,
            'name' => 'SUGNAYU Hair Oil with Brahmi & Bhringraj',
            'slug' => 'sugnayu-hair-oil-100-ml',
            'price' => 295.00,
            'pack_size' => '100 ml',
            'category' => 'Hair Oil',
            'short_description' => 'Hair oil featuring Brahmi and Bhringraj.',
            'description' => 'SUGNAYU Hair Oil, 100 ml, featuring Brahmi and Bhringraj.',
            'image' => '',
            'status' => 1,
            'stock' => 100
        ],
        [
            'id' => 3,
            'name' => 'SUGNAYU Sandal Rose Shampoo with Conditioner',
            'slug' => 'sugnayu-sandal-rose-shampoo-conditioner',
            'price' => 345.00,
            'pack_size' => '200 ml',
            'category' => 'Shampoo',
            'short_description' => 'Shampoo with conditioner for all hair types.',
            'description' => 'SUGNAYU Sandal Rose Shampoo with Conditioner. Confirm the final pack size and product label before publication.',
            'image' => '',
            'status' => 1,
            'stock' => 100
        ],
    ];
}

function sugnayuGetProductById(int $id): ?array
{
    foreach (sugnayuProducts() as $product) {
        if ((int) $product['id'] === $id) {
            return $product;
        }
    }

    return null;
}

function sugnayuMoney($amount): string
{
    return '₹' . number_format((float) $amount, 2);
}
