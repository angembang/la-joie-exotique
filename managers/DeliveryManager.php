<?php 

/**
 * Manages the retrieval and persistence of Delivery object to the platform
 */
class DeliveryManager extends AbstractManager 
{
  /**
   * Creates a new delivery and persists it in the database
   * 
   * @param Delivery $delivery The delivery object to be created.
   * 
   * @return Delivery|null The created delivery or null if not created.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function createDelivery(Delivery $delivery): Delivery
  {
    try {
      // Prepare the SQL query to insert a new delivery into the database.
      $query = $this->db->prepare("INSERT INTO deliveries 
      (order_id, address_id, to_user_address, delivery_address, status) VALUES 
      (:order_id, :address_id, :to_user_address, :delivery_address, :status)");

      // Bind parameters with their values.
      $parameters = [
        ":order_id" => $delivery->getOrderId(), 
        ":address_id" => $delivery->getAddressId(), 
        ":to_user_address" => $delivery->getToUserAddress(), 
        ":delivery_address"=> $delivery->getDeliveryAddress(), 
        ":status" => $delivery->getStatus() 
      ];

      // Execute the query with parameters.
      $query->execute($parameters);

      // Retrieve the last insert identifier.
      $deliveryId = $this->db->lastInsertId();

      // Set the identifier of the created delivery.
      $delivery->setId($deliveryId);

      // Return the created delivery.
      return $delivery;
    
    } catch(PDOException $e) {
      error_log("Failed to create a new delivery: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to create a new delivery");
    }
  }


  /**
   * Retrieves a delivery by its unique identifier
   * 
   * @param int $deliveryId The unique identifier of the delivery.
   * 
   * @return Delivery|null The retrieved delivery or null if not found.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function findDeliveryById(int $deliveryId): ?Delivery 
  {
    try {
      // Prepare the SQL query to retrieve the delivery by its unique identifier.
      $query = $this->db->prepare("SELECT * FROM deliveries WHERE id = :id");

      // Bind the parameter with its value.
      $parameter = [
        ":id" => $deliveryId
      ];

      // Execute the query with the parameter.
      $query->execute($parameter);

      // Fetch the delivery data from the database.
      $deliveryData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if a delivery is found
      if($deliveryData) {
        // INstantiate a new delivery with the retrieved data.
        $delivery = new Delivery(
          $deliveryData["id"],
          $deliveryData["order_id"],
          $deliveryData["address_id"],
          $deliveryData["to_user_address"],
          $deliveryData["delivery_address"],
          $deliveryData["status"]
        );
        return $delivery;
      }
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find the delivery: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find the delivery");
    }
  }


  /**
   * Retrieves a delivery by its order identifier
   * 
   * @param int $orderId The order identifier of the delivery.
   * 
   * @return Delivery|null The retrieved delivery or null if not found.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function findDeliveryByOrderId(int $orderId): ?Delivery 
  {
    try {
      // Prepare the SQL query to retrieve the delivery by its order identifier.
      $query = $this->db->prepare("SELECT deliveries.*, orders.* 
      FROM deliveries 
      JOIN orders ON order_id = orders.id 
      WHERE order_id = :order_id");

      // Bind the parameter with its value.
      $parameter = [
        "order_id" => $orderId
      ];

      // Execute the query with the parameter.
      $query->execute($parameter);

      // Fetch the delivery data from the database.
      $deliveryData = $query->fetch(PDO::FETCH_ASSOC);

      // Check if a delivery is found
      if($deliveryData) {
        // INstantiate a new delivery with the retrieved data.
        $delivery = new Delivery(
          $deliveryData["id"],
          $deliveryData["order_id"],
          $deliveryData["address_id"],
          $deliveryData["to_user_address"],
          $deliveryData["delivery_address"],
          $deliveryData["status"]
        );
        return $delivery;
      }
      return null; 

    } catch(PDOException $e) {
      error_log("Failed to find the delivery: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find the delivery");
    }
  }


  /**
   * Retrieves the delivery by its status
   * 
   * @param string $deliveryStatus The status of the delivery.
   * 
   * @return array |null The array of retrieved deliveries or null if no delivery is found.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function findDeliveriesByStatus(string $deliveryStatus): ?array 
  {
    try {
      // Prepare the SQL query to retrieve deliveries by their status.
      $query = $this->db->prepare("SELECT * FROM deliveries WHERE status = :status");

      // Bind the parameter with its value.
      $parameter = [
        ":status" => $deliveryStatus
      ];

      // Execute the query with the parameter.
      $query->execute($parameter);

      // Fetch the delivery data from the database.
      $deliveriesData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if deliveries are found.
      if($deliveriesData) {
        return $this->hydrateDeliveries($deliveriesData);
      }
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find deliveries: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find deliveries");
    }
  }


  /**
   * Retrieves all deliveries
   * 
   * @return array|null The array of retrieved deliveries or null if no delivery is found
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function findAll(): ?array 
  {
    try {
      // Prepare the SQL query to retrieve all deliveries into the database.
      $query = $this->db->prepare("SELECT * FROM deliveries");

      // Execute the query.
      $query->execute();

      // Fetch deliveries data from the database
      $deliveriesData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if deliveries data are found.
      if($deliveriesData) {
        return $this->hydrateDeliveries($deliveriesData);
      }
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find deliveries: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find deliveries");
    }
  }


  /**
   * Updates a delivrery
   * 
   * @param Delivery $delivery The delivery to be updated.
   * 
   * @return Delivery|null The updated delivery or null if not updated.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function updateDelivery(Delivery $delivery): ?Delivery 
  {
    try {
      // Prepare the SQL query to update the delivery.
      $query = $this->db->prepare("UPDATE deliveries SET 
      order_id = :order_id, 
      address_id = :address_id, 
      to_user_address = :to_user_address, 
      delivery_address = :delivery_address, 
      status = :status 
      WHERE id = :id");

      // Bind parameters with their values
      $parameters = [
        ":id" => $delivery->getId(),
        ":order_id" => $delivery->getOrderId(), 
        ":address_id" => $delivery->getAddressId(), 
        ":to_user_address" => $delivery->getToUserAddress(), 
        ":delivery_address" => $delivery->getDeliveryAddress(), 
        ":status" => $delivery->getStatus()  
      ];

      // Execute the query with parameters.
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return $delivery;
      }
      return null;

    } catch(PDOException $e) {
      error_log("Failed to update the delivery: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to update the delivery");
    }
  }


  /**
   * Deletes a delivery by its unique identifier
   * 
   * @param int $deliveryId The unique identifier of the delivery.
   * 
   * @return bool True if successfull, false if not.
   * 
   * @throws PDOException if an error occurs during the database operation
   */
  public function deleteDelivery(int $deliveryId): bool 
  {
    try {
      // Prepare the SQL query to delete the delivery by its unique identifier.
      $query = $this->db->prepare("DELETE FROM deliveries WHERE id = :id");

      // Bind the parameter with its value.
      $parameter = [
        ":id" => $deliveryId
      ];

      // Execute the query with the parameter.
      $success = $query ->execute($parameter);

      // Check if success
      if($success) {
        return true;
      }
      return false;

    } catch(PDOException $e) {
      error_log("Failed to delete the delivery: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to delete the delivery");
    }
  }


/**
 * Helper method to hydrate Delivery objects from data array.
 *
 * @param array $deliveriesData The array of delivery data retrieved from the database.
 * 
 * @return array An array of Delivery objects hydrated from the provided data.
 */
private function hydrateDeliveries(array $deliveriesData): array {
  // Initialize an empty array to store the hydrated Delivery objects.
  $deliveries = [];
  
  // Loop through each delivery data in the array.
  foreach($deliveriesData as $deliveryData) {
      // Create a new Delivery object using the data from the current iteration.
      $delivery = new Delivery(
        $deliveryData["id"],
        $deliveryData["order_id"],
        $deliveryData["address_id"],
        $deliveryData["to_user_address"],
        $deliveryData["delivery_address"],
        $deliveryData["status"]        
      );
      
      // Add the newly created Product object to the array.
      $deliveries[] = $delivery;
  }
  
  // Return the array of hydrated Product objects.
  return $deliveries;
}
  
}