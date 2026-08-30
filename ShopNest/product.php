<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ShopNest</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php
session_start();
require_once "config.php";
require_once "auth.php";
$user = current_user($conn);
$cartCount = 0;
$result = $conn->query("SELECT SUM(quantity) AS total FROM cart");
if ($result) {
$row = $result->fetch_assoc();
$cartCount = $row['total'];
if ($cartCount == NULL) {
        $cartCount = 0;
}
}
?>
<header>
<div class="container navbar">
<a class="logo" href="index.php">Shop<span>Nest</span></a>
<nav class="nav-links">
<a href="index.php">Shop</a>
<a href="contact.php">Contact</a>
</nav>
<div class="search">
<form action="index.php" method="get">
<input name="search" type="search" placeholder="Search products" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
</form>
</div>
<div class="actions">
<a class="cart-icon" href="cart.php">🛒 <small>(<?= $cartCount ?>)</small></a>
<?php if ($user): ?>
<a class="btn btn-outline">Hi, <?= htmlspecialchars($user['name']) ?></a>
<a class="btn btn-outline" href="logout.php">Sign Out</a>
<?php else: ?>
<a class="btn btn-outline" href="signin.php">Sign In</a>
<a class="btn btn-primary" href="signup.php">Sign Up</a>
<?php endif; ?>
</div>
</div></header>
<?php
$id = 0;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);
$p = $result->fetch_assoc();
if (!$p) {
    header("Location: index.php");
    exit();
}
?>
<main class="container product-detail">
<div class="detail-image">
<img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
</div>
<div class="detail-info">
<span class="badge">Premium Product</span>
<h1><?= htmlspecialchars($p['name']) ?></h1>
<div class="price">
Tk:<?= number_format($p['price'],2) ?>
</div>
<p class="description"><?= htmlspecialchars($p['description']) ?>
</p>
<ul class="feature-list">
    <li>
        ✓ High-Quality Products
    </li>
    <li>
        ✓ Affordable Prices
    </li>
    <li>
        ✓ Trusted Quality
    </li>
    <li>
        ✓ Fast & Easy Shopping
    </li>
    </ul>
<a class="btn btn-primary" href="cart.php?action=add&id=<?= $p['id'] ?>">🛒 Add to Cart</a>
</div>
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
