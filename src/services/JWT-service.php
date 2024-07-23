<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use Dotenv\Dotenv;

// load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../config');
$dotenv->load();

class JWTservice {
    private $secretKey;

    public function __construct(){
        $this->secretKey = $_ENV['SECRET_KEY'];
    }

    public function generateToken($user_id, $email){
        $key = $this->secretKey;

        $issuedAt = time();
        $expirationTime = $issuedAt + 60 * 60;  // JWT valid for 1 hour from issued time
        $payload = [
            'userid' => $user_id,
            'email' => $email,
            'iat' => $issuedAt,
            'exp' => $expirationTime
        ];

        $token = JWT::encode($payload, $key, 'HS256');
        return $token;
    }

    public function validateToken($token){
        $key = $this->secretKey;

        try {
            $decoded = JWT::decode($token, new Firebase\JWT\Key($key, 'HS256'));
            return ['valid' => true, 'data' => (array) $decoded];
        } catch (Exception $e) {
            return ['valid' => false, 'data' => $e->getMessage()];
        }
    }
}
