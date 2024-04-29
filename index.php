<?php

// Load the composer autoloader
require "vendor/autoload.php";

// Load the contents of the .env file into $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "Bienvenu dans la joie exotique";