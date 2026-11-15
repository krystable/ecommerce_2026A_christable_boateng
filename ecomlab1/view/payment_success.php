<?php
require_once "../core/core.php";
require_once "../controllers/CartController.php";

require_login();

$order_id = filter_var($_GET['order_id'] ?? '', FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
$order = $order_id ? (new CartController())->getOrderById($order_id, $_SESSION['customer_id']) : [];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Successful</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php require_once "layout/header.php"; ?>

    <main class="container">
        <?php if (empty($order)): ?>
            <p class="message error">Order not found.</p>
        <?php else: ?>
            <p class="message success">Payment successful. Thank you!</p>
            <h1>Invoice #<?php echo htmlspecialchars($order[0]['invoice_no']); ?></h1>
            <p>Order no. <?php echo (int) $order[0]['order_id']; ?> &middot;
               <?php echo htmlspecialchars($order[0]['order_date']); ?> &middot;
               Status: <?php echo htmlspecialchars($order[0]['order_status']); ?></p>

            <?php $total = 0; ?>
            <table>
                <thead>
                    <tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($order as $line): ?>
                        <?php $sub = $line['product_price'] * $line['qty']; $total += $sub; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($line['product_title']); ?></td>
                            <td><?php echo (int) $line['qty']; ?></td>
                            <td>GH₵ <?php echo number_format($line['product_price'], 2); ?></td>
                            <td>GH₵ <?php echo number_format($sub, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3"><strong>Total</strong></td>
                        <td><strong>GH₵ <?php echo number_format($total, 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>

        <p><a href="../index.php">Back to home</a></p>
    </main>
</body>
</html>