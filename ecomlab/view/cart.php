<?php
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/CartController.php";

$cart  = new CartController();
$ip    = get_cart_key();
$items = $cart->getCartItems($ip);
$total = $cart->getCartTotal($ip);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Cart</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>
<?php require_once __DIR__ . "/layout/header.php"; ?>

<main class="container">
    <h1>Your Cart</h1>
    <?php require __DIR__ . "/layout/flash.php"; ?>

    <?php if (empty($items)): ?>
        <p>Your cart is empty.</p>
        <a href="<?php echo BASE_URL; ?>/view/home.php" class="btn">Continue Shopping</a>
    <?php else: ?>

        <form action="<?php echo BASE_URL; ?>/actions/update_qty_action.php" method="POST">
            <table>
                <thead>
                    <tr><th>Remove</th><th>Product</th><th>Qty</th><th>Unit Price</th></tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="remove[]"
                                   value="<?php echo (int)$item['p_id']; ?>">
                        </td>
                        <td>
                            <img src="<?php echo BASE_URL; ?>/images/products/<?php echo htmlspecialchars($item['product_image']); ?>"
                                 alt="" style="width:50px; height:50px; object-fit:cover; border-radius:6px; vertical-align:middle;">
                            <?php echo htmlspecialchars($item['product_title']); ?>
                        </td>
                        <td>
                            <input type="text" name="qty[]" size="3"
                                   value="<?php echo (int)$item['qty']; ?>">
                            <input type="hidden" name="update[]"
                                   value="<?php echo (int)$item['p_id']; ?>">
                        </td>
                        <td>GH₵ <?php echo number_format((float)$item['product_price'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Subtotal</strong></td>
                        <td><strong>GH₵ <?php echo number_format($total, 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>

            <div class="cart-actions">
                <button type="submit" formaction="<?php echo BASE_URL; ?>/actions/remove_from_cart_action.php">Remove from Cart</button>
                <button type="submit">Update Quantity</button>
                <a href="<?php echo BASE_URL; ?>/view/home.php" class="btn">Continue Shopping</a>
                <a href="<?php echo BASE_URL; ?>/view/checkout.php" class="btn">Checkout</a>
            </div>
        </form>
    <?php endif; ?>
</main>
</body>
</html>
