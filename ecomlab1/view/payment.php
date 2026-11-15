<?php
require_once "../core/core.php";
require_once "../core/paystack_config.php";
require_once "../controllers/CartController.php";

require_login();

$controller = new CartController();
$items = $controller->getCartItems(get_cart_key());

$total = 0;
foreach ($items as $item) {
    $total += $item['product_price'] * $item['qty'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php require_once "layout/header.php"; ?>

    <main class="container">
        <h1>Checkout</h1>

        <?php if (empty($items)): ?>
            <p class="message error">Your cart is empty.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_title']); ?></td>
                            <td><?php echo (int) $item['qty']; ?></td>
                            <td>GH₵ <?php echo number_format($item['product_price'], 2); ?></td>
                            <td>GH₵ <?php echo number_format($item['product_price'] * $item['qty'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3"><strong>Total</strong></td>
                        <td><strong>GH₵ <?php echo number_format($total, 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>

            <p><button class="edit-btn" onclick="payWithPaystack()">Pay Now</button></p>

            <script src="https://js.paystack.co/v1/inline.js"></script>
            <script>
                function payWithPaystack() {
                    var handler = PaystackPop.setup({
                        key: '<?php echo PAYSTACK_PUBLIC_KEY; ?>',
                        email: '<?php echo htmlspecialchars($_SESSION["customer_email"]); ?>',
                        amount: <?php echo (int) round($total * 100); ?>,
                        currency: 'GHS',
                        callback: function (response) {
                            window.location = '../actions/process_payment_action.php?ref=' + encodeURIComponent(response.reference);
                        },
                        onClose: function () { alert('Payment cancelled.'); }
                    });
                    handler.openIframe();
                }
            </script>
        <?php endif; ?>
    </main>
</body>
</html>