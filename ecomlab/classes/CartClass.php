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

    
    public function processPayment($customer_id, $ip, $reference, $amount)
    {
        $conn = $this->getConnection();

        try {
            $conn->beginTransaction();

        
            $items = $this->getCartItems($ip);
            if (empty($items)) {
                $conn->rollBack();
                return false;
            }

            
            $invoice_no = mt_rand(100000, 999999);

            
            $stmt = $conn->prepare("
                INSERT INTO orders (customer_id, invoice_no, order_date, order_status)
                VALUES (?, ?, CURDATE(), 'paid')
            ");
            $stmt->execute([$customer_id, $invoice_no]);
            $order_id = $conn->lastInsertId();

           
            $stmt = $conn->prepare("
                INSERT INTO orderdetails (order_id, product_id, qty)
                SELECT ?, p_id, qty FROM cart WHERE ip_add = ?
            ");
            $stmt->execute([$order_id, $ip]);

           
            $stmt = $conn->prepare("DELETE FROM cart WHERE ip_add = ?");
            $stmt->execute([$ip]);

            $conn->commit();

        
            return $order_id;

        } catch (Exception $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            return false;
        }
    }

    public function getOrderById($order_id, $customer_id)
    {
        $sql = "
            SELECT o.order_id, o.invoice_no, o.order_date, o.order_status,
                   d.product_id, d.qty, p.product_title, p.product_price
            FROM orders o
            JOIN orderdetails d ON d.order_id = o.order_id
            JOIN products p ON p.product_id = d.product_id
            WHERE o.order_id = ? AND o.customer_id = ?
        ";
        return $this->fetchAll($sql, [$order_id, $customer_id]);
    }
}