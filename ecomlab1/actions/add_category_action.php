<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../core/core.php";
require_admin();
require_once __DIR__ . "/../controllers/ProductController.php";

$name = trim($_POST['cat_name'] ?? '');   // must be cat_name, not category_name

if ($name !== '') {
    $controller = new ProductController();
    $ok = $controller->addCategory($name);
    $_SESSION[$ok ? 'success' : 'error'] = $ok ? 'Category added.' : 'Could not add category.';
} else {
    $_SESSION['error'] = 'Category name is required.';
}

header("Location: " . BASE_URL . "/view/admin/category.php");
exit;