<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ShopNest</title><link rel="stylesheet" href="style.css">
</head>
<body>
<?php
session_start();
require_once "config.php";
require_once "auth.php";
$user = current_user($conn);
$cartCount = 0;
$sql = "SELECT SUM(quantity) AS total FROM cart";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    $cartCount = $row['total'];
}
?>
<header>
<div class="container navbar">
<a class="logo" href="index.php">Shop<span>Nest</span>
</a>
<nav class="nav-links">
<a href="index.php">Shop</a>
<a href="contact.php">Contact</a>
</nav>
<div class="search">
<form action="index.php" method="get"><input name="search" type="search" placeholder="Search products" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
</form>
</div>
<div class="actions">
<a class="cart-icon" href="cart.php">🛒 <small>(<?= $cartCount ?>)</small></a>
<?php
if ($user) {
?>
<a class="btn btn-outline">
 Hi, <?= htmlspecialchars($user['name']) ?>
</a>
<a class="btn btn-outline" href="logout.php">
Sign Out
</a>
<?php
} else {
?>
<a class="btn btn-outline" href="signin.php">
    Sign In
</a>
<a class="btn btn-primary" href="signup.php">
Sign Up
</a>
<?php
}
?>
</div>
</div>
</header>
<?php
$messageSent = false;
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];
if ($name != "" && $email != "" && $subject != "" && $message != "") {
$sql = "INSERT INTO contacts (name, email, subject, message)
VALUES ('$name', '$email', '$subject', '$message')";
$result = $conn->query($sql);
if ($result) {
$messageSent = true;
} 
else {
$error = "Could not save your message.";
}
} 
else {
$error = "Please fill all fields.";
}
}
?>
<main>
<section class="page-head container">
<span class="badge">Get In Touch</span>
<h1>Contact Us</h1>
 <p>
 Send us a message — it will be saved in the MySQL database.
</p>
</section>
<section class="container contact-grid">
<div class="panel">
 <h2>Send us a message</h2>
 <p>
 Fill out the form below and we'll get back to you.
 </p>

<?php

if ($messageSent) {
?>

    <p style="background:#eaf8ee;padding:12px;border-radius:10px;margin-bottom:15px">
        ✓ Your message was submitted successfully.
    </p>

<?php
}

if ($error != "") {
?>

    <p style="background:#fff0f0;padding:12px;border-radius:10px;margin-bottom:15px">
        <?= htmlspecialchars($error) ?>
    </p>

<?php
}

?>
<form method="post">
<div class="form-grid">
<div class="field">
<label>Your Name</label>
<input name="name" type="text" required>
</div>
<div class="field">
<label>Your Email</label>
<input name="email" type="email" required>
</div>
</div>
<div class="field">
<label>Subject</label>
<input name="subject" type="text" required>
</div>
<div class="field">
<label>Your Message</label>
<textarea name="message" required></textarea>
</div>
<button class="btn btn-primary" type="submit">Send Message</button>
</form>
</div>
<div>
<div class="panel">
<h2>Contact Information</h2>
<div class="info-box">
<div class="info-icon">📍
</div>
<div>
<h3>Address</h3>
<p>Bashundhara R/A, Dhaka, Bangladesh</p>
</div>
</div>
<div class="info-box">
<div class="info-icon">☎
</div>
<div>
<h3>Phone</h3>
<p>+8801627623062</p>
</div>
</div>
<div class="info-box">
<div class="info-icon">✉
</div>
<div>
<h3>Email</h3>
<p>shopnest@gmail.com</p>
</div>
</div>
</div>
</div>
</section>
<section class="faq">
<div class="container">
<div class="page-head">
<span class="badge">FAQ</span>
<h2>Frequently Asked Questions</h2>
</div>
<div class="faq-grid">
<div class="faq-item">
<h3>What are your shipping policies?</h3>
<p>Free shipping inside Dhaka.</p>
</div>
<div class="faq-item">
<h3>How can I track my order?</h3>
<p>You'll receive tracking information by email after shipping.</p>
</div>
<div class="faq-item">
<h3>What is your return policy?</h3>
<p>Returns are accepted within 30 days.</p>
</div>
<div class="faq-item">
<h3>Do you offer international shipping?</h3>
<p>Yes, we ship worldwide.</p>
</div>
</div>
</div>
</section>
</main>
<footer>
<div class="newsletter">
<div class="container"><h2>Stay in the loop</h2><p>Subscribe to our newsletter for exclusive offers, new arrivals, and style inspiration.</p>
<form class="newsletter-form">
<input type="email" placeholder="Enter your email" required>
<button class="btn btn-primary">Subscribe</button>
</form>
</div>
</div>
<div class="container footer-main">
<div>
<a class="logo" href="index.php">Shop<span>Nest</span></a>
<p class="brand-text">Discover unique products that inspire your lifestyle. Quality craftsmanship meets modern design.</p>
<p class="contact-line">Bashundhara R/A, Dhaka, Bangladesh</p>
<p class="contact-line">+8801627623062</p>
<p class="contact-line">✉ shopnest@gmail.com</p>
</div>
<div>
<h4>Shop</h4>
<ul>
    <li>
        <a href="index.php">All Products</a>
    </li>
    <li>
        <a href="index.php">New Arrivals</a>
    </li>
    <li><a href="index.php">Sale</a>
    </li>
</ul>
</div>
<div>
<h4>Customer Care</h4>
<ul>
    <li>
        <a href="contact.php">Contact Us</a>
    </li>
    <li>
        <a href="#">Help Center</a>
    </li>
    <li>
        <a href="#">Shipping Info</a>
    </li>
</ul>
</div>
<div>
<h4>Company</h4>
    <ul>
        <li>
            <a href="#">About Us</a>
    </li>
    <li>
        <a href="#">Careers</a>
    </li>
    <li>
        <a href="#">Blog</a>
    </li>
</ul>
</div>
<div>
<h4>Legal</h4>
<ul>
    <li>
    <a href="#">Privacy Policy</a>
    </li>
    <li>
        <a href="#">Terms</a>
    </li>
</ul>
</div>
</div>
<div class="container copyright"><span>© 2026 ShopNest. All Rights Reserved.</span>
<span>Privacy · Terms · Cookies</span>
</div>
</footer>
</body>
</html>
