<?php

/**
 * Class ShopController
 * 
 * This controller handles the shopping cart operations including showing the cart,
 * adding items to the cart, updating item quantities, clearing the cart, deleting items from the cart,
 * and displaying the payment form. 
 */
class ShopController extends AbstractController
{ 
    /**
     * Show the shopping cart.
     * 
     * This method retrieves the products in the cart along with their images and quantities,
     * calculates the total amount, and renders the shopping cart view. 
     */
    public function showCart(): void
    {
        // Check if the cart is empty and show an empty message if necessary
        $emptyMessage = isset($_GET['empty']) && $_GET['empty'] === 'true' ? "Votre panier est vide. Voulez-vous continuer vos achats?" : null;

        if (!isset($_SESSION["cart"]) || empty($_SESSION["cart"])) {
            $this->render("shoppingCart", ["emptyMessage" => $emptyMessage]);
        return;
        }
        // Retrieve product IDs from the session cart
        $ids = array_keys($_SESSION["cart"]);
        // Instantiate the product manager
        $productManager = new ProductManager();
        // Initialize an empty array for stocking products with their images and total amount
        $productsWithImages = [];
        $totalAmount = 0;
        // Loop through each product ID and retrieve the product details and images
        foreach($ids as $id) {
            $product = $productManager->findProductById($id);
            if($product) {
                // Retrieve images for the product
                $imageManager = new ImageManager();
                $imageIds = [
                    $product->getImage1Id(),
                    $product->getImage2Id(),
                    $product->getImage3Id(),
                    $product->getImage4Id()
                ];
        
                $images = array_filter(array_map(function($imageId) use ($imageManager) {
                    return $imageId ? $imageManager->findImageById($imageId) : null;
                }, $imageIds));

                // Calculate the quantity of the product in the cart
                $quantityInCart = isset($_SESSION["cart"][$id]) ? $_SESSION["cart"][$id] : 0;

                // Calculate subtotal for the current product
                $quantityInCart = $_SESSION["cart"][$id];
                $subtotal = $product->getPrice() * $quantityInCart;

                // Add subtotal to total amount
                $totalAmount += $subtotal;
        
                // Add images to the product
                $product->images = $images;

                // Add product with images to the array and subtotal to the array
                $productsWithImages[] = [
                    "product" => $product,
                    "images" => $images,
                    "quantityInCart" => $quantityInCart,
                    "subtotal" => $subtotal
                ];
            }            
        }
        // Store the total amount in session
        $_SESSION['totalAmount'] = $totalAmount;
               
        // Render the shopping cart view with the retrieved products and total amount
        $this->render("shoppingCart", [
           "productsWithImages" => $productsWithImages,
           "totalAmount" => $totalAmount,
           "emptyMessage" => null 
        ]);
    }


