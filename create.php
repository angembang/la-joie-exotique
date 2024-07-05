<?php 
// use PHP Dotenv for using stripe
require_once 'vendor/autoload.php'; 
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Retrieve the Stripe secret key from .env file
$key = $_ENV['API_KEY'];

// Create an instance of stripe client
$stripe = new \Stripe\StripeClient($key);

header('Content-Type: application/json');

try {
    // Retrieve JSON from post body
    $jsonStr = file_get_contents('php://input');
    $jsonObj = json_decode($jsonStr);

    // Create a payment intent with amount and currency in $paymentIntent
    $paymentIntent = $stripe->paymentIntents->create([
        'amount' => $jsonObj->amount * 100, // The amount in centimes
        'currency' => 'eur', // Payment devise
    ]);

    // Renvoyer le clientSecret au format JSON
    //http_response_code(200);
    //echo json_encode(['clientSecret' => $paymentIntent->client_secret]);
    $output = [
        'clientSecret' => $paymentIntent->client_secret
    ];
    echo json_encode($output);

} catch (\Stripe\Exception\ApiErrorException $e) {
    // Manage the stripe error
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
