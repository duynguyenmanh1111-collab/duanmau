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

    public function categoryExists(string $name): bool
    {
        $statement = $this->conn->prepare(
            'SELECT 1 FROM categories WHERE name = :name LIMIT 1'
        );
        $statement->execute(['name' => $name]);

        return $statement->fetchColumn() !== false;
    }

    public function categoryExistsExcept(string $name, int $excludeId): bool
    {
        $statement = $this->conn->prepare(
            'SELECT 1 FROM categories WHERE name = :name AND id != :id LIMIT 1'
        );
        $statement->execute(['name' => $name, 'id' => $excludeId]);

        return $statement->fetchColumn() !== false;
    }

    public function findCategory(int $id): array|false
    {
        $statement = $this->conn->prepare(
            'SELECT id, name FROM categories WHERE id = :id LIMIT 1'
        );
        $statement->execute(['id' => $id]);

        return $statement->fetch();
    }

    public function updateCategory(int $id, string $name): void
    {
        $statement = $this->conn->prepare(
            'UPDATE categories SET name = :name WHERE id = :id'
        );
        $statement->execute(['id' => $id, 'name' => $name]);
    }

    public function countCategoryProducts(int $id): int
    {
        $statement = $this->conn->prepare(
            'SELECT COUNT(*) FROM products WHERE category_id = :id'
        );
        $statement->execute(['id' => $id]);

        return (int) $statement->fetchColumn();
    }

    public function deleteCategory(int $id): void
    {
        $statement = $this->conn->prepare('DELETE FROM categories WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    public function createCategory(string $name): void
    {
        $statement = $this->conn->prepare(
            'INSERT INTO categories (name) VALUES (:name)'
        );
        $statement->execute(['name' => $name]);
    }

    public function createProduct(
        string $name,
        float $price,
        int $quantity,
        int $categoryId,
        string $image,
        string $description
    ): void {
        $statement = $this->conn->prepare(
            'INSERT INTO products (name, price, quantity, category_id, image, description)
            VALUES (:name, :price, :quantity, :category_id, :image, :description)'
        );
        $statement->execute([
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'category_id' => $categoryId,
            'image' => $image,
            'description' => $description,
        ]);
    }

    public function updateProduct(
        int $id,
        string $name,
        float $price,
        int $quantity,
        int $categoryId,
        string $image,
        string $description
    ): void {
        $statement = $this->conn->prepare(
            'UPDATE products
            SET name = :name, price = :price, quantity = :quantity,
                category_id = :category_id, image = :image, description = :description
            WHERE id = :id'
        );
        $statement->execute([
            'id' => $id,
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'category_id' => $categoryId,
            'image' => $image,
            'description' => $description,
        ]);
    }

    public function deleteProduct(int $id): void
    {
        $statement = $this->conn->prepare('DELETE FROM products WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    public function findProduct(int $id): array|false
    {
        $statement = $this->conn->prepare(
            'SELECT id, name, price, quantity, category_id, image, description
            FROM products WHERE id = :id LIMIT 1'
        );
        $statement->execute(['id' => $id]);

        return $statement->fetch();
    }
}
