<?php
require_once __DIR__ . '/bootstrap.php';
if (!isset($pageTitle)) $pageTitle = "SUGNAYU | Hair Care & Personal Care";
if (!isset($pageDescription)) $pageDescription = "Discover SUGNAYU, explore our brand story and find updates about our hair-care collection.";
if (!isset($activePage)) $activePage = "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="theme-color" content="#075638">
  <meta property="og:site_name" content="SUGNAYU">
  <meta property="og:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="icon" href="assets/images/sugnayu-logo.jpg">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <div class="announcement"><span class="announcement-spark">✳</span> A new SUGNAYU experience is taking shape <a href="shop.php">Explore the store <span aria-hidden="true">→</span></a></div>
  <header class="site-header">
    <div class="nav-wrap">
      <a class="brand" href="index.php" aria-label="SUGNAYU home"><img src="assets/images/sugnayu-logo.jpg" alt="SUGNAYU logo"><span class="brand-word">SUGNAYU<small>CARE FOR EVERYDAY</small></span></a>
      <button class="menu-toggle" aria-label="Open navigation menu" aria-expanded="false"><span></span><span></span><span></span></button>
      <nav class="main-nav" aria-label="Main navigation">
        <a class="<?= $activePage === 'home' ? 'active' : '' ?>" href="index.php">Home</a>
        <a class="<?= $activePage === 'shop' ? 'active' : '' ?>" href="shop.php">Shop</a>
        <a class="<?= $activePage === 'about' ? 'active' : '' ?>" href="about.php">Our Story</a>
        <a class="<?= $activePage === 'contact' ? 'active' : '' ?>" href="contact.php">Contact</a>
      </nav>
      <div class="nav-actions"><a class="nav-search" href="shop.php" aria-label="Explore products">⌕</a><a class="nav-cart" href="cart.php" aria-label="Shopping cart">🛒 <span><?= cartCount() ?></span></a></div>
    </div>
  </header>