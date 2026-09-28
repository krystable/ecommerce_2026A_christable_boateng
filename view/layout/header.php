<?php

require_once "../core/core.php";
?>

<nav>
    <a href="index.php">Home</a> |
    <?php if (is_logged_in()): ?>
        Welcome, <?php echo htmlspecialchars($_SESSION['customer_name'] ?? "User"); ?> 
        <a href = "my_account.php">My Account</a> |
        <a href="logout.php">Logout</a>

    <?php else: ?>
        <a href="login.php">Login</a> |
        <a href="register.php">Register</a>
    <?php endif; ?>
    
