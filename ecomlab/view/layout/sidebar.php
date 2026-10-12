<?php
require_once __DIR__ . "/../../core/core.php";
require_once __DIR__ . "/../../controllers/ProductController.php";

$sideController = new ProductController();
$sideCategories = $sideController->getAllCategories();
$sideBrands = $sideController->getAllBrands();
?>

<aside class="sidebar">
    <h3>Categories</h3>
    <ul>
        <?php foreach ($sideCategories as $category): ?>
            <li><a href="<?php echo BASE_URL ?>/view/home.php?cat_id=<?php echo (int)$category['cat_id']; ?>">
                <?php echo htmlspecialchars($category['cat_name']); ?></a></li>
        <?php endforeach; ?>
    </ul>

    <h3>Brands</h3>
    <ul>
        <?php foreach ($sideBrands as $brand): ?>
            <li><a href="<?php echo BASE_URL ?>/view/home.php?brand_id=<?php echo (int)$brand['brand_id']; ?>">
                <?php echo htmlspecialchars($brand['brand_name']); ?></a></li>
        <?php endforeach; ?>
    </ul>
</aside>    