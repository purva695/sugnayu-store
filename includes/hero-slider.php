<?php
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
?>

<section class="ref-hero" id="pageHeroSlider">
    <?php foreach ($slides as $i => $slide): ?>
        <article class="ref-slide <?= $i === 0 ? 'is-active' : '' ?>">
            <img
                src="<?= e($slide['image']) ?>"
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
            <button
                type="button"
                class="<?= $i === 0 ? 'is-active' : '' ?>"
                data-index="<?= $i ?>"
                aria-label="Show banner <?= $i + 1 ?>">
            </button>
        <?php endforeach; ?>
    </div>
</section>