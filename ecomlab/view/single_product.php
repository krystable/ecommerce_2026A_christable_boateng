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
    <img src="<?php echo BASE_URL; ?>/images/products/<?php echo htmlspecialchars($product['product_image']); ?>"
         alt="<?php echo htmlspecialchars($product['product_title']); ?>" style="max-width:320px; border-radius:12px;">
    <h1><?php echo htmlspecialchars($product['product_title']); ?></h1>
    <p><strong>Category:</strong> <?php echo htmlspecialchars($product['cat_name']); ?></p>
    <p><strong>Brand:</strong> <?php echo htmlspecialchars($product['brand_name']); ?></p>
    <p><strong>Price:</strong> GH₵ <?php echo htmlspecialchars($product['product_price']); ?></p>
    <p><?php echo nl2br(htmlspecialchars($product['product_desc'])); ?></p>
    <p><strong>Keywords:</strong> <?php echo htmlspecialchars($product['product_keywords']); ?></p>
    <a href="?pro_id=<?php echo (int)$product['product_id']; ?>&add_cart=<?php echo (int)$product['product_id']; ?>">Add to Cart</a>
</main>
</body>
</html>