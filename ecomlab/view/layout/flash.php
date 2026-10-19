<?php
$flashTypes = ['error' => 'flash-error', 'success' => 'flash-success'];
foreach ($flashTypes as $key => $class) {
    if (isset($_SESSION[$key])) {
        echo "<div class='flash $class'>" . htmlspecialchars($_SESSION[$key]) . "</div>";
        unset($_SESSION[$key]);
    }
}
