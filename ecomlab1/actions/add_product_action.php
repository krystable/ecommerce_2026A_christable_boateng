<?php
require_once __DIR__ . "/../core/core.php";
require_admin();
require_once __DIR__ . "/../core/upload.php";
require_once __DIR__ . "/../controllers/ProductController.php";

$back = BASE_URL . "/view/admin/product.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: $back");
    exit;
}

$cat = filter_var($_POST['product_cat'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$brand = filter_var($_POST['product_brand'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$title = trim(strip_tags($_POST['product_title'] ?? ''));
$price = filter_var($_POST['product_price'] ?? '', FILTER_VALIDATE_FLOAT);
$desc = trim(strip_tags($_POST['product_desc'] ?? ''));
$keywords = trim(strip_tags($_POST['product_keywords'] ?? ''));

if (!$cat || !$brand || $title === '' || $price === false || $price < 0) {
    $_SESSION['error'] = "All fields are required and must be valid.";
    header("Location: $back");
    exit;
}

$error = '';
$filename = upload_product_images($_FILES['product_image'] ?? null, $error);
if ($filename === false) {
    $_SESSION['error'] = $error;
    header("Location: $back");
    exit;
}

if ($filename === '') {
    $_SESSION['error'] = "Product image is required.";
    header("Location: $back");
    exit;
}

$controller = new ProductController();
$ok = $controller->addProduct($cat, $brand, $title, $price, $desc, $filename, $keywords);

$_SESSION[$ok ? 'success' : 'error'] = $ok ? "Product added successfully." : "Failed to add product.";
header("Location: $back");
exit;