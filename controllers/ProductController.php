<?php

/**
 * Manages the retrieval and persistence of Product object to the platform
 */
class ProductController extends AbstractController
{
    /**
     * Displays the registration product form with necessary data.
     */
    public function addProduct(): void 
    {
        $categoryManager = new CategoryManager();
        $categories = $categoryManager->findAll();

        $tagManger = new TagManager();
        $tags = $tagManger->findAll();
   
        $this->render("productForm", [
            "categories" =>$categories,
            "tags" => $tags
        ]);
    }


    /**
     * Validates product registration data and creates a new product based on provided information.
     */
    public function checkAddProduct(): void
    {
        try {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                // Check if required fields are set
                $requiredFields = ["name", "description", "price", "tagId", "categoryId", "quantity", "csrf-token"];
      
                foreach ($requiredFields as $field) {
                    if (!isset($_POST[$field]) || empty($_POST[$field])) {
                        throw new Exception("Veuillez remplir tous les champs");
                    }
                }

                $tokenManager = new CSRFTokenManager();
                if (!isset($_POST["csrf-token"]) || !$tokenManager->validateCSRFToken($_POST["csrf-token"])) {
                    throw new Exception("Invalid token");
                }

                $name = htmlspecialchars($_POST["name"]);
                $description = htmlspecialchars($_POST["description"]);
                $price =htmlspecialchars($_POST["price"]);
                $tagId =$_POST["tagId"];
                $categoryId = $_POST["categoryId"];
                $quantity = htmlspecialchars($_POST["quantity"]);
          
                // Handle image uploads
                $imageFields = ["image1", "image2", "image3", "image4"];
                $uploadedImageIds = [null, null, null, null]; // Assuming have 4 image slots
                $uploadDir = 'uploads/';

                foreach ($imageFields as $index => $imageField) {
                    if (isset($_FILES[$imageField]) && $_FILES[$imageField]['error'] == UPLOAD_ERR_OK) {
                        $imageName = uniqid() . '_' . basename($_FILES[$imageField]['name']);
                        $imagePath = $uploadDir . $imageName;
                        if (move_uploaded_file($_FILES[$imageField]['tmp_name'], $imagePath)) {
                            $image = new Image(null, $imageName, $imagePath, $imageName);
                            $imageManager = new ImageManager();
                            $createdImage = $imageManager->createImage($image);
                            if ($createdImage) {
                                $uploadedImageIds[$index] = $createdImage->getId(); // Assuming getId() returns the ID of the created image
                            } else {
                                throw new Exception("Failed to insert image $imageField to the database");
                            }
                        } else {
                            throw new Exception("Failed to download the image $imageField");
                        }
                    }
                }

                // Create product
                $product = new Product(
                    null,
                    $name,
                    $description,
                    $price,
                    $tagId,
                    $categoryId,
                    $uploadedImageIds[0],
                    $uploadedImageIds[1],
                    $uploadedImageIds[2],
                    $uploadedImageIds[3]
                );

                $productManager = new ProductManager();
                $createdProduct = $productManager->createProduct($product);

                if ($createdProduct) {
                    $categoryManager = new CategoryManager();
                    $categories = $categoryManager->findAll();

                    $tagManager = new TagManager();
                    $tags = $tagManager->findAll();

                    $this->render("productForm", [
                        "categories" => $categories,
                        "tags" => $tags
                    ]);
                } else {
                    throw new Exception("Une erreur s'est produite lors de l'ajout du produit");
                }
            } else {
                throw new Exception("Le formulaire n'est pas soumis par la méthode POST");
            }
        } catch (Exception $e) {
            $_SESSION["error-message"] = $e->getMessage();
        }
    }


    /**
     * Add the product to the cart.
     * This function verifies if the quantity is provided and calculates the total price.
     * If the quantity is provided, it adds the product details in the cart.
     *
     * @return void
     * 
     * @throws PDOException if an error occurs during the database operation.
     */
    public function checkAndAddProductToCart(): void 
    {
        try {
            // Check if the form is submitted via POST method
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                // Check if the quantity is provided and not empty
                if(isset($_POST["quantity"])) {
                    // Get the quantity from the form
                    $quantity = intval($_POST['quantity']);
                    // Get the productId
                    $productId = intval($_POST['product_id']);
    
                    // Instantiate the product manager for retrieve the product by its unique identifier
                    $productManager = new ProductManager();
                    $product = $productManager->findProductById($productId);
    
                    // Check if the product is found
                    if($product) {
                        $productPrice = $product->getPrice();
                        $subtotal = $productPrice * $quantity;
                        $productName = $product->getName();
                        // Product data
                        $responseData = [
                            "productId" => $productId,
                            "productName" => $productName,
                            "quantity" => $quantity,
                            "subtotal" => $subtotal
                        ];

                        $this->render("shoppingCart", [
                            "product" => $responseData
                        ]);
                    } else {
                        throw new PDOException("Failed to find the product");
                    }
    
                } else {
                    throw new Exception("the quantity is not provided");
                }
            } else {
                throw new PDOException("The form is not submitted by post method");
            }
    
        } catch(PDOException $e) {
            error_log("Failed to add product to the cart: ".$e->getMessage().$e->getCode());
            throw new PDOException("Failed to add product to the cart");
        }
    }


    /*
     * Displays products by category
     *
     * @return void
     */
    public function showProductsByCategoryId(int $categoryId) 
    {
        try {
            $categoryManager = new CategoryManager();
            $productManager = new ProductManager();
            $imageManager = new ImageManager();
            $category =  $categoryManager->findCategoryById($categoryId);

            if($category) {
                $categoryId = $category->getid();
                $products = $productManager->findProductsByCategoryId($categoryId);

                foreach($products as $product) {
                    $productImages = [
                        $product->getImage1Id(),
                        $product->getImage2Id(),  
                        $product->getImage3Id(),
                        $product->getImage4Id()  
                    ];
                    $images = array_filter(array_map(function ($imageId) use ($imageManager) {
                        return $imageId ? $imageManager->findImageById($imageId) :null;
                    },  $productImages));

                    $product->images = $images;
                }
                $this->render("productsCategory", [
                    "category" => $category,
                    "products" => $products
                ]);
            }
        } catch(Exception $e) {
            error_log("Failed to show products by category: ".$e->getMessage().$e->getCode());
            throw new Exception("Failed to show products by category");
        }

    }


    /**
    * 
    */
    public function showProductByTag(): void 
    {
    
    } 



    /**
     * 
     */
    public function searchProduct(): void
    {
    }


    /**
     * Show product by its unique identifier
     * 
     * @param int $productId The unique identifier of the product
     * 
     * @return Product|null The retrieved product object, or null if not found
     * 
     * @throws PDOException if an error occurs during database operation
     */
    public function showProductById(int $productId): void 
    {
        try {
                $productManager = new ProductManager();
                $product = $productManager->findProductById($productId);
  
                if ($product) {
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
  
                    $product->images = $images;
  
                    error_log("Produit trouvé: " . print_r($product, true));
                    $this->render("product", [
                        "product" => $product
                    ]);
                } else {
                    error_log("Aucun produit trouvé pour l'ID: " . $productId);
                    $this->render("error", ["message" => "Produit non trouvé."]);
                }
            } catch (PDOException $e) {
                error_log("Échec lors de la recherche du produit: " . $e->getMessage() . $e->getCode());
                throw new PDOException("Échec lors de la recherche du produit");
            }
        }
  
  
    /** 
     * 
    */
    public function order(): void 
    {
    }


    /**
     * 
     */
    public function confirmOrder(): void 
    {
    }

}