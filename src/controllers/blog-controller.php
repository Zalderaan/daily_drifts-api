<?php

require_once __DIR__ . '/../services/jwt-service.php';
require_once __DIR__ . '/../models/blog-model.php';
require_once __DIR__ . '/../services/cookie-service.php';

class BlogController {
    private $JWTservice;
    private $blogModel;
    private $cookieService;
    public function __construct(){
        
        $this->JWTservice = new JWTservice();
        $this->blogModel = new Blog();
        $this->cookieService = new CookieService();
    }

    // POST
    public function createBlog(){
        echo "createBlog in controller reached";
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            // get token from cookie
            $token = $this->cookieService->getTokenCookie();

            if($token){
                // validate token
                $validate = $this->JWTservice->validateToken($token);
                echo json_encode($validate);

                // extract data if token is valid
                if($validate){
                    $userid = $validate['data']['user_id'];
                    $result = $this->blogModel->addBlog($data, $userid);

                    http_response_code(201); // Created
                    echo json_encode(['message' => 'Blog created successfully']);
                } else {
                    throw new Exception("Error validating token");
                }
            } else {
                throw new Exception("No token found");
            }
        } catch (\Exception $e) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    // GET
    public function getAllBlogs(){
        // echo "getAllBlogs in controller reached";
        try {
            return $this->blogModel->getBlogsWithAuthors();
        } catch (\Exception $e) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    // PUT

    // DELETE
}
