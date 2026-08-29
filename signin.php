<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In | ShopNest</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once "config.php";
require_once "auth.php";
$user = current_user($conn);
if ($user) { header("Location: index.php"); exit; }

$cartCount = 0;
$result = $conn->query("SELECT COALESCE(SUM(quantity),0) AS total FROM cart");
if ($result) $cartCount = (int)$result->fetch_assoc()['total'];

$error = "";
$email = $_COOKIE['username'] ?? "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if ($email === '' || $password === '') {
        $error = "Please enter your email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password_hash FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

if ($row && password_verify($password, $row['password_hash'])) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['name'];

 if ($remember) {
 
     setcookie("username", $email, time() + (86400 * 30), "/");
            }

            header("Location: index.php");
            exit;
        } else {
            $error = "Incorrect email or password.";
        }
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
<a class="btn btn-outline" href="signin.php">Sign In</a>
<a class="btn btn-primary" href="signup.php">Sign Up</a>
</div>
</div>
</header>
<main class="container cart-page">
<div class="panel" style="max-width:480px;margin:50px auto">
<h1 style="font-size:32px;margin-bottom:8px">Sign In</h1>
<p style="color:var(--muted);margin-bottom:24px">Welcome back to ShopNest.</p>
<?php if($error): ?>
<p style="background:#fff0f0;padding:12px;border-radius:10px;margin-bottom:15px"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="post">
<div class="field">
<label>Email</label>
<input name="email" type="email" required value="<?= htmlspecialchars($email) ?>">
</div>
<div class="field">
<label>Password</label>
<input name="password" type="password" required>
</div>
<div class="field" style="display:flex;align-items:center;gap:8px">
<input type="checkbox" name="remember" id="remember" style="width:auto">
<label for="remember" style="margin:0">Remember me for 30 days</label>
</div>
<button class="btn btn-primary" type="submit" style="width:100%">Sign In</button>
</form>
<p style="margin-top:16px;font-size:14px;color:var(--muted)">Don't have an account? <a href="signup.php" style="color:var(--primary);font-weight:bold">Sign up</a></p>
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
    <li><a href="index.php">All Products</a></li>
    <li><a href="index.php">New Arrivals</a></li>
    <li><a href="index.php">Sale</a></li>
</ul>
</div>
<div>
<h4>Customer Care</h4>
<ul>
    <li><a href="contact.php">Contact Us</a></li>
    <li><a href="#">Help Center</a></li>
    <li><a href="#">Shipping Info</a></li>
</ul>
</div>
<div>
<h4>Company</h4>
<ul>
    <li><a href="#">About Us</a></li>
    <li><a href="#">Careers</a></li>
    <li><a href="#">Blog</a></li>
</ul>
</div>
<div>
<h4>Legal</h4>
<ul>
    <li><a href="#">Privacy Policy</a></li>
    <li><a href="#">Terms</a></li>
</ul>
</div>
</div>
<div class="container copyright"><span>© 2026 ShopNest. All Rights Reserved.</span>
<span>Privacy · Terms · Cookies</span>
</div>
</footer>
</body>
</html>
