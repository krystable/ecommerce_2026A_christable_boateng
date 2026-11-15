<?php
require_once "../core/core.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Failed</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php require_once "layout/header.php"; ?>

    <main class="container">
        <h1>Payment Failed</h1>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p class="message error">' . htmlspecialchars($_SESSION['error']) . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <p><a href="payment.php">Try again</a></p>
    </main>
</body>
</html>