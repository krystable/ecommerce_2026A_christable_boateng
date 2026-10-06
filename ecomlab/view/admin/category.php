<?php
require_once __DIR__ . "/../../core/core.php";
require_admin();
require_once __DIR__ . "/../../controllers/ProductController.php";

$controller = new ProductController();

$editCategory = null;
if (isset($_GET['edit_id'])) {
    $editId = filter_var($_GET['edit_id'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    if ($editId !== false) {
        $editCategory = $controller->getCategoryById($editId);
    }
}

$categories = $controller->getAllCategories();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">        <title>Manage Categories</title>
        <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
    </head>
    <body>
        <?php require_once "../layout/header.php"; ?>
        <main class="container">

        <?php if (isset($_SESSION['success'])): ?>
            <p class="success"> <?php echo htmlspecialchars($_SESSION['success']); ?> </p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"> <?php echo htmlspecialchars($_SESSION['error']); ?> </p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if ($editCategory): ?>
            <form action="<?php echo BASE_URL; ?>/actions/update_category_action.php" method="POST" class='form-card'>
                <input type="hidden" name="category_id" value="<?php echo htmlspecialchars($editCategory['category_id']); ?>">
                <div>
                    <label for="category_name">Category Name:</label>
                    <input type="text" id="category_name" name="category_name" value="<?php echo htmlspecialchars($editCategory['category_name']); ?>" required>
                </div>
                <button type="submit">Update Category</button>
                <a href="category.php" class="cancel-button">Cancel</a>
            </form>
        <?php else: ?>

            <form action="<?php echo BASE_URL; ?>/actions/add_category_action.php" method="POST" class='form-card'>
                <label for="cat_name">Category Name:</label>
                <input type="text" id="cat_name" name="cat_name" required>
                <button type="submit">Add Category</button>
            </form>
        <?php endif; ?>

        <h2>All Categories</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
    <tr>
        <td><?php echo htmlspecialchars($category['cat_id']); ?></td>

        <?php if ($editCategory && (int)$editCategory['cat_id'] === (int)$category['cat_id']): ?>
            <td colspan="2">
                <form action="<?php echo BASE_URL; ?>/actions/update_category_action.php"
                      method="POST" class="inline-form">
                    <input type="hidden" name="cat_id" value="<?php echo (int)$category['cat_id']; ?>">
                    <input type="text" name="cat_name" required autofocus
                           value="<?php echo htmlspecialchars($editCategory['cat_name']); ?>">
                    <button type="submit">Save</button>
                    <a href="category.php">Cancel</a>
                </form>
            </td>
        <?php else: ?>
            <td><?php echo htmlspecialchars($category['cat_name']); ?></td>
            <td><a href="category.php?edit_id=<?php echo (int)$category['cat_id']; ?>">Edit</a></td>
        <?php endif; ?>
    </tr>
<?php endforeach; ?>
            </tbody>
        </table>
        </main>
    </body>
</html>

