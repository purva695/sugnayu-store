<?php
$pageTitle = "Contact SUGNAYU | Customer Enquiries";
$pageDescription = "Contact SUGNAYU for general brand and product enquiries. Official contact details will be published here.";
$activePage = "contact";
include __DIR__ . "/includes/header.php";
?>
<main>
  <section class="page-hero"><span class="eyebrow">LET'S CONNECT</span>
    <h1>We're here to<br><span>help you find answers.</span></h1>
    <p>Have a question about SUGNAYU or our upcoming collection? This page is being prepared for customer enquiries.</p>
    <div class="breadcrumb"><a href="index.php">Home</a><span>/</span><span>Contact</span></div>
  </section>
  <section class="section contact-layout">
    <div class="contact-intro"><span class="eyebrow">CUSTOMER CARE</span>
      <h2>Good conversations start with clear information.</h2>
      <p>Our official email, phone number and business address will be added after confirmation. We don't want to publish contact details that haven't been verified.</p>
      <div class="contact-card"><span class="contact-card-icon">✉</span>
        <div><strong>Product & brand enquiries</strong>
          <p>Official customer support details coming soon.</p>
        </div>
      </div>
      <div class="contact-card"><span class="contact-card-icon">⌂</span>
        <div><strong>Business information</strong>
          <p>Verified company details will be published here.</p>
        </div>
      </div>
    </div>
    <form class="contact-form" id="contactPreviewForm">
      <h2>Send an enquiry</h2>
      <p>This is a visual form preview. It will send messages after a backend and verified business email are configured.</p><label for="name">Your name</label><input id="name" name="name" placeholder="Enter your name" required><label for="email">Email address</label><input id="email" name="email" type="email" placeholder="you@example.com" required><label for="topic">What is your enquiry about?</label><select id="topic" name="topic">
        <option>General enquiry</option>
        <option>Product information</option>
        <option>Order support</option>
        <option>Business enquiry</option>
      </select><label for="message">Message</label><textarea id="message" name="message" rows="4" placeholder="How can we help?" required></textarea><button class="btn btn-primary" type="submit">Preview form <span aria-hidden="true">→</span></button>
      <p class="form-status" id="formStatus" aria-live="polite"></p>
    </form>
  </section>
</main>
<?php include __DIR__ . "/includes/footer.php"; ?>