<?php
require_once __DIR__ . "/../../core/core.php";
require_admin();
require_once __DIR__ . "/../../controllers/ProductController.php";

$controller = new ProductController();

$editProduct = null;
if (isset($_GET['edit_id'])) {
    $editId = filter_var($_GET['edit_id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($editId !== false) {
        $editProduct = $controller->getProductById($editId);
    }
}

$categories = $controller->getAllCategories();
$brands = $controller->getAllBrands();
$products = $controller->getAllProducts();

$isEdit = (bool)$editProduct;
$action = $isEdit ? "update_product_action.php" : "add_product_action.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
    </head>
<body>
    <?php require_once __DIR__ . "/../layout/header.php"; ?>
    <main class= "container">
        <h1> <?php echo $isEdit ? "Edit Product" : "Add New Product"; ?></h1>

        <?php
        foreach (['error' => 'red', 'success' => 'green'] as $key => $color) {
            if (isset($_SESSION[$key])) {
                echo "<p style='color: $color;'>{$_SESSION[$key]}</p>";
                unset($_SESSION[$key]);
            }
        }
        ?>

        <form action="<?php echo BASE_URL; ?>/actions/<?php echo $action; ?>" method="POST"
          enctype="multipart/form-data" id="productForm">

        <?php if ($isEdit): ?>
            <input type="hidden" name="product_id" value="<?php echo (int)$editProduct['product_id']; ?>">
            <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($editProduct['product_image']); ?>">
        <?php endif; ?>

        <label for="product_title">Product Title</label>
        <input type="text" id="product_title" name="product_title" required
               value="<?php echo $isEdit ? htmlspecialchars($editProduct['product_title']) : ''; ?>">

        <label for="product_price">Price</label>
        <input type="text" id="product_price" name="product_price" required
               value="<?php echo $isEdit ? htmlspecialchars($editProduct['product_price']) : ''; ?>">

        <label for="product_cat">Category</label>
        <select id="product_cat" name="product_cat" required>
            <option value="">-- Select category --</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?php echo (int)$c['cat_id']; ?>"
                    <?php echo ($isEdit && (int)$editProduct['product_cat'] === (int)$c['cat_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($c['cat_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="product_brand">Brand</label>
        <select id="product_brand" name="product_brand" required>
            <option value="">-- Select brand --</option>
            <?php foreach ($brands as $b): ?>
                <option value="<?php echo (int)$b['brand_id']; ?>"
                    <?php echo ($isEdit && (int)$editProduct['product_brand'] === (int)$b['brand_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($b['brand_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="product_desc">Description</label>
        <textarea id="product_desc" name="product_desc" rows="4"><?php
            echo $isEdit ? htmlspecialchars($editProduct['product_desc']) : ''; ?></textarea>

        <label for="product_keywords">Keywords</label>
        <input type="text" id="product_keywords" name="product_keywords"
               value="<?php echo $isEdit ? htmlspecialchars($editProduct['product_keywords']) : ''; ?>">

        <?php if ($isEdit && !empty($editProduct['product_image'])): ?>
            <p>Current image:</p>
            <img src="<?php echo BASE_URL; ?>/images/products/<?php echo htmlspecialchars($editProduct['product_image']); ?>"
                 alt="Current product image" style="max-width:150px; border-radius:8px;">
        <?php endif; ?>

        <label for="product_image"><?php echo $isEdit ? "Replace image (optional)" : "Image"; ?></label>
        <input type="file" id="product_image" name="product_image"
               accept="image/jpeg,image/png,image/gif,image/webp" <?php echo $isEdit ? '' : 'required'; ?>>
        <p id="imageError" style="color:red;"></p>

        <button type="submit"><?php echo $isEdit ? "Save changes" : "Add product"; ?></button>
        <?php if ($isEdit): ?><a href="product.php">Cancel</a><?php endif; ?>
    </form>

    <h2>All Products</h2>
    <table>
        <thead><tr><th>ID</th><th>Title</th><th>Category</th><th>Brand</th><th>Price</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><?php echo (int)$p['product_id']; ?></td>
                <td><?php echo htmlspecialchars($p['product_title']); ?></td>
                <td><?php echo htmlspecialchars($p['cat_name']); ?></td>
                <td><?php echo htmlspecialchars($p['brand_name']); ?></td>
                <td><?php echo htmlspecialchars($p['product_price']); ?></td>
                <td><a href="product.php?edit_id=<?php echo (int)$p['product_id']; ?>">Edit</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>

<script>
// Client-side check is for UX only. The PHP upload helper is the real security check.
document.getElementById('productForm').addEventListener('submit', function (e) {
    var input = document.getElementById('product_image');
    var msg = document.getElementById('imageError');
    msg.textContent = '';
    if (input.files.length === 0) return;

    var file = input.files[0];
    var allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (allowed.indexOf(file.type) === -1) {
        msg.textContent = 'Only JPG, PNG, GIF or WEBP images are allowed.';
        e.preventDefault();
    } else if (file.size > 5 * 1024 * 1024) {
        msg.textContent = 'Image must be 5MB or smaller.';
        e.preventDefault();
    }
});
</script>
</body>
</html>