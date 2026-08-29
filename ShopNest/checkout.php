<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout | ShopNest</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once "config.php";
require_once "auth.php";
$user = current_user($conn);
$cartCount = 0;
$result = $conn->query("SELECT COALESCE(SUM(quantity),0) AS total FROM cart");
if ($result) $cartCount = (int)$result->fetch_assoc()['total'];
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
<input name="search" type="search" placeholder="Search products" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"></form>
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
</div>
</header>
<?php
$result = $conn->query("
    SELECT cart.quantity, products.id AS product_id, products.name, products.price
    FROM cart
    JOIN products ON products.id = cart.product_id
");
$cartItems = [];
$total = 0;
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
    $total += $row['price'] * $row['quantity'];
}

$success = false;
$error = "";
$orderId = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $total > 0) {
$name = trim($_POST['name'] ?? '');
$address = trim($_POST['address'] ?? '');
$phone = trim($_POST['phone'] ?? '');
if ($name === '' || $address === '' || $phone === '') {
        $error = "Please fill in all fields.";
    }
 else {
 $conn->begin_transaction();
    try {
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_address, customer_phone, total) VALUES (?,?,?,?)");
    $stmt->bind_param("sssd", $name, $address, $phone, $total);
    $stmt->execute();
    $orderId = $conn->insert_id;
    $itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?,?,?,?,?)");
foreach ($cartItems as $item) {
    $itemStmt->bind_param(
     "iisdi",
    $orderId,
    $item['product_id'],
    $item['name'],
    $item['price'],
    $item['quantity']
);
$itemStmt->execute();
 }

    $conn->query("DELETE FROM cart");
    $conn->commit();
    $success = true;
        } 
catch (Exception $e) {
    $conn->rollback();
    $error = "Something went wrong while placing your order. Please try again.";
 }
}
}
?>
<main class="container cart-page">
<div class="page-head">
<h1>Checkout</h1>
<p>Simply check you Product and Price .</p>
</div>
<div class="panel" style="max-width:650px;margin:auto">
<?php
if($success): 
?>
<div class="empty"><h1>Order placed!</h1><p>Thank you for shopping with ShopNest.<?php if($orderId): ?> Your order number is <strong>#<?= $orderId ?></strong>.<?php endif; ?></p><a class="btn btn-primary" href="index.php">Back to Shop</a></div>
<?php elseif($total<=0): ?><div class="empty"><h1>No items to checkout</h1><a class="btn btn-primary" href="index.php">Shop Now</a></div>
<?php else: ?>
<h2>Order Total: Tk:<?= number_format($total,2) ?>
</h2>
<?php if($error): ?>
<p style="background:#fff0f0;padding:12px;border-radius:10px;margin-bottom:15px"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="post">
<div class="field">
<label>Full Name</label>
<input name="name" required value="<?= htmlspecialchars($_POST['name'] ?? ($user['name'] ?? '')) ?>">
</div>
<div class="field">
<label>Address</label>
<textarea name="address" required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
</div>
<div class="field">
<label>Phone</label>
<input name="phone" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
</div>
<button class="btn btn-primary" type="submit">Place Order</button>
</form>
<?php endif; 
?>
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