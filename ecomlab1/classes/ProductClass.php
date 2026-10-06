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
}