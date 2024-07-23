<?php

require_once __DIR__ . '/../../config/dbconnection.php';

class User extends Connection {
    private $pdo;

    public function __construct() {
        $connection = new Connection();
        $this->pdo = $connection->connect();
    }

    // get user data from email (for login)
    public function fetchDataByEmail($email) {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email);

        try {
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            // echo "User fetched successfully";
            return $user;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user data for email: " . $e->getMessage());
        }
    }

    public function fetchDataByID($id) {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);

        try {
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $user;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user data for id: " . $e->getMessage());
        }
    }

    // get user data from username (for registration)
    public function fetchDataByUsername($username) {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':username', $username);

        try {
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $user;
        } catch (PDOException $e) {
            throw new Exception("Error fetching user data for username: " . $e->getMessage());
        }
    }

    // create new user
    public function create($username, $email, $password) {
        // hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        try {
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Error creating user: " . $e->getMessage());
        }
    }

    public function update($username, $email, $password, $id) {
        // hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Error updating user data: " . $e->getMessage());
        }
    }
}
