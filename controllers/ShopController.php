<?php

/**
 * 
 */
class ShopController extends AbstractController
{ 
  /**
   * 
   */
  public function showProductDetail(): void
  {

  }


    /**
     * 
     */
    public function showCart(): void
    {
        // Check if there is no key
        if (!isset($_SESSION["cart"]) || empty($_SESSION["cart"])) {
            echo "Votre panier est vide";
            return;
        } 
        // Retrieve keys of the array session
        $ids = array_keys($_SESSION["cart"]);
        // Instantiate the product manager
        $productManager = new ProductManager();
        // Initialize an empty array for stocking products with their images and total amount
        $productsWithImages = [];
        $totalAmount = 0;
        // Retrieve each product with images
        foreach($ids as $id) {
            $product = $productManager->findProductById($id);
            if($product) {
                // Retrieve images
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

                // Calculate the quantity of the product
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
        // Store $totalAmount in session
        $_SESSION['totalAmount'] = $totalAmount;
               
        
        $this->render("shoppingCart", [
           "productsWithImages" => $productsWithImages,
           "totalAmount" => $totalAmount
        ]);
    }


    /**
     * 
     */
    public function addToCart(): void
    {
        try {
            // Check if an session exist
            if(!isset($_SESSION)) {
                // Run the session
                session_start();
            }
           // Check if the session cart exist
           if(!isset($_SESSION["cart"])) {
                $_SESSION["cart"] = array();
           }    
            // Retrieve the product id
            echo $_GET["id"];
            if(isset($_GET["id"])) {
                $id = $_GET["id"];
                // Check if the product with the provided identifier exist
                $productManager = new ProductManager();
                $product = $productManager->findProductById($id);
                if(!$product) {
                    die("Ce produit n'existe pas");
                }
                // Add product to the cart
                if(isset($_SESSION["cart"][$id])) {
                    $_SESSION["cart"][$id]++;    
                } else {
                    $_SESSION["cart"][$id] = 1;  
                    echo "Le produit a bien été ajouté au panier";
                    // Display the product
                    dump($_SESSION["cart"]); 
                    // Redirect to the shoppind card page
                   
                }
            }            
            
        } catch(Exception $e) {
            error_log("Failed to add the product to the cart: " . $e->getMessage());
            $this->renderJson(["success" => false, "error" => $e->getMessage()]);       
        }
    }


    /*
     *
     */
    public function updateQuantity() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $data = json_decode(file_get_contents('php://input'), true);
        $productId = $data['product_id'] ?? null;
        $action = $data['action'] ?? null;

        if (!$productId || !$action) {
            echo json_encode(['error' => 'Invalid input']);
            exit();
        }

        if (!isset($_SESSION)) {
            session_start();
        }

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
     *
     */
    public function clearCart() {
        if (!isset($_SESSION)) {
            session_start();
        }

        // Vider le panier
        unset($_SESSION["cart"]);

        // Rediriger vers la page du panier
        header("Location: /index.php?route=shopping-cart");
        exit();
    }


    /*
     *
     */
    public function deleteProduct() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $data = json_decode(file_get_contents('php://input'), true);
        $productId = $data['product_id'] ?? null;

        if (!$productId) {
            echo json_encode(['error' => 'Invalid input']);
            exit();
        }

        if (!isset($_SESSION)) {
            session_start();
        }

        if (isset($_SESSION["cart"][$productId])) {
            unset($_SESSION["cart"][$productId]);

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
     * Display payment form
     */
    public function showPaymentForm() 
    {
        $this->render("paymentForm", []);
    }


    /*
     *
     */
    public function handlePayment()
    {
       require "create.php";
    }

   
}