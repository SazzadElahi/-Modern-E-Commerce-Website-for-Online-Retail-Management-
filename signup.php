<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign Up | ShopNest</title>
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
$name = $email = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($name === '' || $email === '' || $password === '' || $confirm === '') {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "An account with that email already exists.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password_hash) VALUES (?,?,?)");
            $stmt->bind_param("sss", $name, $email, $hash);
            $stmt->execute();

            $_SESSION['user_id'] = $conn->insert_id;
            header("Location: index.php");
            exit;
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
<h1 style="font-size:32px;margin-bottom:8px">Create an Account</h1>
<p style="color:var(--muted);margin-bottom:24px">Join ShopNest to check out faster next time.</p>
<?php if($error): ?>
<p style="background:#fff0f0;padding:12px;border-radius:10px;margin-bottom:15px"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="post">
<div class="field">
<label>Full Name</label>
<input name="name" type="text" required value="<?= htmlspecialchars($name) ?>">
</div>
<div class="field">
<label>Email</label>
<input name="email" type="email" required value="<?= htmlspecialchars($email) ?>">
</div>
<div class="field">
<label>Password</label>
<input name="password" type="password" required minlength="6">
</div>
<div class="field">
<label>Confirm Password</label>
<input name="confirm_password" type="password" required minlength="6">
</div>
<button class="btn btn-primary" type="submit" style="width:100%">Create Account</button>
</form>
<p style="margin-top:16px;font-size:14px;color:var(--muted)">Already have an account? <a href="signin.php" style="color:var(--primary);font-weight:bold">Sign in</a></p>
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
