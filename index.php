<?php
session_start();

// Load the composer autoloader
require "vendor/autoload.php";

// Load the contents of the .env file into $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Check if the CSRF token is not set in the session
if(!isset($_SESSION["csrf-token"])) {
  // Generate a CSRF token
  $tokenManager = new CSRFTokenManager();
  $token =  $tokenManager->generateCSRFToken();

  // Store the CSRF token in the session
  $_SESSION["csrf-token"] = $token;
}

/**
 * @var Router $router An instance of the Router class for handling requests.
 */ 
$router = new Router();

/**
 * Handle the request based on the $_GET parameters
 */
$router->handleRequest($_GET);
//echo $_GET["id"];
//dump($_SESSION["cart"]);
//var_dump($_SESSION["cart"]); 
