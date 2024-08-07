<?php

/**
 * Class Router
 * Handles the routing of requests.
 */ 
 class Router
{
  /**
   * Handles the incoming request based on the provided $_GET parameters
   * 
   * @param array $get The associative array of $_GET parameters.
   * 
   * @return void
   */
  public function handleRequest(array $get): void 
  {
    // Instanciate necessary controllers
    $authController = new AuthController();
    $pageController = new PageController();
    $productController = new ProductController();
    $shopController = new ShopController();

    // Check if a route is provided
    if(isset($get["route"])) {
      switch($get["route"]) {
        case "register-login":
          // Route for displaying the register form
          $authController->registerLogin();
          break;

        case "checkRegister":
           // Route for checking the registration form submission
           $authController->checkRegister();
          break;

        case "checkLogin":
          // Route for checking the login form submission
          $authController->checkLogin();
          break;

        case "admin":
          // Route for displaying the admin page
          $pageController->adminHome();
          break;

        case "logout":
          // Route for logging out the user
          $authController->logout();
          break;

        case "add-product":
          // Route for displaying the product addition form
          $productController->addProduct();
          break;

        case "checkAddProduct":
          // Route for checking the product addition form submission
          $productController->checkAddProduct();
          break;

        case "search-product":
          // Route for searching products
          $productController->searchProductsByKeyword(); 
          break;

          case "product":
            if (isset($get["product_id"])) {
                $productId = (int)$get["product_id"];
                $productController->showProductById($productId);
            } else {
                error_log("Routeur: Aucun ID de produit reçu");
                $pageController->error();
            }
          break;

        case "category-products": 
          // Route for displaying products by category
          // check if category identifier is provided
          if(isset($get["category_id"])) {
            $productController->showProductsByCategoryId($get["category_id"]);
          } else {
            // Redirect to error page if category id is not provided
            $pageController->error();
          }
          break;
          
        case "category-alimentary": 
          // Route for displaying products of alimentary category
          $productController->showProductsOfAlimentaryCategory();
          break;
          
        case "category-cosmetic": 
          // Route for displaying products of alimentary category
          $productController->showProductsOfCosmeticCategory();
          break;

        case "tag-products":
          // Route for displaying products by tag
          $productController->showProductByTag();
          break;

        case "shopping-cart":
          // Route for displaying the shopping cart
          $shopController->showCart();
          break;

        case "clean-url":
          // Route for cleaning url after payment
          $pageController->cleanUrl();
          break;

        case "cart":
          // Route for displaying the shopping cart
          $shopController->addToCart();
          break;

        case "update-quantity":
          $shopController->updateQuantity();
          break;

        case "clear-cart":
          $shopController->clearCart();
          break;

        /*case "payment-success":
          $shopController->paymentSuccess();
          break;*/
        
        case "delete-product":
          $shopController->deleteProduct();
          break;
          
        case "create-paiement-stripe":
          // Route for displaying a stripe paiement
          $shopController->createStripe();
          break;
          
        case "payment-success":
          // Route for the payment success
          $shopController->paymentSuccess();
          break;
          
        case "order-confirmation":
          // Route displaying the order confirmation place
          $shopController->orderConfirmation();
          break;
          
        case "download-facture":
          // Route for dowloading the facture
          $shopController->downloadFacture();
          break;
          
        case "order":
          // Route for placing an order
          $productController->order();
          break;

        case "show-orders":
          // Route for displaying orders
          $shopController->showOrders();
          break;
          
        case "show-orders-by-guest-name":
          // Route for displaying orders by the guest name
          $shopController->showOrdersByGuestName();
          break;
          
        case "order-details":
          // Route for displaying the order details
          $shopController->orderDetails();
          break;
          
        case "update-order-status":
          // Route for displaying the update order status form
          $shopController->updateOrderStatus();
          break;
          
         case "checkUpdate-order-status":
          // Route for checking the updating order status form
          $shopController->checkUpdateOrderStatus();
          break;

        case "payment-form":
          // Route for displaying the payment form
          $shopController->showPaymentForm();
          break;
          
        case "admin-inventory":
          // Route for displaying the inventaire page
          $shopController->inventory();
          break;

        case "about":
          // Route for displaying the about page
          $pageController->about();
          break;

        case "legal-notice":
          // Route for displaying the legal notice page
          $pageController->legalnotice();
          break;

        case "privacy-policy":
          // Route for displaying the privacy policy page
          $pageController->privacypolicy();
          break;

        case "contact":
          // Route for displaying the contact page
          $pageController->contact();
          break;
          
        default:
          // Default route, display the home page
          $pageController->home();
          break;
      }
    } else {
      // Route is not provided. Render the home page.
      $pageController->home();
    }
  }
}