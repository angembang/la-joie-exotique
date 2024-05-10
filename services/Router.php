<?php

/**
 * Class Router
 * Handles the routing of requests.
 */ 
 class Router
{
  /**
   * @var PageController Instance of the PageController class.
   */
  private PageController $pageController;
  /**
   * @var ProductController Instance of the ProductController class.
   */
  private ProductController $productController;
  /**
   * @var AuthController Instance of the AuthController class.
   */
  private AuthController $authController;
  /**
   * @var ShowController Instance of the ShowController class.
   */
  private ShowController $showController;

  /**
   * Constructor
   * 
   * @param PageController $pageController The page controller instance.
   * @param ProductController $productController The product controller instance.
   * @param AuthController $authController The authentication controller instance.
   * @param ShowController $showController The show controller instance.
   */
  public function __construct(
    PageController $pageController, 
    ProductController $productController, 
    AuthController $authController, 
    ShowController $showController)
  {
    $this->pageController = $pageController;
    $this->productController = $productController;
    $this->authController = $authController;
    $this->showController = $showController;
  }
  
  
  /**
   * Handles the incoming request based on the provided $_GET parameters
   * 
   * @param array $get The associative array of $_GET parameters.
   * 
   * @return void
   */
  public function handleRequest(array $get): void 
  {
    // Check if a route is provided
    if(isset($get["route"])) {
      switch($get["route"]) {
        case "register":
          // Route for displaying the register form
          $this->authController->register();
          break;

        case "checkRegister":
           // Route for checking the registration form submission
          $this->authController->checkRegister();
          break;

        case "login":
          // Route for displaying the login form
          $this->authController->login();
          break;

        case "checkLogin":
          // Route for checking the login form submission
          $this->authController->checkLogin();
          break;

        case "logout":
          // Route for logging out the user
          $this->authController->logout();
          break;

        case "add-product":
          // Route for displaying the product addition form
          $this->productController->addProduct();
          break;

        case "checkAddProduct":
          // Route for checking the product addition form submission
          $this->productController->checkAddProduct();
          break;

        case "search-product":
          // Route for searching products
          $this->productController->searchProduct(); 
          break;

        case "products":
          // Route for displaying all products
          $this->showController->showProducts();
          break;

        case "category-products": 
          // Route for displaying products by category
          $this->showController->showProductsByCategory();
          break;

        case "tag-products":
          // Route for displaying products by tag
          $this->showController->showProductByTag();
          break;

        case "product-detail":
          // Route for displaying product details
          $this->showController->showProductDetail(); 
          break;

        case "card":
          // Route for displaying the shopping cart
          $this->showController->showCard();
          break;
          
        case "order":
          // Route for placing an order
          $this->productController->order();
          break;

        case "confirm-order":
          // Route for confirming an order
          $this->productController->confirmOrder();
          break;

        case "about":
          // Route for displaying the about page
          $this->pageController->about();
          break;

        default:
         // Default route, display the home page
          $this->pageController->home();
          break;
      }
    } else {
      // Route is not provided. Render the home page.
      $this->pageController->home();
    }
  }
}