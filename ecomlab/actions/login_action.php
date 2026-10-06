<?php

require_once "../core/core.php";
require_once "../controllers/CustomerController.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../view/login.php");
    exit;
}

$email = trim(strip_tags($_POST['customer_email'] ?? ''));
$pass = $_POST['customer_pass'] ?? '';

if (
    $email === "" ||
    $pass === "" ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    $_SESSION['error'] = "Invalid email or password.";
    header("Location: ../view/login.php");
    exit;
}

$controller = new CustomerController();
$result = $controller->login($email, $pass);

if (isset($result["customer_id"])) {

    session_regenerate_id(true);

    $_SESSION['customer_id'] = $result['customer_id'];
    $_SESSION['customer_name'] = $result['customer_name'];
    $_SESSION['customer_email'] = $result['customer_email'];
    $_SESSION['user_role'] = $result['user_role'];

    // index.php is in the root of ecomlab2
    header("Location: ../index.php");
    exit;

} else {

    $_SESSION['error'] = $result["message"] ?? "Invalid email or password.";

    header("Location: ../view/login.php");
    exit;
}