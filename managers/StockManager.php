<?php

/**
 * Manages the retrieval and persistence of Stock objects in the platform
 */
class StockManager extends AbstractManager
{
  /**
   * Creates a new stock and persists it in the database
   * 
   * @param int $productId The product identifier of the stock object to be created.
   * @param int $quantity The product quantity of the stock object to be created.
   * 
   * @return bool True if successfull, false if not.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function createStock(int $productId, int $quantity): bool
  {
    try {
      // Prepare the SQL query to insert the stock into the database.
      $query = $this->db->prepare("INSERT INTO stocks 
      (product_id, quantity) 
      VALUES 
      (:product_id, :quantity)");

      // Bind parameters with their values.
      $parameters = [
        ":product_id" => $productId,
        ":quantity" => $quantity
      ];

      // Execute the query with parameters.
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return true;
      }
      return false;

    } catch (PDOException $e) {
      error_log("Failed to create a new stock: " . $e->getMessage(), $e->getCode());
      throw new PDOException("Failed to create a new stock");
    }
  }


  /**
   * Retrieves the stock by its product identifier
   * 
   * @param int $productId The product identifier of the stock.
   * 
   * @return Stock|null The retrieved stock or null if not found.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function findStockByProductId(int $productId): ?Stock
  {
    try {
      // Prepare the SQL query to retrieve the stock by its product identifier.
      $query = $this->db->prepare("SELECT s.*, p.* 
      FROM stocks s
      JOIN products p
      ON s.product_id = p.id 
      WHERE s.product_id = :product_id");

      // Bind the parameter with its value.
      $parameter = [
        ":product_id" => $productId
      ];

      // Execute the query with the parameter.
      $query->execute($parameter);

      // Fetch the retrieved stock data from the database.
      $stockData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if the stock data is found.
      if ($stockData) {
        $stock = new Stock(
          $stockData["product_id"],
          $stockData["quantity"]
        );
        return $stock;
      }
      return null;

    } catch (PDOException $e) {
        error_log("Failed to find the stock: " . $e->getMessage(), $e->getCode());
        throw new PDOException("Failed to find the stock");
    }
  }


  /**
   * Updates the quantity of stock for a specific product
   * 
   * @param int $productId The product identifier.
   * @param int $quantity The new quantity of the stock.
   * 
   * @return bool True if the update was successful, false if not.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function updateStockQuantityByProductId(int $productId, int $quantity): bool 
  {
    try {
      // Prepare the SQL query to update the stock quantity for the specified product.
      $query = $this->db->prepare("UPDATE stocks SET 
      quantity = :quantity 
      WHERE 
      product_id = :product_id");

      // Bind parameters with their values.
      $parameters = [
        ":quantity" => $quantity,
        ":product_id" => $productId
      ];

      // Execute the query with parameters.
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        // Return true
        return true;
      }
      // Return false
      return false;

    } catch (PDOException $e) {
      // Log error and throw exception if an error occurs during the database operation.
      error_log("Failed to update stock quantity: " . $e->getMessage(), $e->getCode());
      throw new PDOException("Failed to update stock quantity");
    }
  }


  /**
   * Deletes the stock by the product identifier
   * 
   * @param int $productId The product identifier of the stock
   * 
   * @return bool True if successfull, false if not
   * 
   * @throws PDOException if an error occurs during the database operation
   */
  public function deleteStockByProductId(int $productId): bool 
  {
    try {
      // Prepare the SQL query to delete a stock by its product identifier
      $query = $this->db->prepare("DELETE FROM stocks WHERE product_id = :product_id");

      // bind the parameter with its value
      $parameter = [
        ":product_id" => $productId
      ];

      // Execute the query with the parameter
      $success = $query->execute($parameter);

      // CHeck if success
      if($success) {
        return true;
      }
      return false;

    } catch (PDOException $e) {
      // Log error and throw exception if an error occurs during the database operation.
      error_log("Failed to delete the stock: " . $e->getMessage(), $e->getCode());
      throw new PDOException("Failed to delete the stock");
    }
  }

}
