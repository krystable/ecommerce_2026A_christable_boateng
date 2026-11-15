<?php
require_once __DIR__ . "/../core/core.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SHOPPN</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-card">

        <div class="auth-logo">
            <span class="mark">&#128717;</span>
            <div class="wordmark">SHOPPN</div>
            <div class="tagline">Beauty you can shop in a tap</div>
        </div>

        <h2 class="auth-title">Welcome back</h2>
        <p class="auth-sub">Log in to continue shopping.</p>

        <?php require __DIR__ . "/layout/flash.php"; ?>

        <form action="<?php echo BASE_URL; ?>/actions/login_action.php" method="POST">
            <div class="field">
                <label for="customer_email">Email</label>
                <input type="email" id="customer_email" name="customer_email"
                       placeholder="you@example.com" required autofocus>
            </div>

            <div class="field">
                <label for="customer_pass">Password</label>
                <input type="password" id="customer_pass" name="customer_pass"
                       placeholder="Your password" required>
            </div>

            <button type="submit">Log in</button>
        </form>

        <p class="auth-switch">New to SHOPPN? <a href="<?php echo BASE_URL; ?>/view/register.php">Create an account</a></p>
    </div>
</div>
<script src="<?php echo BASE_URL; ?>/js/auth.js"></script>
</body>
</html>
