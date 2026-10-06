<?php
require_once __DIR__ . "/../../core/core.php";
?>

<nav class="sidebar">
    <a href="<?php echo BASE_URL; ?>/index.php" class="sidebar-brand">SHOPPN</a>

    <?php if (is_logged_in()): ?>
        <span class="sidebar-user">Hi, <?php echo htmlspecialchars($_SESSION['customer_name'] ?? "User"); ?></span>
        <a href="<?php echo BASE_URL; ?>/index.php">Home</a>
        <a href="<?php echo BASE_URL; ?>/view/account/my_account.php">My Account</a>

        <?php if (is_admin()): ?>
            <span class="sidebar-label">Admin</span>
            <a href="<?php echo BASE_URL; ?>/view/admin/brand.php">Manage Brands</a>
            <a href="<?php echo BASE_URL; ?>/view/admin/category.php">Manage Categories</a>
            <a href="<?php echo BASE_URL; ?>/view/customers.php">Customers</a>
        <?php endif; ?>

        <a href="<?php echo BASE_URL; ?>/logout.php" class="sidebar-logout">Logout</a>
    <?php else: ?>
        <a href="<?php echo BASE_URL; ?>/view/login.php">Login</a>
        <a href="<?php echo BASE_URL; ?>/view/register.php">Register</a>
    <?php endif; ?>
</nav>