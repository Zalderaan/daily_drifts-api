<?php

require_once __DIR__ . '/../../config/dbconnection.php';

class Blog {

    private $pdo;

    public function __construct() {
        $connection = new Connection();
        $this->pdo = $connection->connect();
    }

    
}

