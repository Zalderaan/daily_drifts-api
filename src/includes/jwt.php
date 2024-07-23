<?php

use Firebase\JWT\JWT;

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../config');
$dotenv->load();

$secretKey = $_ENV['SECRET_KEY'];

// Generate JWT token
function generateToken($user_id) {
    global $secretKey;

    $issuedAt = time();
    $expirationTime = $issuedAt + 60 * 60;  // JWT valid for 1 hour from issued time
    $payload = [
        'userid' => $user_id,
        'iat' => $issuedAt,
        'exp' => $expirationTime
    ];

    return JWT::encode($payload, $secretKey, 'HS256');
}

// Validate JWT token
function validateToken($jwt) {
    global $secretKey;

    try {
        $decoded = JWT::decode($jwt, [$secretKey, 'HS256']);
        return ['valid' => true, 'data' => (array) $decoded];
    } catch (Exception $e) {
        return ['valid' => false, 'data' => $e->getMessage()];
    }
}
