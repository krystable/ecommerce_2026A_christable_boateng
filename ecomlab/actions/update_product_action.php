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

$id = filter_var($_POST['product_id'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$cat = filter_var($_POST['product_cat'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$brand = filter_var($_POST['product_brand'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$title = trim(strip_tags($_POST['product_title'] ?? ''));
$price = filter_var($_POST['product_price'] ?? '', FILTER_VALIDATE_FLOAT);
$desc = trim(strip_tags($_POST['product_desc'] ?? ''));
$keywords = trim(strip_tags($_POST['product_keywords'] ?? ''));
$current = basename($_POST['current_image'] ?? '');

if (!$cat || !$brand || $title === '' || $price === false || $price < 0) {
    $_SESSION['error'] = "All fields are required and must be valid.";
    header("Location: $back" . ($id ? "?edit_id=$id" : ''));
    exit;
}

$error = '';
$new = upload_product_images($_FILES['product_image'] ?? null, $error);

if ($new === false) {
    $_SESSION['error'] = $error;
    header("Location: $back" . ($id ? "?edit_id=$id" : ''));
    exit;
}

$filename = $new !== '' ? $new : $current;

$controller = new ProductController();
$ok = $controller->updateProduct($id, $cat, $brand, $title, $price, $desc, $filename, $keywords);

$_SESSION[$ok ? 'success' : 'error'] = $ok ? "Product updated successfully." : "Failed to add product.";
header("Location: $back");
exit;