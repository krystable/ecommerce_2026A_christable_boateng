<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "core/core.php";

if (!is_logged_in()) {
    header("Location: view/register.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SHOPPN</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once __DIR__ . "/view/layout/header.php"; ?>

    <main class="container">
        <div class="welcome-card">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['customer_name'] ?? 'User'); ?></h1>
            <p class="subtitle">
                <?php echo is_admin() ? "You're signed in as an admin." : "You're signed in as a customer."; ?>
            </p>
        </div>

        <?php if (is_admin()): ?>
            <h2>Admin Tools</h2>
            <div class="dashboard-grid">
                <a href="view/admin/brand.php" class="dashboard-tile">
                    <span class="tile-title">Brands</span>
                    <span class="tile-desc">Add and edit product brands</span>
                </a>
                <a href="view/admin/category.php" class="dashboard-tile">
                    <span class="tile-title">Categories</span>
                    <span class="tile-desc">Add and edit product categories</span>
                </a>
                <a href="view/customers.php" class="dashboard-tile">
                    <span class="tile-title">Customers</span>
                    <span class="tile-desc">View all registered customers</span>
                </a>
            </div>
        <?php else: ?>
            <h2>Your Account</h2>
            <div class="dashboard-grid">
                <a href="view/account/my_account.php" class="dashboard-tile">
                    <span class="tile-title">My Account</span>
                    <span class="tile-desc">View and manage your account details</span>
                </a>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>