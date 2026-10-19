<?php
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/CartController.php";

$back = BASE_URL . "/view/cart.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: $back");
    exit;
}

$qtys    = $_POST['qty'] ?? [];
$updates = $_POST['update'] ?? [];

if (!is_array($qtys) || !is_array($updates)) {
    header("Location: $back");
    exit;
}

$cart = new CartController();
$ip = get_cart_key();
$updated = 0;
$invalid = 0;

foreach ($updates as $i => $rawId) {
    $pid = filter_var($rawId, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    $qty = filter_var($qtys[$i] ?? '', FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);

    if (!$pid || !$qty) {
        $invalid++;
        continue;
    }
    if ($cart->updateQty($pid, $qty, $ip)) {
        $updated++;
    }
}

if ($invalid) {
    $_SESSION['error'] = "Quantities must be whole numbers of 1 or more.";
} elseif ($updated) {
    $_SESSION['success'] = "Cart updated.";
}

header("Location: $back");
exit;