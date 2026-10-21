<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/auth.php';

if (!isset($pageTitle)) {
  $pageTitle = "SUGNAYU | Hair Care & Personal Care";
}
if (!isset($pageDescription)) {
  $pageDescription = "Discover SUGNAYU and explore our hair-care collection.";
}
if (!isset($activePage)) {
  $activePage = "";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta name="description"
    content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">

  <meta name="theme-color" content="#123c29">

  <meta property="og:site_name" content="SUGNAYU">
  <meta property="og:title"
    content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description"
    content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">

  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

  <link rel="icon" href="assets/images/sugnayu-logo.jpg">

  <link rel="stylesheet"
    href="assets/css/style.css?v=<?= filemtime(__DIR__ . '/../assets/css/style.css') ?>">
</head>

<body>

  <div class="announcement">
    <span class="announcement-spark">✳</span>
    <span>Thoughtful hair care for your everyday routine</span>
    <a href="shop.php">Explore the store →</a>
  </div>

  <header class="site-header">
    <div class="nav-wrap">

      <!-- LOGO: LEFT -->
      <a class="brand" href="index.php" aria-label="SUGNAYU home">
        <img src="assets/images/sugnayu-logo.jpg" alt="SUGNAYU logo">

        <span class="brand-word">
          SUGNAYU
          <small>CARE FOR EVERYDAY</small>
        </span>
      </a>


      <!-- SEARCH AREA -->
      <div class="header-search-container">

        <button
          type="button"
          class="mobile-search-toggle"
          id="mobileSearchToggle"
          aria-label="Open search"
          aria-expanded="false"
          aria-controls="mobileSearchForm">

          <svg viewBox="0 0 24 24"
            width="23"
            height="23"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round">
            <circle cx="11" cy="11" r="7"></circle>
            <path d="m16 16 4 4"></path>
          </svg>
        </button>

        <form
          class="nav-search-form header-search-always"
          id="mobileSearchForm"
          action="shop.php"
          method="get">

          <input
            type="search"
            name="q"
            placeholder="Search for products..."
            aria-label="Search products"
            value="<?= e($_GET['q'] ?? '') ?>">

          <button type="submit" aria-label="Search products">
            <svg viewBox="0 0 24 24"
              width="21"
              height="21"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round">
              <circle cx="11" cy="11" r="7"></circle>
              <path d="m16 16 4 4"></path>
            </svg>
          </button>
        </form>

      </div>

      <!-- MENU, ACCOUNT AND CART: RIGHT -->
      <div class="header-tools">

        <button
          class="menu-toggle"
          id="menuToggle"
          type="button"
          aria-label="Open navigation menu"
          aria-expanded="false"
          aria-controls="mainNavigation">

          <span></span>
          <span></span>
          <span></span>
        </button>

        <!-- ACCOUNT DROPDOWN -->
        <details class="header-account">

          <summary aria-label="My account" title="My Account">
            <svg viewBox="0 0 24 24"
              width="24"
              height="24"
              fill="none"
              stroke="currentColor"
              stroke-width="1.7"
              stroke-linecap="round"
              stroke-linejoin="round">

              <circle cx="12" cy="8" r="4"></circle>
              <path d="M4 21v-2a8 8 0 0 1 16 0v2"></path>
            </svg>
          </summary>

          <div class="account-dropdown">
            <?php if (isCustomerLoggedIn()): ?>
              <a href="account.php">My Account</a>
            <?php else: ?>
              <a href="login.php">Login</a>
              <a href="signup.php">Sign Up</a>
            <?php endif; ?>
          </div>

        </details>

        <!-- SHOPPING CART -->
        <a class="nav-cart"
          href="cart.php"
          aria-label="Shopping cart">

          <svg viewBox="0 0 24 24"
            width="25"
            height="25"
            fill="none"
            stroke="currentColor"
            stroke-width="1.7"
            stroke-linecap="round"
            stroke-linejoin="round">

            <path d="M3 3h2l2.4 12.2a2 2 0 0 0 2 1.6h8.8a2 2 0 0 0 2-1.6L22 7H6"></path>
            <circle cx="10" cy="21" r="1"></circle>
            <circle cx="19" cy="21" r="1"></circle>
          </svg>

          <span class="cart-count"><?= cartCount() ?></span>
        </a>

      </div>

      <!-- DROPDOWN NAVIGATION -->
      <nav class="main-nav"
        id="mainNavigation"
        aria-label="Main navigation">

        <a class="<?= $activePage === 'home' ? 'active' : '' ?>"
          href="index.php">Home</a>

        <a class="<?= $activePage === 'shop' ? 'active' : '' ?>"
          href="shop.php">Shop</a>

        <a class="<?= $activePage === 'about' ? 'active' : '' ?>"
          href="about.php">Our Story</a>

        <a class="<?= $activePage === 'contact' ? 'active' : '' ?>"
          href="contact.php">Contact</a>

      </nav>

    </div>
  </header>