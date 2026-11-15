<?php
require_once __DIR__ . "/../../core/core.php";
require_once __DIR__ . "/../../controllers/ProductController.php";

$sideController = new ProductController();
$sideCategories = $sideController->getAllCategories();
$sideBrands     = $sideController->getAllBrands();

$activeCat   = isset($_GET['cat'])   ? (int)$_GET['cat']   : 0;
$activeBrand = isset($_GET['brand']) ? (int)$_GET['brand'] : 0;
?>
<div class="filters">
    <details class="filter-dropdown">
        <summary>Categories</summary>
        <ul>
            <?php foreach ($sideCategories as $c): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>/view/home.php?cat=<?php echo (int)$c['cat_id']; ?>"
                       class="<?php echo $activeCat === (int)$c['cat_id'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($c['cat_name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </details>

    <details class="filter-dropdown">
        <summary>Brands</summary>
        <ul>
            <?php foreach ($sideBrands as $b): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>/view/home.php?brand=<?php echo (int)$b['brand_id']; ?>"
                       class="<?php echo $activeBrand === (int)$b['brand_id'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($b['brand_name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </details>

    <?php if ($activeCat || $activeBrand): ?>
        <a href="<?php echo BASE_URL; ?>/view/home.php" class="clear-filter">Clear filter &times;</a>
    <?php endif; ?>
</div>

<script>
// Only one dropdown open at a time, and close when clicking elsewhere
document.addEventListener('click', function (e) {
    document.querySelectorAll('.filter-dropdown[open]').forEach(function (d) {
        if (!d.contains(e.target)) d.removeAttribute('open');
    });
});
document.querySelectorAll('.filter-dropdown').forEach(function (d) {
    d.addEventListener('toggle', function () {
        if (d.open) {
            document.querySelectorAll('.filter-dropdown[open]').forEach(function (o) {
                if (o !== d) o.removeAttribute('open');
            });
        }
    });
});
</script>
