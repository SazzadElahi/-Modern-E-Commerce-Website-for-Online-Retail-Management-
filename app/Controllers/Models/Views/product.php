<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Product Details | ShopNest</title>
    <link rel="stylesheet" href="style.css">
</head>

<body><?php require __DIR__ . '/layout/header.php'; ?><main class="container product-detail">
        <div class="detail-info"><span class="badge"><?= htmlspecialchars($product['category_name'] ?? 'Product') ?></span>
            <h1><?= htmlspecialchars($product['name']) ?></h1>
            <div class="price">Tk:<?= number_format($product['price'], 2) ?></div>
            <p class="seller-text">Seller: <?= htmlspecialchars($product['shop_name'] ?? 'ShopNest') ?></p>
            <p class="stock-text">Stock available: <?= (int)$product['stock'] ?></p>
            <p class="description"><?= htmlspecialchars($product['description']) ?></p>
            <ul class="feature-list">
                <li>✓ High-Quality Products</li>
                <li>✓ Affordable Prices</li>
                <li>✓ Secure Checkout</li>
                <li>✓ Order Tracking</li>
            </ul><?php if ($product['stock'] > 0): ?><?php if ($user && $role === ''): ?><a class="btn btn-primary" href="cart.php?action=add&id=<?= $product['id'] ?>">🛒 Add to Cart</a><?php else: ?><a class="btn btn-primary" href="signin.php">Sign In to Buy</a><?php endif; ?><?php else: ?><button class="btn btn-outline" disabled>Out of Stock</button><?php endif; ?>
        </div>
    </main>
    <section class="container review-section">
        <div class="section-title">
            <h2>Customer Reviews</h2>
        </div><?php if ($user && $role === ''): ?><a class="btn btn-outline" href="review.php?product_id=<?= $product['id'] ?>">Write Review</a><?php endif; ?><div class="review-list"><?php if ($reviews->num_rows === 0): ?><p class="muted-text">No reviews yet.</p><?php endif; ?><?php while ($r = $reviews->fetch_assoc()): ?><div class="review-card"><strong><?= htmlspecialchars($r['name']) ?></strong><span class="rating">★ <?= (int)$r['rating'] ?>/5</span>
                    <p><?= htmlspecialchars($r['comment']) ?></p>
                </div><?php endwhile; ?></div>
    </section><?php require __DIR__ . '/layout/footer.php'; ?>
</body>

</html>
