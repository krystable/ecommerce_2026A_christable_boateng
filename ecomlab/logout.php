<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/core/core.php";

secure_logout();
header("Location: " . BASE_URL . "/view/login.php");
exit;