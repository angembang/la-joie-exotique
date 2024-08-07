<?php

/**
 * Manages the retrieval and persistence of Product object in the platform
 */
class ProductManager extends AbstractManager
{
  /**
   * Creates a new Product and persists it in the database
   * 
   * @param Product $product The product object to be created.
   * 
   * @return Product The created product object with the asigned identifier.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */ 
  public function createProduct(Product $product): Product
  {
    try {
      // Prepare the SQL query to insert a new product into the database.
      $query = $this->db->prepare("INSERT INTO products (name, description, price, tag_id, category_id, image1_id, image2_id, image3_id, image4_id) VALUES 
      (:name, :description, :price, :tag_id, :category_id, :image1_id, :image2_id, :image3_id, :image4_id)");

      // Bind parameters with their values.
      $parameters = [
        ":name" => $product->getName(),
        ":description" => $product->getDescription(),
        ":price" => $product->getPrice(),
        ":tag_id" => $product->getTagId(),
        ":category_id" =>$product->getCategoryId(),
        ":image1_id" =>$product->getImage1Id(),
        ":image2_id" =>$product->getImage2Id(),
        ":image3_id" =>$product->getImage3Id(),
        ":image4_id" =>$product->getImage4Id(),
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Retrieve the last insert product identifier.
      $productId = $this->db->lastInsertId();

      // Set the identifier for the created product.
      $product->setId($productId);

      // Return the created product.
      return $product;
    
    } catch(PDOException $e) {
      error_log("Failed to create a new product: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to create a new product");
    }
  }
  
  
  /**
   * Retrieves the product by its unique identifier
   * 
   * @param int $productId The unique identifier of the product.
   * 
   * @return Product|null The retrieved product or null if product is not found
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findProductById(int $productId): ?Product
  {
    try {
      // Prepare the SQL query to retrieve the product by its unique identifier.
      $query = $this->db->prepare("SELECT * FROM products WHERE id = :id");
      
      // Bind the parameter with its value.
      $parameter = [
        ":id" => $productId
      ];

      // EXecute the query with the parameter.
      $query->execute($parameter);

      // Fetch the product data from the database.
      $productData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if product is found.
      if($productData) {
        // Instanciate a new product with retrieved data
        $product = new Product(
          $productData["id"],
          $productData["name"],
          $productData["description"],
          $productData["price"],
          $productData["tag_id"],
          $productData["category_id"],
          $productData["image1_id"],
          $productData["image2_id"],
          $productData["image3_id"],
          $productData["image4_id"]
        );
        return $product;
      }
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to find the product: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find the product");
    }
  }


  /**
   * Retrieves products by their name
   * 
   * @param string $productsName The name of products to retrieve.
   * 
   * @return array Product|null The retrieved products or null if product is not found.
   * 
   * @throws  PDOEXception If an error occurs during the database operation.
   */
  public function findProductsByName(string $productName): ?array
  {
    try {
      // Prepare the SQL query to retrieve products by their name.
      $query = $this->db->prepare("SELECT * FROM products WHERE name = :name");
      
      // Bind the parameter with its value.
      $parameter = [
        ":name" => $productName
      ];

      // EXecute the query with the parameter.
      $query->execute($parameter);

      // Fetch the product data from the database.
      $productsData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if products are found
      if($productsData) {
        return $this->hydrateProducts($productsData);
      }
        return null;
    
    } catch(PDOException $e) {
      error_log("Failed to find products: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find products");
    }
  }


  /**
   * Retrieves products by their name and price
   * 
   * @param string $productName, float $productPrice The name and price of products.
   * 
   * @return array Product|null The array of retrieved products. Null if no product is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findProductsByNameAndPrice(string $productName, float $productPrice): ?Array 
  {
    try {
      // Prepare the SQL query to retrieve products by their name and price.
      $query = $this->db->prepare("SELECT * FROM products WHERE name = :name AND price = :price");
      
      // Bind parameters with their values.
      $parameters = [
        ":name" => $productName,
        ":price"=> $productPrice
      ];
    
      // EXecute the query with parameters.
      $query->execute($parameters);

      // Fetch the product data from the database.
      $productsData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if products are found
      if($productsData) {
        return $this->hydrateProducts($productsData);
      }
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to find products: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find products");
    }  
  }
  
  
   /**
   * Retrieves  products by their tag identifier
   * 
   * @param int $tagId The tag identifier of the product.
   * 
   * @return array Product|null The array of retrieved products or null if no product is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findProductsByTagId(int $tagId): ?array 
  {
    try {
      // Prepare the SQL query to retrieve products by their name and price.
      $query = $this->db->prepare("SELECT products.*, tags.* 
      FROM products
      JOIN tags
      ON tag_id = tags.id 
      WHERE tag_id = :tag_id");
      
      // Bind the parameter with its value.
      $parameter = [
        ":tag_id" => $tagId
      ];
    
      // EXecute the query with the parameter.
      $query->execute($parameter);

      // Fetch the product data from the database.
      $productsData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if products are found
      if($productsData) {
        return $this->hydrateProducts($productsData);
      } 
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to find products: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find products");
    }
  }


   /**
   * Retrieves all products
   *
   * @return array Product|null The array of product or null if no product is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findAll(): ?array 
  {
    try {
      // Prepare the SQL query to retrieve all products into the database
      $query = $this->db->prepare("SELECT * FROM products");

      // Execute the query
      $query->execute();

      // Fetch products data from the database
      $productsData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if products data is not empty
      if($productsData) {
        return $this->hydrateProducts($productsData);
      }
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to find products: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find products");
    }  
  }


  /**
   * Retrieves products by its category identifier
   * 
   * @param int $categoryId The category identifier of the products.
   * 
   * @return array|null An array The retrieved product, or null if no product is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findProductsByCategoryId(int $categoryId): ?array
  {
    try {
      // Prepare the SQL query to retrieve products by their category identifier
      $query = $this->db->prepare("SELECT * FROM products  
      WHERE category_id = :category_id 
      ORDER BY id DESC");

      // Bind the parameter with its value
      $parameter = [
        ":category_id" => $categoryId
      ];

      // Execute the query with the parameter
      $query->execute($parameter);

      // Fetch products data from the database
      $productsData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if tags data are found
      if($productsData) {
        return $this->hydrateProducts($productsData);
      }
      return null; 

    } catch(PDOException $e) {
      error_log("Failed to find products: " .$e->getMessage());
      throw new PDOException("Failed to find products");
    }
  }
  
  
  /**
     * Retrieves a product by keyword.
     * 
     * @param String $keyword The keyword of the product.
     * 
     * @return array|null The retrieved product or null if not found.
     * 
     */
    public function searchProductByKeyword(string $keyword): ?array
    {
        // Prepare the query to retrieve product by keyword 
        $query = $this->db->prepare("SELECT * FROM products 
        WHERE (name LIKE :keyword OR description LIKE :keyword)");
        // Prepare keyword for search
        $keywordFormatted = '%' . $keyword . '%';
        // Bind parameters
        $parameters = [
            ":keyword" => $keywordFormatted
            ];
        // Execute the query with the parameter
        $query->execute($parameters); 
        // Fetch the product data from the database
        $productsData = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // Check if products data is not empty
      if($productsData) {
        return $this->hydrateProducts($productsData);
      }
      return null;
    
  }


  /**
   * Updates a product in the database
   * 
   * @param Product $product The product to be updated.
   * 
   * @return Product|null The updated product or null if not updated.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function updateProduct(Product $product): ?Product
  {
    try {
      // Prepare the SQL query to update a product.
      $query = $this->db->prepare("UPDATE products SET 
      name = :name,
      description = :description,
      price = :price,
      tag_id = :tag_id,
      category_id = :category_id,
      image1_id = :image1_id,
      image2_id = :image2_id,
      image3_id = :image3_id,
      image4_id = :image4_id 
      WHERE id = :id");

      // Bind parameters with their values
      $parameters = [
        "id" => $product->getId(),
        ":name" => $product->getName(),
        ":description" => $product->getDescription(),
        ":price" => $product->getPrice(),
        ":tag_id" => $product->getTagId(),
        ":image1_id" =>$product->getImage1Id(),
        ":image2_id" =>$product->getImage2Id(),
        ":image3_id" =>$product->getImage3Id(),
        ":image4_id" =>$product->getImage4Id()
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return $product;
      }
      return null;
    
    } catch(PDOException $e) {
      error_log("Failed to update the product: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to update the product");
    }  
  }


  /**
   * Deletes a product from the database
   * 
   * @param int $productId The unique identifier of the product to be deleted
   * 
   * @return bool True if the operation is successful, false if not.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function deleteProductById(int $productId): bool
  {
    try {
      // Prepare the SQL query to delete the retrieve product.
      $query = $this->db->prepare("DELETE FROM products WHERE id = :id");

      // Bind parameters with their values
      $parameters = [
        ":id" => $productId
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      if($success) {
        return true;
      } 
      return false;
  
    } catch(PDOException $e) {
      error_log("Failed to delete the product: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to delete the product");
    }  
  }


/**
 * Helper method to hydrate Product objects from data array.
 *
 * @param array $productsData The array of product data retrieved from the database.
 * 
 * @return array An array of Product objects hydrated from the provided data.
 */
private function hydrateProducts(array $productsData): array {
  // Initialize an empty array to store the hydrated Product objects.
  $products = [];
  
   // Loop through each product data in the array.
   foreach ($productsData as $productData) {
    // Validate the necessary keys
    $requiredKeys = ["id", "name", "description", "price", "tag_id", "category_id", "image1_id", "image2_id", "image3_id", "image4_id"];
    foreach ($requiredKeys as $key) {
        if (!array_key_exists($key, $productData)) {
            // Log an error or handle the missing key as appropriate
            error_log("Missing key: $key in product data: " . json_encode($productData));
            continue 2; // Skip this product data if a key is missing
        }
    }

    // Create a new Product object using the validated data
    $product = new Product(
        $productData["id"],
        $productData["name"],
        $productData["description"],
        $productData["price"],
        $productData["tag_id"],
        $productData["category_id"],
        $productData["image1_id"],
        $productData["image2_id"],
        $productData["image3_id"],
        $productData["image4_id"]
    );

    // Add the newly created Product object to the array.
    $products[] = $product;
}
  
  // Return the array of hydrated Product objects.
  return $products;
}
}