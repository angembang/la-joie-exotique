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
    $showController = new ShowController();

    // Check if a route is provided
    if(isset($get["route"])) {
      switch($get["route"]) {
        case "register":
          // Route for displaying the register form
          $authController->register();
          break;

        case "checkRegister":
           // Route for checking the registration form submission
           $authController->checkRegister();
          break;

        case "login":
          // Route for displaying the login form
          $authController->login();
          break;

        case "checkLogin":
          // Route for checking the login form submission
          $authController->checkLogin();
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
          $productController->searchProduct(); 
          break;

        case "products":
          // Route for displaying all products
          $showController->showProducts();
          break;

        case "category-products": 
          // Route for displaying products by category
          $showController->showProductsByCategory();
          break;

        case "tag-products":
          // Route for displaying products by tag
          $showController->showProductByTag();
          break;

        case "product-detail":
          // Route for displaying product details
          $showController->showProductDetail(); 
          break;

        case "card":
          // Route for displaying the shopping cart
          $showController->showCard();
          break;
          
        case "order":
          // Route for placing an order
          $productController->order();
          break;

        case "confirm-order":
          // Route for confirming an order
          $productController->confirmOrder();
          break;

        case "about":
          // Route for displaying the about page
          $pageController->about();
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