<?php
require_once __DIR__ . "/../core/core.php";
require_admin();


require_once __DIR__ . "/../controllers/ProductController.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/view/admin/category.php");
    exit;
}

$id = $_POST['cat_id'] ?? '';
$name = trim(strip_tags($_POST['cat_name'] ?? ''));

$validId = filter_var($id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);

if($validId === false || $name === "") {
    $_SESSION['error'] = "Invalid category ID or name.";
    header("Location: " . BASE_URL . "/view/admin/category.php");
    exit;
}  

$controller = new ProductController();
$result = $controller->updateCategory($id, $name);

if ($result) {
    $_SESSION['success'] = "Category updated successfully.";
} else {
    $_SESSION['error'] = "Failed to update category. It may already exist.";
}
header("Location: " . BASE_URL . "/view/admin/category.php");
exit;