<?php

require_once __DIR__ . '/../services/userAuth-service.php';
require_once __DIR__ . '/../services/jwt-service.php';
require_once __DIR__ . '/../services/cookie-service.php';

class UserAuthController {
    private $userAuthService;
    private $JWTservice;
    private $cookieService;

    public function __construct(){
        $this->userAuthService = new UserAuthService();
        $this->JWTservice = new JWTservice();
        $this->cookieService = new CookieService();
    }

    public function register(){
        // echo "register in controller reached";
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $result = $this->userAuthService->register($data);
            http_response_code(201); // Created
            echo json_encode(['message' => 'User registered successfully', 'data' => $data['username']]);
        } catch (\Exception $e) {
            http_response_code(400); // Bad Request
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function login(){
        // echo "login in controller reached";

        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $result = $this->userAuthService->login($data);

            if ($result) {
                $token = $this->JWTservice->generateToken($result);
                $this->cookieService->setTokenCookie($token);
                // echo json_encode("Token set in cookie successfully");

                // echo $token;

                // $data['token'] = $token;
                http_response_code(200); // OK
                echo json_encode(['message' => 'User logged in successfully', 'email' => $data['email'] ]);
            } else {
                throw new Exception("Invalid credentials");
            }
        } catch(\Exception $e){
            http_response_code(400); // Bad Request
            echo json_encode(['message' => $e->getMessage()]);
        }
    }

    public function logout(){
        // echo "logout in controller reached";
        try {
            // if(CookieService::clearTokenCookie()){
            //     echo json_encode("Token cleared from cookie successfully");
            // }
            http_response_code(200); // OK
            echo json_encode(['message' => 'User logged out successfully']);
        } catch(\Exception $e){
            http_response_code(400); // Bad Request
            echo json_encode(['message' => $e->getMessage()]);
        }
    }
} 