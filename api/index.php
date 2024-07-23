<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");// Set the response content type to JSON
header("Access-Control-Allow-Origin: *");// Allow from a specific origin
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");// Allow specific HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization");// Allow specific headers
header("Access-Control-Allow-Credentials: true");// Optional: Allow credentials

// Handle preflight (OPTIONS) requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/dbconnection.php';
require_once __DIR__ . '/../src/controllers/userAuth-controller.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../config');
$dotenv->load();

// setup routing
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// // for debugging
// echo $requestUri . '<br>';
// echo $requestMethod . '<br>';

// remove the query string from URI
// Remove leading directories if necessary
$baseDir = '/blog-api/api'; // Your base directory http://localhost/blog-api
$path = str_replace($baseDir, '', $requestUri);

// Remove query string
$path = strtok($path, '?');

//  // for debugging purposes
// echo $path;

// basic routing
if($requestMethod === 'POST') {
    switch($path){
        case '/register':
            // echo 'register in switch reached';
            $userAuthController = new UserAuthController();
            $userAuthController->register();
            break;
        case '/login':
            // echo 'login in switch reached';
            $userAuthController = new UserAuthController();
            $userAuthController->login();
            break;
        default:
    }
}


