<?php
class AuthModel
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function findByEmail(string $email): array|false
    {
        $statement = $this->conn->prepare(
            'SELECT id, name, email, password, role FROM users WHERE email = :email LIMIT 1'
        );
        $statement->execute(['email' => $email]);

        return $statement->fetch();
    }

    public function createUser(
        string $name,
        string $email,
        string $passwordHash,
        string $phone,
        string $address
    ): bool
    {
        try {
            $statement = $this->conn->prepare(
                'INSERT INTO users (name, email, password, phone, address)
                VALUES (:name, :email, :password, :phone, :address)'
            );
            $statement->execute([
                'name' => $name,
                'email' => $email,
                'password' => $passwordHash,
                'phone' => $phone,
                'address' => $address,
            ]);

            return true;
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                return false;
            }

            throw $exception;
        }
    }
}