    /**
     * Add a product to the shopping cart.
     * 
     * This method adds a product to the session cart or increments its quantity if it already exists in the cart. 
     */
    public function addToCart(): void
    {
        try {
            // Start the session if it does not exist
            if(!isset($_SESSION)) {
                session_start();
            }
           // Initialize the cart session if it does not exist
           if(!isset($_SESSION["cart"])) {
                $_SESSION["cart"] = array();
           }    
            // Retrieve the product ID from the GET parameters
            if(isset($_GET["id"])) {
                $id = $_GET["id"];
                // Instantiate the product manager and check if the product exists
                $productManager = new ProductManager();
                $product = $productManager->findProductById($id);
                if(!$product) {
                    die("Ce produit n'existe pas");
                }
                // Add or increment the product quantity in the cart
                if (isset($_SESSION["cart"][$id])) {
                    $_SESSION["cart"][$id]++;
                } else {
                    $_SESSION["cart"][$id] = 1;
                }
                $response = ["success" => true, "message" => "Le produit a bien été ajouté au panier", "cart" => $_SESSION["cart"]];
            } else {
                $response = ["success" => false, "message" => "ID du produit manquant"];
            }
        } catch (Exception $e) {
            error_log("Failed to add the product to the cart: " . $e->getMessage());
            $response = ["success" => false, "error" => $e->getMessage()];
        }
        // Return a JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }


    /*
     * Update the quantity of a product in the shopping cart.
     * 
     * This method increments or decrements the quantity of a product in the session cart
     * and returns the updated quantity, subtotal, and total amount.
     */
    public function updateQuantity() {
        // Get the input data from the request
        $data = json_decode(file_get_contents('php://input'), true);
        $productId = $data['product_id'] ?? null;
        $action = $data['action'] ?? null;
        
        // Validate the input data
        if (!$productId || !$action) {
            echo json_encode(['error' => 'Invalid input']);
            exit();
        }
        // Start the session if it does not exist
        if (!isset($_SESSION)) {
            session_start();
        }
        // Check if the product is in the cart and update its quantity
        if (isset($_SESSION["cart"][$productId])) {
            $productManager = new ProductManager();
            $product = $productManager->findProductById($productId);

            if ($action == 'increment') {
                $_SESSION["cart"][$productId]++;
            } elseif ($action == 'decrement' && $_SESSION["cart"][$productId] > 1) {
                $_SESSION["cart"][$productId]--;
            }

            $quantity = $_SESSION["cart"][$productId];
            $subtotal = number_format($product->getPrice() * $quantity, 2);
            
            // Calculate the total amount of the cart
            $totalAmount = number_format(array_sum(array_map(function($id) use ($productManager) {
                $product = $productManager->findProductById($id);
                return $product->getPrice() * $_SESSION["cart"][$id];
            }, array_keys($_SESSION["cart"]))), 2);

            echo json_encode([
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'totalAmount' => $totalAmount
            ]);
        } else {
            echo json_encode(['error' => 'Product not found in cart']);
        }
        exit();
    }


    /*
     * Clear the shopping cart.
     * 
     * This method removes all items from the session cart and redirects to the shopping cart page.
     */
    public function clearCart() {
        if (!isset($_SESSION)) {
            session_start();
        }

         // Clear the cart
        unset($_SESSION["cart"]);

        // Redirect to the shopping cart page
        header("Location: /index.php?route=shopping-cart");
        exit();
    }


    /*
     * Delete a product from the shopping cart.
     * 
     * This method removes a product from the session cart and returns the updated total amount.
     */
    public function deleteProduct() {
        // Get the input data from the request
        $data = json_decode(file_get_contents('php://input'), true);
        $productId = $data['product_id'] ?? null;

        // Validate the input data
        if (!$productId) {
            echo json_encode(['error' => 'Invalid input']);
            exit();
        }
        // Start the session if it does not exist
        if (!isset($_SESSION)) {
            session_start();
        }
        // Check if the product is in the cart and remove it
        if (isset($_SESSION["cart"][$productId])) {
            unset($_SESSION["cart"][$productId]);
            // Calculate the updated total amount of the cart
            $productManager = new ProductManager();
            $totalAmount = array_sum(array_map(function($id) use ($productManager) {
                $product = $productManager->findProductById($id);
                return $product->getPrice() * $_SESSION["cart"][$id];
            }, array_keys($_SESSION["cart"])));

            echo json_encode(['totalAmount' => $totalAmount]);
        } else {
            echo json_encode(['error' => 'Product not found in cart']);
        }
        exit();
    }


    /*
     *
     */
    public function placeOrder(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION)) {
                session_start();
            }
    
            $userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;
            $guestName = $_POST['guest-name'] ?? null;
            $city = $_POST['city'] ?? null;
            $postalCode = $_POST['postalCode'] ?? null;
            $street = $_POST['street'] ?? null;
            $number = $_POST['number'] ?? null;
            $digicodeOrApptName = $_POST['digicode-or-appt-name'] ?? null;
            $totalAmount = $_SESSION['totalAmount'];
            $orderDate = new DateTime();
            $status = 'Pending';
    
            // Log the received data
            error_log("Order data: userId=$userId, guestName=$guestName, totalAmount=$totalAmount, status=$status");
    
            // Create the order
            $orderManager = new OrderManager();
            $order = new Order(null, $userId,  $orderDate, $totalAmount, $status, null, $guestName);
            $createdOrder = $orderManager->createOrder($order);
    
