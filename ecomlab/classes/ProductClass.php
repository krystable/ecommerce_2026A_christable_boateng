<?php

require_once __DIR__ . "/../core/db_class.php";


class ProductClass extends Database
{
    public function addBrand($name)
    {
        $sql = "INSERT INTO brands (brand_name) VALUES (?)";
        return $this->execute($sql, [$name]);
    }

    public function getAllBrands()
    {
        $sql = "SELECT * FROM brands ORDER BY brand_name ASC";
        return $this->fetchAll($sql);
    }

    public function getBrandById($id)
    {
        $sql = "SELECT * FROM brands WHERE brand_id = ?";
        $rows = $this->fetchAll($sql, [$id]);
        return $rows ? $rows[0] : null;
    }

    public function updateBrand($id, $name)
    {
        $sql = "UPDATE brands SET brand_name = ? WHERE brand_id = ?";
        return $this->execute($sql, [$name, $id]);
    }

   public function deleteBrand($id)
    {
        $sql = "DELETE FROM brands WHERE brand_id = ?";
        return $this->execute($sql, [$id]);
    }


    //Category functions
    public function addCategory($name)
    {
        $sql = "INSERT INTO categories (cat_name) VALUES (?)";
        return $this->execute($sql, [$name]);
    }

    public function getAllCategories()
    {
        $sql = "SELECT * FROM categories ORDER BY cat_name ASC";
        return $this->fetchAll($sql);
    }

    public function getCategoryById($id)
    {
        $sql = "SELECT * FROM categories WHERE cat_id = ?";
        $rows = $this->fetchAll($sql, [$id]);
        return $rows ? $rows[0] : null;
    }

    public function updateCategory($id, $name)
    {
        $sql = "UPDATE categories SET cat_name = ? WHERE cat_id = ?";
        return $this->execute($sql, [$name, $id]);
    }

   public function deleteCategory($id)
    {
        $sql = "DELETE FROM categories WHERE cat_id = ?";
        return $this->execute($sql, [$id]);
    }


//add product functions
    public function addProduct($cat, $brand, $title, $price, $desc, $image, $keywords)
    {
        $sql = "INSERT INTO products (product_cat, product_brand, product_title, product_price,product_desc, product_image, product_keywords)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
        return $this->execute($sql, [$cat, $brand, $title, $price, $desc, $image, $keywords]);
    
    }

    public function updateProduct($id, $cat, $brand, $title, $price, $desc, $image, $keywords)
    {
        $sql = "UPDATE products SET product_cat = ?, product_brand = ?, product_title = ?, 
        product_price = ?, product_desc = ?, product_image = ?, product_keywords = ? WHERE product_id = ?";
        return $this->execute($sql, [$cat, $brand, $title, $price, $desc, $image, $keywords, $id]);
    }

    
    public function getProductById($id)
    {
        $sql = "SELECT p.*, c.cat_name, b.brand_name
                FROM products p
                JOIN categories c ON p.product_cat = c.cat_id
                JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_id = ?";
        $rows = $this->fetchAll($sql, [$id]);
        return $rows ? $rows[0] : null;
    }

    public function getAllProducts()
    {
    $sql = "SELECT p.*, c.cat_name, b.brand_name
            FROM products p
            JOIN categories c ON p.product_cat = c.cat_id
            JOIN brands b ON p.product_brand = b.brand_id
            ORDER BY p.product_id DESC";
    return $this->fetchAll($sql) ?: [];
    }

    public function getFeaturedProducts($limit = 6)
    {
        $limit = (int)$limit;
        $sql = "SELECT * FROM products ORDER BY RAND() LIMIT $limit";
        return $this->fetchAll($sql) ?: [];
    }

    public function getProductByCategory($cat_id)
    {
        return $this->fetchAll("SELECT * FROM products WHERE product_cat = ?", [$cat_id]) ?: [];
    }

    public function getProductByBrand($brand_id)
    {
        return $this->fetchAll("SELECT * FROM products WHERE product_brand = ?", [$brand_id]) ?: [];
    }

    public function searchProducts($query)
    {
        $like = '%' . $query . '%';
        $sql = "SELECT * FROM products WHERE product_title LIKE ? OR product_keywords LIKE ?";
        return $this->fetchAll($sql, [$like, $like]) ?: [];
    }
}
