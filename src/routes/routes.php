<?php

require_once __DIR__ . '/../../config/dbconnection.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class Router {
    private $dispatcher;

    public function __construct(){
        $this->dispatcher = simpleDispatcher(function(RouteCollector $r) {
            // usage: $r->addRoute('REQ METHOD', '/route', ['controller', 'function from controller']);

            // users
            $r->addRoute('POST', '/register', ['UserAuthController', 'register']);
            $r->addRoute('POST', '/login', ['UserAuthController', 'login']);

            // posts
            
        });
    }
}