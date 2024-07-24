<?php

require_once __DIR__ . '/../../config/dbconnection.php';

class Blog extends Connection{

    private $pdo;
    public function __construct() {
        $connection = new Connection();
        $this->pdo = $connection->connect();
    }

    // POST
    public function addBlog($data) {
        try{
            // insert new blog first
            $query = "INSERT INTO blogs (blog_title, blog_content) VALUES (:blog_title, :blog_content)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':blog_title', $data['blog_title']);
            $stmt->bindParam(':blog_content', $data['blog_content']);
            $stmt->execute();
            $blog_id = $this->pdo->lastInsertId(); // get the blog_id of the recently added blog
            if(!$blog_id) {
                throw new Exception("Error adding blog");
            }

            // insert into author_posts
            $query2 = "INSERT INTO author_posts (user_id, blog_id) VALUES (:user_id, :blog_id)";
            $stmt2 = $this->pdo->prepare($query2);
            $stmt2->bindParam(':user_id', $data['user_id']);
            $stmt2->bindParam(':blog_id', $data['blog_id']);
            $stmt2->execute();

            $this->pdo->commit();
            echo json_encode(['message' => 'Blog added successfully']);

        } catch (PDOException $e) {
            throw new Exception("Error adding blog: " . $e->getMessage() );
        }
    }
}