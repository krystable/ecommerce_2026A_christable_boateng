<?php
require_once __DIR__ . "/../core/core.php";
require_login();
require_once __DIR__ . "/../controllers/ProductController.php";

$id = filter_var($_GET['pro_id'] ?? '', FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
if (!$id) {
    header("Location: " . BASE_URL . "/view/home.php");
    exit;
}

$controller = new ProductController();
$product = $controller->getProductById($id);
if (!$product) {
    header("Location: " . BASE_URL . "/view/home.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo htmlspecialchars($product['product_title']); ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>
<?php require_once __DIR__ . "/layout/header.php"; ?>

<main class="container">
    <a href="<?php echo BASE_URL; ?>/view/home.php" class="home-btn">&larr; Back to shop</a>
    <?php require __DIR__ . "/layout/flash.php"; ?>

    <div class="product-detail">
        <img src="<?php echo BASE_URL; ?>/images/products/<?php echo htmlspecialchars($product['product_image']); ?>"
             alt="<?php echo htmlspecialchars($product['product_title']); ?>">
        <div class="detail-info">
            <h1><?php echo htmlspecialchars($product['product_title']); ?></h1>
            <p class="detail-price">GH₵ <?php echo htmlspecialchars($product['product_price']); ?></p>
            <p class="detail-meta">
                <span class="tag"><?php echo htmlspecialchars($product['cat_name']); ?></span>
                <span class="tag"><?php echo htmlspecialchars($product['brand_name']); ?></span>
            </p>
            <p><?php echo nl2br(htmlspecialchars($product['product_desc'])); ?></p>
            <p class="detail-keywords"><strong>Keywords:</strong> <?php echo htmlspecialchars($product['product_keywords']); ?></p>
            <a class="btn" href="<?php echo BASE_URL; ?>/actions/add_to_cart_action.php?add_cart=<?php echo (int)$product['product_id']; ?>">Add to Cart</a>
        </div>
    </div>
</main>
</body>
</html>