<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once "../core/core.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        </head>

        <body>
            <h1>Login</h1>
            <nav>
                <a href="index.php">Home</a> |
                <a href="register.php">Register</a>
            </nav>

        
            <?php
            if (isset($_SESSION['error'])) {
                echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']);
            }
            ?>

            <form action="../actions/login_action.php" method="POST">
                <div>
                <label for="customer_email">Email:</label>
                <input type="email" id="customer_email" name="customer_email" required><br><br>
</div>
<div>

                <label for="customer_pass">Password:</label>
                <input type="password" id="customer_pass" name="customer_pass" required><br><br>
                </div>

                <div>
                    <button type="submit">Login</button>
                </div>  
            </form>

            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </body>
        </html>

        