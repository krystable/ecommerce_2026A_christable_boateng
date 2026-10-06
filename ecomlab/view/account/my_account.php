<?php
require_once "../../core/core.php";
require_login();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Account</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>
    <?php require_once "../layout/header.php"; ?>
    <main class="container">
        <h1>My Account</h1>
        <p>Name: <?php echo htmlspecialchars($_SESSION['customer_name'] ?? ''); ?></p>
        <p>Email: <?php echo htmlspecialchars($_SESSION['customer_email'] ?? ''); ?></p>
    </main>
</body>
</html>