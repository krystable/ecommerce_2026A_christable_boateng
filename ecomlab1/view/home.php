<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . "/../core/core.php";
require_login();
require_once __DIR__ . "/../controllers/ProductController.php";

$controller = new ProductController();

if (isset($_GET['cat']) && filter_var($_GET['cat'], FILTER_VALIDATE_INT)) {
    $products = $controller->getProductsByCategory((int)$_GET['cat']);
} elseif (isset($_GET['brand']) && filter_var($_GET['brand'], FILTER_VALIDATE_INT)) {
    $products = $controller->getProductsByBrand((int)$_GET['brand']);
} else {
    $products = $controller->getFeaturedProducts();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shop</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>
<?php require_once __DIR__ . "/layout/header.php"; ?>

<main class="container">
    <a href="<?php echo BASE_URL; ?>/index.php" class="home-btn">← Home</a>
    <form action="<?php echo BASE_URL; ?>/view/search_results.php" method="GET" class="search-bar">
        <input type="text" name="user_query" placeholder="Search products...">
        <button type="submit">Search</button>
    </form>
    <?php require __DIR__ . "/layout/flash.php"; ?>
    <?php require_once __DIR__ . "/layout/sidebar.php"; ?>
    <?php require __DIR__ . "/layout/product_cards.php"; ?>
</main>
</body>
</html>