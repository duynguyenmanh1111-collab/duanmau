<?php 
// Có class chứa các function thực thi tương tác với cơ sở dữ liệu 
class ProductModel 
{
    public $conn;
    public function __construct()
    {
        $this->conn = connectDB();
    }

    // Viết truy vấn danh sách sản phẩm 
    public function getAllProduct()
    {
        $sql = 'SELECT p.id, p.name, p.price, p.quantity, p.category_id, p.image, p.description, c.name AS category_name
                FROM products AS p
                LEFT JOIN categories AS c ON c.id = p.category_id
                ORDER BY p.id DESC';

        return $this->conn->query($sql)->fetchAll();
    }

    public function getAllCategories()
    {
        $sql = 'SELECT id, name FROM categories ORDER BY name';

        return $this->conn->query($sql)->fetchAll();
    }
}
