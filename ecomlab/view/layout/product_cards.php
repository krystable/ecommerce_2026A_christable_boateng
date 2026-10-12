<?php 
if (empty($products)): ?>
    <p>No products found.</p>
<?php else: ?>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img src="<?php echo BASE_URL; ?>/images/products/<?php echo htmlspecialchars($product['product_image']); ?>"
                    alt= "<?php echo htmlspecialchars ($product['product_price']); ?>">
                <h3><?php echo htmlspecialchars ($product['product_title']); ?></h3>
                <p class="price">GH₵ <?php echo htmlspecialchars($product['product_price']); ?></h3>
                <a href="<php echo BASE_URL: ?>/view/single_product.php?pro_id=<?php echo (int)$product['product_id']; ?>" >Details</a>
                <a href="?add_cart=<?php echo (int)$product['product_id']; ?>"> Add to Cart</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