            if ($createdOrder) {
                $cart = $_SESSION['cart'];
                $orderProductManager = new OrderProductManager();
    
                foreach ($cart as $productId => $quantity) {
                    $orderProductManager->createOrderProduct($createdOrder->getId(), $productId, $quantity);
                }
    
                unset($_SESSION['cart']);
    
                $this->redirect("index.php?route=payment-form&order_id={$createdOrder->getId()}");
            } else {
                $_SESSION['error-message'] = "Failed to create order.";
                $this->redirect("index.php?route=shopping-cart");
            }
        }
    }


    /*
     *
     */
    public function showOrders() {
        // Démarrer la session si elle n'est pas déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifiez que l'utilisateur est bien un administrateur, sinon redirigez-le
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('index.php?route=home');
            return;
        }

        // Récupérez toutes les commandes
        $orderManager = new OrderManager();
        $orders = $orderManager->findAll();

        // Affichez les commandes
        $this->render("adminOrders", ['orders' => $orders]);
    }


    /*
     * Display the payment form.
     * 
     * This method renders the payment form view.
     */
    public function showPaymentForm() 
    {
        $this->render("paymentForm", []);
    }
    
    
    /**
     * 
     * 
     */
    public function createStripe(): void 
    {
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
    }
    
    
    /**
     * 
     * 
     */
    public function paymentSuccess(): void
    {
        try {
             // Activer l'affichage des erreurs pour ce script
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        // Définir le chemin du fichier de log
        $logDir = __DIR__ . '/log';
        $logfile = $logDir . '/debug.log';

        // Vérifier si le répertoire de log existe, sinon le créer
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        // Vérifier si le fichier de log est accessible
        if (!file_exists($logfile)) {
            touch($logfile);
        }
        if (!is_writable($logfile)) {
            throw new Exception("Le fichier de log n'est pas accessible en écriture.");
        }

        // Log tous les paramètres GET reçus
        error_log("Received GET parameters: " . print_r($_GET, true), 3, $logfile);

        // Retrieve the redirection parameters
        $paymentIntentId = $_GET["payment_intent"] ?? null;
        $guestName = isset($_GET["guest_name"]) ? urldecode($_GET["guest_name"]) : null;
        $totalAmount = isset($_GET["total_amount"]) ? urldecode($_GET["total_amount"]) : null;
        $userId = isset($_GET["user"]) ? urldecode($_GET["user"]) : null;

        // Log des paramètres reçus
        error_log("Received parameters: Payment Intent ID = $paymentIntentId, Guest Name = $guestName, Total Amount = $totalAmount, User ID = $userId", 3, $logfile);

        if (!$paymentIntentId || !$totalAmount) {
            throw new Exception("Missing payment details.");
        }

        // Stocker les paramètres récupérés dans la session
        $_SESSION['debug'] = [
            "Payment Intent ID" => $paymentIntentId,
            "Guest Name" => $guestName,
            "Total Amount" => $totalAmount,
            "User ID" => $userId
        ];

        // Check the payment status
        $stripe = new \Stripe\StripeClient($_ENV["API_KEY"]);
        $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentId);

        if ($paymentIntent->status !== "succeeded") {
            throw new Exception("Payment did not succeed.");
        }

        // Create an order object and persist it to the database
        $createdAt = new DateTime();
        $status = "commande payée en attente de préparation";
        // Convert totalAmount to float
        $totalAmount = (float)$totalAmount;

        // Stocker les informations avant la création de la commande
        $_SESSION['debug']["Before Creating Order"] = [
            "User ID" => $userId,
            "Guest Name" => $guestName,
            "Total Amount" => $totalAmount
        ];

        $orderModel = new Order(null, $userId, $guestName, $createdAt, $totalAmount, $status, null);
        $orderManager = new OrderManager();
        $order = $orderManager->createOrder($orderModel);

        // Retrieve the created order identifier
        $orderId = $order->getId();

        if (!$orderId) {
            throw new Exception("Failed to create order.");
        }

        // Stocker les informations après la création de la commande
        $_SESSION['debug']["Order Created"] = [
            "Order ID" => $orderId
        ];

        // Log de la création de la commande
        error_log("Order created with ID: $orderId", 3, $logfile);

        // Update the stock quantity
        // ...

        // Redirect to the confirm order page
        $this->redirect("index.php?route=order-confirmation&order_id={$order->getId()}");
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage(), 3, $logfile); // Log the error message
        $_SESSION["debug"] = [
            "Error" => $e->getMessage(),
            "GET Parameters" => $_GET // Add this line to store GET parameters in session
        ];
        header("Location: /php/la-joie-exotique/templates/logErrorPage.phtml");
        exit();
    }
}

    
    /**
     * 
     * 
     */
    public function orderConfirmation(): void 
    {
        $this->render("orderConfirm", []);
    }

}