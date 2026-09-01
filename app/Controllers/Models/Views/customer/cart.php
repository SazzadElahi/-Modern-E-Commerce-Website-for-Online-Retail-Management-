<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Shopping Cart | ShopNest</title>
    <link rel="stylesheet" href="style.css">
</head>

<body><?php require __DIR__ . '/../layout/header.php'; ?><main class="container cart-page">
        <div class="page-head" style="text-align:center">
            <h1>Shopping Cart</h1>
            <p>Review your products before checkout.</p>
        </div>
        <div class="cart-layout style="text-align:center"">
            <div><?php $total = 0;
                    if ($items->num_rows === 0): ?>
                    <div class="empty"  >
                        <h1>Your cart is empty</h1>
                        <p>Add a product to get started.</p>
                        <a class="btn btn-primary" href="index.php">Browse Products</a>
                    </div><?php else: ?><form method="post"><?php while ($item = $items->fetch_assoc()): $sub = $item['price'] * $item['quantity'];
                                                                $total += $sub; ?><div class="cart-item">
                                <div style="flex:1">
                                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                                    <p>Tk:<?= number_format($item['price'], 2) ?> each</p>
                                    <div class="price">Tk:<?= number_format($sub, 2) ?></div><label>Qty <input style="width:70px;padding:7px;border:1px solid #ddd;border-radius:7px" type="number" min="1" max="<?= $item['stock'] ?>" name="qty[<?= $item['cart_id'] ?>]" value="<?= $item['quantity'] ?>"></label><a href="cart.php?action=remove&id=<?= $item['cart_id'] ?>" class="btn btn-outline">Remove</a>
                                </div>
                            </div><?php endwhile; ?><button class="btn btn-outline" name="update">Update Cart</button></form><?php endif; ?></div><?php if ($items->num_rows > 0): ?><div class="panel">
                    <h2>Cart Total</h2>
                    <div class="price">Tk:<?= number_format($total, 2) ?></div><a class="btn btn-primary" href="checkout.php">Checkout & Pay</a>
                </div><?php endif; ?>
        </div>
    </main><?php require __DIR__ . '/../layout/footer.php'; ?></body>

</html>