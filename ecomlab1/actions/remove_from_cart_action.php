<?php
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/CartController.php";

$back = BASE_URL . "/view/cart.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: $back");
    exit;
}

$remove = $_POST['remove'] ?? [];
if (!is_array($remove) || empty($remove)) {
    $_SESSION['error'] = "Tick the items you want to remove.";
    header("Location: $back");
    exit;
}

$cart = new CartController();
$ip = get_cart_key();
$removed = 0;

foreach ($remove as $raw) {
    $pid = filter_var($raw, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    if ($pid && $cart->removeFromCart($pid, $ip)) {
        $removed++;
    }
}

$_SESSION[$removed ? 'success' : 'error'] = $removed ? "Removed from your cart." : "Nothing was removed.";
header("Location: $back");
exit;
