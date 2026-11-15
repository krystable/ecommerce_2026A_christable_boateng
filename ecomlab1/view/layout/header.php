<?php
require_once __DIR__ . "/../../core/core.php";
require_once __DIR__ . "/../../controllers/CartController.php";

$headerCart  = new CartController();
$cartCount   = $headerCart->getCartCount(get_cart_key());
$cartTotal   = $headerCart->getCartTotal(get_cart_key());
?>

<nav class="sidebar">
    <a href="<?php echo BASE_URL; ?>/index.php" class="sidebar-brand">SHOPPN</a>

    <div class="cart-summary">
        <strong>Shopping Cart</strong>
        <span>Total Items: <?php echo $cartCount; ?></span>
        <span>Total Price: GH₵ <?php echo number_format($cartTotal, 2); ?></span>
        <a href="<?php echo BASE_URL; ?>/view/cart.php">Go to Cart</a>
    </div>

    <?php if (is_logged_in()): ?>
        <span class="sidebar-user">Hi, <?php echo htmlspecialchars($_SESSION['customer_name'] ?? "User"); ?></span>
        <a href="<?php echo BASE_URL; ?>/index.php">Home</a>
        <a href="<?php echo BASE_URL; ?>/view/account/my_account.php">My Account</a>
        <a href="<?php echo BASE_URL; ?>/view/home.php">Shop</a>

        <?php if (is_admin()): ?>
            <span class="sidebar-label">Admin</span>
            <a href="<?php echo BASE_URL; ?>/view/admin/brand.php">Manage Brands</a>
            <a href="<?php echo BASE_URL; ?>/view/admin/category.php">Manage Categories</a>
            <a href="<?php echo BASE_URL; ?>/view/admin/product.php">Manage Products</a>
            <a href="<?php echo BASE_URL; ?>/view/customers.php">Customers</a>
        <?php endif; ?>

        <a href="<?php echo BASE_URL; ?>/logout.php" class="sidebar-logout">Logout</a>
    <?php else: ?>
        <a href="<?php echo BASE_URL; ?>/view/login.php">Login</a>
        <a href="<?php echo BASE_URL; ?>/view/register.php">Register</a>
    <?php endif; ?>
</nav>