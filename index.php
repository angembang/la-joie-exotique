<?php

// Charge l'autoload de composer
require "vendor/autoload.php";

// Charge le contenu du .env dans $_ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "Bienvenu dans la joie exotique";