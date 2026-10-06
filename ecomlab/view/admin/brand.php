<?php

require_once __DIR__ . "/../../core/core.php";
require_admin();

require_once __DIR__ ."/../../controllers/ProductController.php";


$controller = new ProductController();

$editBrand = null;
if (isset($_GET['edit_id'])) {
    $editId = filter_var($_GET['edit_id'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    if ($editId !== false) {
        $editBrand = $controller ->getBrandById($editId);
    }
}

$brands = $controller->getAllBrands();
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manage Brands</title>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/admin.css">
    </head>
    <body>
        <?php require_once "../layout/header.php"; ?>
        <main class="container">
            <h1><?php echo $editBrand ? "Edit Brand" : "Add Brand"; ?></h1>

            <?php if (isset($_SESSION['success'])): ?>
                <p class="success"> <?php echo htmlspecialchars($_SESSION['success']); ?> </p>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <p class="error"> <?php echo htmlspecialchars($_SESSION['error']); ?> </p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php
            if ($editBrand): ?>
                <form action="<?php echo BASE_URL; ?>/actions/update_brand_action.php" method="POST">
                    <input type="hidden" name="brand_id" value="<?php echo htmlspecialchars($editBrand['brand_id']); ?>">
                    <div>
                        <label for="brand_name">Brand Name:</label>
                        <input type="text" id="brand_name" name="brand_name" value="<?php echo htmlspecialchars($editBrand['brand_name']); ?>" required>
                    </div>
                    <button type="submit">Update Brand</button>
                    <a href="brand.php" class="cancel-button">Cancel</a>
                </form>
            <?php else: ?>
                <form action="<?php echo BASE_URL; ?>/actions/add_brand_action.php" method="POST">
                    <label for="brand_name">Brand Name:</label>
                    <input type="text" id="brand_name" name="brand_name" required>
                    <button type="submit">Add Brand</button>
                </form>
            <?php endif; ?>

            <h2>All Brands</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                 <?php foreach ($brands as $brand): ?>
    <tr>
        <td><?php echo htmlspecialchars($brand['brand_id']); ?></td>

        <?php if ($editBrand && (int)$editBrand['brand_id'] === (int)$brand['brand_id']): ?>
            <td colspan="2">
                <form action="<?php echo BASE_URL; ?>/actions/update_brand_action.php"
                      method="POST" class="inline-form">
                    <input type="hidden" name="brand_id" value="<?php echo (int)$brand['brand_id']; ?>">
                    <input type="text" name="brand_name" required autofocus
                           value="<?php echo htmlspecialchars($editBrand['brand_name']); ?>">
                    <button type="submit">Save</button>
                    <a href="brand.php">Cancel</a>
                </form>
            </td>
        <?php else: ?>
            <td><?php echo htmlspecialchars($brand['brand_name']); ?></td>
            <td><a href="brand.php?edit_id=<?php echo (int)$brand['brand_id']; ?>">Edit</a></td>
        <?php endif; ?>
    </tr>
<?php endforeach; ?>
                </tbody>
            </table>
        </main>
</body>
</html>