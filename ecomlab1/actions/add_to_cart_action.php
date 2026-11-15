<?php
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/CartController.php";
require_once __DIR__ . "/../controllers/ProductController.php";

// Go back to the page the visitor came from, but only if it's on this site.
$back = BASE_URL . "/view/home.php";
if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) !== false) {
    $back = $_SERVER['HTTP_REFERER'];
}

$id = filter_var($_GET['add_cart'] ?? '', FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
if (!$id) {
    header("Location: " . BASE_URL . "/view/home.php");
    exit;
}

// Make sure the product exists
$products = new ProductController();
if (!$products->getProductById($id)) {
    $_SESSION['error'] = "That product doesn't exist.";
    header("Location: $back");
    exit;
}

$cart = new CartController();
$ip = get_cart_key();

if ($cart->isInCart($id, $ip)) {
    $_SESSION['error'] = "This item is already in your cart. Go to cart to update quantity.";
} elseif ($cart->addToCart($id, 1, $ip, get_user_id())) {
    $_SESSION['success'] = "Item added to your cart.";
} else {
    $_SESSION['error'] = "Could not add the item to your cart.";
}

header("Location: $back");
exit;
