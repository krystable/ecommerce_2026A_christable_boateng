<?php
require_once __DIR__ . "/../core/core.php";
require_admin();

require_once __DIR__ . "/../controllers/ProductController.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/view/admin/brand.php");
    exit;
}

$id   = $_POST['brand_id'] ?? '';
$name = trim(strip_tags($_POST['brand_name'] ?? ''));

$validId = filter_var($id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);

if (!$validId || $name === "") {
    $_SESSION['error'] = "Invalid brand ID or name.";
    header("Location: " . BASE_URL . "/view/admin/brand.php");
    exit;
}

$controller = new ProductController();
$result = $controller->updateBrand($validId, $name);

if ($result) {
    $_SESSION['success'] = "Brand updated successfully.";
} else {
    $_SESSION['error'] = "Failed to update brand. It may already exist.";
}

header("Location: " . BASE_URL . "/view/admin/brand.php");
exit;