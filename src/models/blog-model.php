<?php

require_once __DIR__ . '/../../config/dbconnection.php';

class Blog extends Connection{

    private $pdo;
    public function __construct() {
        $connection = new Connection();
        $this->pdo = $connection->connect();
    }

    // POST
    public function addBlog($data, $user_id) {
        try{

            $this->pdo->beginTransaction();

            $blog_title = $data['blog_title'];
            $blog_body = $data['blog_body'];
            $data['user_id'] = $user_id; //kukunin pa to from jwt idk how to implement pa

            // insert new blog first
            $query = "INSERT INTO blogs (blog_title, blog_body) VALUES (:blog_title, :blog_body)";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':blog_title', $blog_title);
            $stmt->bindParam(':blog_body', $blog_body);
            $stmt->execute();

            $blog_id = $this->pdo->lastInsertId(); // get the blog_id of the recently added blog
            if(!$blog_id) {
                throw new Exception("Error adding blog");
            }

            // insert into author_posts
            $query2 = "INSERT INTO author_posts (author_posts_user_id, author_posts_blog_id) VALUES (:user_id, :blog_id)";
            $stmt2 = $this->pdo->prepare($query2);
            $stmt2->bindParam(':user_id', $user_id);
            $stmt2->bindParam(':blog_id', $blog_id);
            $stmt2->execute();

            $this->pdo->commit();
            echo json_encode(['message' => 'Blog added successfully']);
            return $blog_id;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            echo json_encode(['message' => "Error adding blog: " . $e->getMessage() ]);
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo json_encode(['message' => $e->getMessage() ]);
        }
    }

    // GET
    public function getBlogsWithAuthors(){ // for displaying all blogs with author info
        /* CONSIDERATIONS FOR THIS FUNCTION
            - get all blogs with author info
            - display blog_title, blog_body, blog_created_at, author_username
            - ** might want to add pagination later
        */
        try{
            $query = "
            SELECT 
                blogs.blog_id AS blog_id,
                blogs.blog_title AS blog_title,
                blogs.blog_body AS blog_body,
                blogs.blog_created_at AS author_posts_created_at,
                users.user_id AS author_user_id,
                users.user_username AS author_user_username
            FROM 
                blogs
            INNER JOIN
                author_posts ON blogs.blog_id = author_posts.author_posts_blog_id
            INNER JOIN
                users ON users.user_id = author_posts.author_posts_user_id
            ";
    
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
    
            $blogs = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $blogs;

        } catch (PDOException $e) {
            echo json_encode(['message' => "Error fetching blogs w/ authors: " . $e->getMessage() ]);
        }
    }

    public function getBlogsByAuthor($data){
        
    }

    // PUT
    public function updateBlog(){

    }

    // DELETE
    public function deleteBlog(){
        $query = "DELETE FROM blogs WHERE id = :id";
    }
}