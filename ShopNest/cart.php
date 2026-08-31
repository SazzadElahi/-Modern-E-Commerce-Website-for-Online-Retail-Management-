<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shopping Cart | ShopNest</title>
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
<?php endif;
 ?>
</div>
</div>
</header>
<?php
$action = $_GET['action'] ?? '';
$id = (int)($_GET['id'] ?? 0);

if ($action === 'add' && $id > 0) {
    $stmt = $conn->prepare("SELECT id FROM products WHERE id=?");
    $stmt->bind_param("i",$id); $stmt->execute();
    if ($stmt->get_result()->num_rows) {
        $stmt = $conn->prepare("SELECT id FROM cart WHERE product_id=?");
        $stmt->bind_param("i",$id); $stmt->execute();
        $existing = $stmt->get_result()->fetch_assoc();
        if ($existing) {
            $stmt = $conn->prepare("UPDATE cart SET quantity=quantity+1 WHERE id=?");
            $stmt->bind_param("i",$existing['id']); $stmt->execute();
        } else {
            $stmt = $conn->prepare("INSERT INTO cart(product_id,quantity) VALUES(?,1)");
            $stmt->bind_param("i",$id); $stmt->execute();
        }
    }
    header("Location: cart.php"); exit;
}
if ($action === 'remove' && $id > 0) {
    $stmt=$conn->prepare("DELETE FROM cart WHERE id=?"); $stmt->bind_param("i",$id); $stmt->execute();
    header("Location: cart.php"); exit;
}
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['update'])) {
    foreach ($_POST['qty'] ?? [] as $cartId=>$qty) {
        $cartId=(int)$cartId; $qty=max(1,(int)$qty);
        $stmt=$conn->prepare("UPDATE cart SET quantity=? WHERE id=?");
        $stmt->bind_param("ii",$qty,$cartId); $stmt->execute();
    }
    header("Location: cart.php"); exit;
}
$items = $conn->query("SELECT cart.id AS cart_id, cart.quantity, products.* FROM cart JOIN products ON products.id=cart.product_id");
$total=0;
?>
<main class="container cart-page">
<div class="page-head" style="text-align:left">
<h1>Shopping Cart</h1><p>Check your Product details.</p>
</div>
<div class="cart-layout">
<div>
<?php if ($items->num_rows===0): ?>
<div class="empty">
<h1>Your cart is empty</h1><p>Add a product to get started.</p>
<a class="btn btn-primary" href="index.php">Browse Products</a>
</div>
<?php else:
 ?>
<form method="post">
<?php
 while($item=$items->fetch_assoc()): $subtotal=$item['price']*$item['quantity']; $total+=$subtotal; ?>
<div class="cart-item">
<img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
<div style="flex:1">
<h3><?= htmlspecialchars($item['name']) ?></h3>
<p>Tk:<?= number_format($item['price'],2) ?> each</p>
<div class="price">Tk:<?= number_format($subtotal,2) 
?>
</div>
<label>Qty <input style="width:70px;padding:7px;border:1px solid #ddd;border-radius:7px" type="number" min="1" name="qty[<?= $item['cart_id'] ?>]" value="<?= $item['quantity'] ?>">
</label> 
<a href="cart.php?action=remove&id=<?= $item['cart_id'] ?>" class="btn btn-outline">Remove</a>
</div>
</div>
<?php endwhile; 
?>
<button class="btn btn-outline" name="update">Update Cart</button>
</form>
<br>
<a class="btn btn-outline" href="index.php">← Continue Shopping</a>
<?php endif; 
?>
</div>
<aside class="summary">
<h2>Order Summary</h2>
<div class="summary-row"><span>Subtotal</span><strong>Tk:<?= number_format($total,2) ?></strong>
</div>
<div class="summary-row"><span>Shipping</span><strong>Free</strong>
</div>
<div class="summary-row summary-total"><span>Total</span><strong>Tk:<?= number_format($total,2) ?></strong>
</div><a class="btn btn-primary" style="width:100%;margin-top:18px" href="checkout.php">Checkout</a>
</aside>
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