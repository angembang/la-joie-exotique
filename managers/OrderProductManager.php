<?php

/**
 * manages the retrieval and persistence of the order product in the platform
 */
class OrderProductManager extends AbstractManager 
{
  /**
   * Creates a new order-product relation and persists it in the database
   * 
   * @param int $orderId The order identifier of the order product to be created.
   * @param int $productId The product identifier of the order product to be created.
   * @param int $quantity The quantity of the order product to be created.
   * 
   * @return bool True if successfull, false if not.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function createOrderProduct(int $orderId, int $productId, int $quantity): bool
  {
    try {
      // Retrieve and validate the product
      $productManager = new ProductManager();
      $product = $productManager->findProductById($productId);
      if (!$product) {
        throw new PDOException("Product not found");
      }
      // Calculate the subtotal (price * quantity)
      $subtotal = $product->getPrice() * $quantity;

      // Prepare the SQL query to insert a new order-product relation into the database
      $query = $this->db->prepare("INSERT INTO orders_products
      (order_id, product_id, quantity, subtotal) 
      VALUES 
      (:order_id, :product_id, :quantity, :subtotal)");

      // Bind parameters with their values
      $parameters = [
        ":order_id" => $orderId,
        ":product_id" => $productId,
        ":quantity" => $quantity,
        ":subtotal" => $subtotal
      ];

      // Execute the query with parameters
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return true;
      }
      return false;

    } catch (PDOException | InvalidArgumentException $e) {
      error_log("Failed to create a new order-product: " . $e->getMessage().$e->getCode());
      throw new PDOException("Failed to create a new order-product");
    }
  }


  /**
   *  Retrieves products by order identifier
   * 
   * @param int $orderId The order identifier.
   * 
   * @return array|null The array of retrieved products or null if not found.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function findProductsByOrderId(int $orderId): ?array 
  {
    try {
      // Prepare the query to retrieve products by order identifier.
      $query = $this->db->prepare("SELECT po.*, p.* FROM products_orders po 
      JOIN products p 
      ON po.product_id = p.id 
      WHERE po.order_id = :order_id");

      // Bind the parameter with its value.
      $parameter = [
        ":order_id" => $orderId
      ];

      // Execute the query with the parameter.
      $query->execute($parameter);

      // Fetch products data from the database.
      $orderProductsData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if products are found
      if ($orderProductsData) {
        return $this->hydrateOrderProducts($orderProductsData);
      }
      return null; 

    } catch(PDOException $e) {
      error_log("Failed to find products of the order: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find products of the order");
    }
  }


  /**
   * Updates a product's quantity in an order
   * 
   * @param int $orderId The order identifier.
   * @param int $productId The product identifier.
   * @param int $quantity The new quantity of the product in the order.
   * 
   * @return bool True if successful, false otherwise.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function updateProductQuantityInOrder(int $orderId, int $productId, int $quantity): bool 
  {
    try {
      // Prepare the SQL query to update the product quantity in the order.
      $query = $this->db->prepare("UPDATE products_orders SET 
      quantity = :quantity, 
      subtotal = :subtotal 
      WHERE order_id = :order_id AND product_id = :product_id");

      // Calculate the new subtotal (price * quantity)
      $productManager = new ProductManager();
      $product = $productManager->findProductById($productId);
      if (!$product) {
        throw new PDOException("Product not found");
      }
      $subtotal = $product->getPrice() * $quantity;

      // Bind parameters with their values.
      $parameters = [
        ":order_id" => $orderId,
        ":product_id" => $productId,
        ":quantity" => $quantity,
        ":subtotal" => $subtotal
      ];

      // Execute the query with parameters.
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return true;
      }
      return false;

    } catch(PDOException $e) {
      error_log("Failed to update the product quantity in the order: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to update the product quantity in the order");
    }
  }


  /**
   * Removes a product from an order
   * 
   * @param int $orderId The order identifier.
   * @param int $productId The product identifier.
   * 
   * @return bool True if successful, false otherwise.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function removeProductFromOrder(int $orderId, int $productId): bool 
  {
    try {
      // Prepare the SQL query to delete the product from the order.
      $query = $this->db->prepare("DELETE FROM products_orders 
      WHERE order_id = :order_id AND product_id = :product_id");

      // Bind parameters with their values.
      $parameters = [
        ":order_id" => $orderId,
        ":product_id" => $productId
      ];

      // Execute the query with parameters.
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return true;
      }
      return false;

    } catch(PDOException $e) {
      error_log("Failed to remove the product from the order: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to remove the product from the order");
    }
  }
  
  
  /**
   * Deletes an order product by its order identifier
   * 
   * @param int $orderId The order identifier of the order product.
   * 
   * @return bool True if succesfull, false if not.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function deleteOrderProduct(int $orderId): bool 
  {
    try {
      // Prepare the SQL query to delete an order product by its unique identifier.
      $query = $this->db->prepare("DELETE FROM orders_products WHERE order_id = order_:id");

      // Bind the parameter
      $parameter = [
        ":order_id" => $orderId
      ];

      // Execute the query with the parameter.
      $success = $query->execute($parameter);
    
      // Check if success
      if($success) {
        return true;
      }
      return false;

    } catch(PDOException $e) {
      error_log("Failed to delete the order product: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to delete the order product");
    }
  }


  /**
   * Helper method to hydrate order product objects from data array.
   *
   * @param array $orderProductsData The array of product data retrieved from the database.
   * 
   * @return array An array of Order Product objects hydrated from the provided data.
   */
  private function hydrateOrderProducts(array $orderProductsData): array 
  {
    // Initialize an empty array to store the hydrated Product objects.
    $orderProducts = [];

    // Loop through each order product data in the array.
    foreach ($orderProductsData as $orderProductData) {
      // Create a new OrderProduct object using the data from the current iteration.
      $orderProduct = new OrderProduct(
        $orderProductData["order_id"],
        $orderProductData["product_id"],
        $orderProductData["quantity"],
        $orderProductData["subtotal"]
      );

      // Add the newly created Product object to the array.
      $orderProducts[] =  $orderProduct;
    }

    // Return the array of hydrated Product objects.
    return  $orderProducts;
  }
}