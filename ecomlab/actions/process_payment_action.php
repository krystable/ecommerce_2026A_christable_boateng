<?php
require_once "../core/core.php";
require_once "../core/paystack_config.php";
require_once "../controllers/CartController.php";


require_login();

$reference = trim($_GET['ref'] ?? '');
if ($reference === '' || !preg_match('/^[A-Za-z0-9._=-]+$/', $reference)) {
    $_SESSION['error'] = "Missing or invalid payment reference.";
    header("Location: ../view/payment_failed.php");
    exit;
}

$ip         = get_cart_key();
$controller = new CartController();

// Work out what the customer SHOULD have paid, from the database, not from the browser
$items = $controller->getCartItems($ip);
$total = 0;
foreach ($items as $item) {
    $total += $item['product_price'] * $item['qty'];
}
$expectedPesewas = (int) round($total * 100);

// Ask Paystack directly whether this transaction really succeeded
$ch = curl_init("https://api.paystack.co/transaction/verify/" . rawurlencode($reference));
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => ["Authorization: Bearer " . PAYSTACK_SECRET_KEY],
    CURLOPT_TIMEOUT        => 20,
]);
$response = curl_exec($ch);
curl_close($ch);

$result = $response ? json_decode($response, true) : null;

error_log("PAY DEBUG ref=$reference expected=$expectedPesewas paystack_amount=" . ($result['data']['amount'] ?? 'none') . " paystack_status=" . ($result['data']['status'] ?? 'none') . " msg=" . ($result['message'] ?? 'none'));

$verified = $result
    && !empty($result['status'])
    && ($result['data']['status'] ?? '') === 'success'
    && (int) ($result['data']['amount'] ?? 0) === $expectedPesewas
    && $expectedPesewas > 0;

if (!$verified) {
    $_SESSION['error'] = "Payment could not be verified.";
    header("Location: ../view/payment_failed.php");
    exit;
}

$order_id = $controller->processPayment($_SESSION['customer_id'], $ip, $reference, $total);

if ($order_id) {
    header("Location: ../view/payment_success.php?order_id=" . urlencode($order_id));
} else {
    $_SESSION['error'] = "Payment received but the order could not be saved. Please contact support with reference " . $reference;
    header("Location: ../view/payment_failed.php");
}
exit;