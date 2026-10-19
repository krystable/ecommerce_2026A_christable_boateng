<?php
require_once __DIR__ . "/../core/db_class.php";

class CartClass extends Database
{
    public function isInCart($product_id, $ip)
    {
        $rows = $this->fetchAll(
            "SELECT p_id FROM cart WHERE p_id = ? AND ip_add = ?",
            [$product_id, $ip]
        );
        return !empty($rows);
    }

    // $customer_id is accepted per the brief; the INSERT uses the brief's columns.
    public function addToCart($product_id, $qty, $ip, $customer_id = null)
    {
        $sql = "INSERT INTO cart (p_id, ip_add, qty) VALUES (?, ?, ?)";
        return $this->execute($sql, [$product_id, $ip, $qty]);
    }

    public function getCartItems($ip)
    {
        $sql = "SELECT cart.*, products.product_title, products.product_price, products.product_image
                FROM cart
                JOIN products ON cart.p_id = products.product_id
                WHERE cart.ip_add = ?";
        return $this->fetchAll($sql, [$ip]) ?: [];
    }

    public function getCartCount($ip)
    {
        $rows = $this->fetchAll("SELECT COUNT(*) AS total FROM cart WHERE ip_add = ?", [$ip]);
        return $rows ? (int)$rows[0]['total'] : 0;
    }

    public function getCartTotal($ip)
    {
        $total = 0.0;
        foreach ($this->getCartItems($ip) as $item) {
            $total += (int)$item['qty'] * (float)$item['product_price'];
        }
        return $total;
    }

    public function removeFromCart($product_id, $ip)
    {
        return $this->execute(
            "DELETE FROM cart WHERE p_id = ? AND ip_add = ?",
            [$product_id, $ip]
        );
    }

    public function updateQty($product_id, $new_qty, $ip)
    {
        if ((int)$new_qty < 1) {
            return false;
        }
        return $this->execute(
            "UPDATE cart SET qty = ? WHERE p_id = ? AND ip_add = ?",
            [(int)$new_qty, $product_id, $ip]
        );
    }
}