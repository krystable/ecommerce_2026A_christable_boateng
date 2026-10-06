<?php

require_once  __DIR__ . "/../core/core.php";
require_admin();

require_once __DIR__ . "/../controllers/ProductController.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/view/admin/category.php");
    exit;
}

$name = trim(strip_tags($_POST['category_name'] ?? ''));

if ($name === "") {
    $_SESSION['error'] = "Category name cannot be empty.";
    header("Location: " . BASE_URL . "/view/admin/category.php");
    exit;
}

$controller = new ProductController();
$result = $controller->addCategory($name);

if ($result) {
    $_SESSION['success'] = "Category added successfully.";
} else {
    $_SESSION['error'] = "Failed to add category. It may already exist.";
}
header("Location: ". BASE_URL . "/view/admin/category.php");
exit;