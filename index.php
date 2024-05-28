<?php

// Load the composer autoloader
require "vendor/autoload.php";

// Load the contents of the .env file into $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/**
 * @var Router $router An instance of the Router class for handling requests.
 */ 
$router = new Router();

/**
 * Handle the request based on the $_GET parameters
 */
$router->handleRequest($_GET);
