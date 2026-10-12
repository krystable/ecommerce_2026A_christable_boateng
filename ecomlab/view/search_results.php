<?php
require_once __DIR__ . "/../core/core.php";
require_login();
require_once __DIR__ . "/../controllers/ProductController.php";

$controller = new ProductController();
$query = trim($_GET['user_query'] ?? '');
$products = $query === '' ? [] : $controller->searchProducts($query);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Search results</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/style.css">
</head>
<body>
<?php require_once __DIR__ . "/layout/header.php"; ?>

<main class="container">
    <h1>Results for "<?php echo htmlspecialchars($query); ?>"</h1>
    <?php require_once __DIR__ . "/layout/sidebar.php"; ?>
    <?php require __DIR__ . "/layout/product_cards.php"; ?>
</main>
</body>
</html>