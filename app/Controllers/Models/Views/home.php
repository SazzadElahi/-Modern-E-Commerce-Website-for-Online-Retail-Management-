<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ShopNest</title>
    <link rel="stylesheet" href="style.css">
</head>

<body><?php require __DIR__ . '/layout/header.php'; ?>
    <main>
        <section class="hero container">
            <h1>Shop Everything. Live Better.</h1>
            <p>Quality products. Great prices. Easy shopping.</p>
        </section>
        <section class="container">
            <div class="section-title">
                <div><span class="badge">Featured Collection</span>
                    <h2>Browse Products</h2>
                </div>
            </div>
            <div class="category-links"><a class="btn btn-outline"
                    href="index.php">All</a><?php while ($c = $categories->fetch_assoc()): ?><a class="btn btn-outline"
                        href="index.php?category=<?= $c['category_id'] ?>"><?= htmlspecialchars($c['category_name']) ?></a><?php endwhile; ?>
            </div>
            <div class="product-grid"><?php if ($products->num_rows === 0): ?>
                    <div class="empty">
                        <h1>No products found</h1>
                        <p>Try another search or category.</p>
                    </div><?php endif; ?><?php while ($p = $products->fetch_assoc()): ?>
                    <article class="card">
                        <div class="card-body"><span
                                class="badge"><?= htmlspecialchars($p['category_name'] ?? 'Uncategorized') ?></span>
                            <h3><?= htmlspecialchars($p['name']) ?></h3>
                            <p class="seller-text">Seller: <?= htmlspecialchars($p['shop_name'] ?? 'ShopNest') ?></p>
                            <p class="rating">★ <?= number_format((float) $p['rating'], 1) ?></p>
                            <div class="price">Tk:<?= number_format($p['price'], 2) ?></div><?php if ($p['stock'] > 0): ?><a
                                    class="btn btn-primary" href="cart.php?action=add&id=<?= $p['id'] ?>">Add to
                                    Cart</a><?php else: ?><button class="btn btn-outline" disabled>Out of
                                    Stock</button><?php endif; ?>
                        </div>
                    </article><?php endwhile; ?>
            </div>
        </section>
    </main><?php require __DIR__ . '/layout/footer.php'; ?>
</body>

</html>
