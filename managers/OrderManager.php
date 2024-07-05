<?php

/**
 * Manages the retrieval and persistence of Order object in the platform
 */
class OrderManager extends AbstractManager 
{
  /**
   * Creates a new Order and persists it in the database
   * 
   * @param Order $order the order object to be created.
   * 
   * @return Order The created product with the asigned identifier.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function createOrder(Order $order): Order
  {
    try {
      // Prepare the SQL query to insert a new order into the database
      $query = $this->db->prepare("INSERT INTO orders (user_id, created_at, total_price, status, updated_at, guestName) VALUES 
      (:user_id, :created_at, :total_price, :status, :updated_at, :guestName)");

      // Bind parameters with their values
      $parameters = [
        ":user_id" => $order->getUserId(), 
        ":created_at" => $order->getCreatedAt()->format('Y-m-d H:i:s'), 
        ":total_price" => $order->getTotalPrice(), 
        ":status" => $order->getStatus(), 
        ":updated_at" => $order->getUpdatedAt(),
        ":guestName" => $order->getGuestName()
      ];

      // Execute the query with parameters
      $query->execute($parameters);

      // Retrieve the last insert Order
      $orderId = $this->db->lastInsertId();

      // set the identifier for the created Order
      $order->setId($orderId);

      // return the created order
      return $order;

    } catch(PDOException $e) {
      error_log("Failed to create a new order: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to create a new order");
    }
  }


  /**
   * Retrieves an order by its unique identifier
   * 
   * @param int $orderId The unique identifier of the order.
   * 
   * @return Order|null The retrieved order or null if not found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findOrderById(int $orderId): ?Order 
  {
    try {
      // Prepare the SQL query to retrieve the order by its unique identifier.
      $query = $this->db->prepare("SELECT * FROM orders WHERE id = :id");

      // Bind the parameter with its value.
      $parameter = [
        ":id" => $orderId
      ];

      // Execute the query with the parameter.
      $query->execute($parameter);

      // Fetch the order data from the database.
      $orderData = $query->fetch(PDO::FETCH_ASSOC);

      // Chech if order is found
      if($orderData) {
        // Instanciate a new order with retrieved data
        $order = new Order(
          $orderData["id"],
          $orderData["user_id"],
          $orderData["created_at"],
          $orderData["total_price"],
          $orderData["status"],
          $orderData["updated_at"],
          $orderData["guestName"]
        );
        return $order;
      }
      return null;

    } catch(PDOException $e) {
      error_log("Failed to find the orther: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find the orther");
    }
  }


  /**
   * Retrieves orders by their user identifier
   * 
   * @param int $userId The user identifier of the order.
   * 
   * @return array|null The array of retrieved orders or null if no order is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findOrdersByUserId(int $userId): ?array 
  {
    try {
      // Prepare the query to retrieve orders by the user identifier.
      $query = $this->db->prepare("SELECT orders.*, users.* 
      FROM orders
      JOIN users 
      ON user_id = users.id
      WHERE user_id = :user_id");

      // Bind the parametre with its value.
      $parameter = [
        ":user-id" =>  $userId
      ];

      // Execute the query with the parameter.
      $query->execute($parameter);

      // Fetch the order data from the database.
      $ordersData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if orders are found
      if($ordersData) {
        return $this->hydrateOrders($ordersData);
      }
      return null;  

    } catch(PDOException $e) {
      error_log("Failed to find orders: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find orders");
    }
  }


  /**
   * Retrieves orders by the created date
   * 
   * @param DateTime $createdAt The created date and time of the order
   * 
   * @return array|null The array of retrieved orders or null if no order is found.
   * 
   * @throws PDOException If an error occurs during the database operation.
   */
  public function findOrdersByCreatedDate(DateTime $createdAt): ?array 
  {
    try {
      // Prepare the query to retrieve orders by the created date.
      $query = $this->db->prepare("SELECT * FROM orders WHERE created_at = :created_at");

      // Bind the parametre with its value.
      $parameter = [
        ":created_at" => $createdAt
      ];

      // Execute the query with the parameter.
      $query->execute($parameter);

      // Fetch the order data from the database.
      $ordersData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if orders are found
      if($ordersData) {
        return $this->hydrateOrders($ordersData);
      }
      return null; 

    } catch(PDOException $e) {
      error_log("Failed to find orders: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find orders");
    }
  }


  /**
   * Retrieves orders by total price 
   * 
   * @param float $totalPrice The total price of the order.
   * 
   * @return array|null The array of retrieved orders or null if no order is found.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function findOrdersByTotalPrice(float $totalPrice): ?array 
  {
    try {
      // Prepare the query to retrieve orders by the total price.
      $query = $this->db->prepare("SELECT * FROM orders WHERE total_price = :total_price");

      // Bind the parametre with its value.
      $parameter = [
        ":total_price" => $totalPrice
      ];

      // Execute the query with the parameter.
      $query->execute($parameter);

      // Fetch the order data from the database.
      $ordersData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if orders are found
      if($ordersData) {
        return $this->hydrateOrders($ordersData);
      }
      return null; 

    } catch(PDOException $e) {
      error_log("Failed to find orders: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find orders");
    }
  }


  /**
   * Retrieves orders by the status
   * 
   * @param string $status The status of the order to retrieve.
   * 
   * @return array $orders|null The array of retrieved orders or null if no order is found.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function findOrdersByStatus(string $status): ?array 
  {
    try {
      // Prepare the query to retrieve orders by their status.
      $query = $this->db->prepare("SELECT * FROM orders WHERE status = :status");

      // Bind the parametre with its value.
      $parameter = [
        ":status" => $status
      ];

      // Execute the query with the parameter.
      $query->execute($parameter);

      // Fetch the order data from the database.
      $ordersData = $query->fetchAll(PDO::FETCH_ASSOC);

      // Check if orders are found
      if($ordersData) {
        return $this->hydrateOrders($ordersData);
      }
      return null; 

    } catch(PDOException $e) {
      error_log("Failed to find orders: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find orders");
    }
  }


  /**
   * Retrieves orders by the updated date
   * 
   * @param DateTime $updatedAt The updated date of the order.
   * 
   * @return array $order|null The array of retrieved orders or null i no order is found.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function findOrdersByUpdatedDate(DateTime $updatedAt): ?array 
  {
    try {
      // Prepare the query to retrieve orders by their updated date.
      $query = $this->db->prepare("SELECT * FROM orders WHERE updated_at = :updated_at");
   
      // Bind the parametre with its value.
      $parameter = [
        ":updated_at" => $updatedAt
      ];
   
      // Execute the query with the parameter.
      $query->execute($parameter);
   
      // Fetch orders data from the database.
      $ordersData = $query->fetchAll(PDO::FETCH_ASSOC);
   
      // Check if orders are found
      if($ordersData) {
        return $this->hydrateOrders($ordersData);
      }
      return null; 

    } catch(PDOException $e) {
      error_log("Failed to find orders: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find orders");
    }
  }


  /**
   * Retrieves all orders
   * 
   * @return array|null The array of retrieved orders or null if no order is found.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function findAll(): ?array 
  {
    try {
      // Prepare the query to retrieve all orders into the database;
      $query = $this->db->prepare("SELECT * FROM  orders");

      // Execute the query with the parameter.
      $query->execute();

      // Fetch orders data from the database.
      $ordersData = $query->fetchAll(PDO::FETCH_ASSOC);
   
      // Check if orders are found
      if($ordersData) {
        return $this->hydrateOrders($ordersData);
      }
      return null; 

    } catch(PDOException $e) {
      error_log("Failed to find orders: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to find orders");
    }
  }


  /**
   * Updates an order in the database
   * 
   * @param Order $order The order to be updated.
   * 
   * @return Order|null The updated order or null if not updated.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function updateOrder(Order $order): ?Order 
  {
    try {
      // Set updated_at to the current date and time.
      $now = new DateTime();

      // Prepare the SQL query to update an order in the database.
      $query = $this->db->prepare("UPDATE orders SET 
      user_id = :user_id,
      created_at = :created_at,
      total_price = :total_price, 
      status = :status,
      updated_at = :updated_at
      WHERE id = :id");

      // Bind parameters with their values.
      $parameters = [
        ":id" => $order->getId(),
        ":user_id" => $order->getUserId(),
        ":created_at" => $order->getCreatedAt(),
        ":total_price" => $order->getTotalPrice(),
        ":status" => $order->getStatus(),
        ":updated_at" => $now->format('Y-m-d H:i:s'),
        ":guestName" =>$order->getGuestName()
      ];

      // Execute the query with parameters.
      $success = $query->execute($parameters);

      // Check if success
      if($success) {
        return $order;
      }
      return null;

    } catch(PDOException $e) {
      error_log("Failed to update the order: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to update the order");
    }
  }


  /**
   * Deletes an order from the database
   * 
   * @param int $orderId The unique identifier of the order to be deleted.
   * 
   * @return bool True if succesfull, false if not.
   * 
   * @throws PDOException if an error occurs during the database operation.
   */
  public function deleteOrder(int $orderId): bool 
  {
    try {
      // Prepare SQL query to delete an order into the database
      $query = $this->db->prepare("DELETE FROM orders WHERE id = :id");

      // Bind the parameter with its value.
      $parameter = [
        ":id" => $orderId
      ];

      // Execute the query with the parameter.
      $success = $query->execute($parameter);

      // Check if successful
      if($success) {
        return true;
      }
      return false;

    }  catch(PDOException $e) {
      error_log("Failed to delete the order: " .$e->getMessage(). $e->getCode());
      throw new PDOException("Failed to delete the order");
    }
  }


/**
 * Helper method to hydrate Order objects from data array.
 *
 * @param array $ordersData The array of order data retrieved from the database.
 * 
 * @return array An array of Order objects hydrated from the provided data.
 */
private function hydrateOrders(array $ordersData): array {
  // Initialize an empty array to store the hydrated Order objects.
  $orders = [];
  
  // Loop through each order data in the array.
  foreach($ordersData as $orderData) {
      // Create a new Order object using the data from the current iteration.
      $order = new Order(
        $orderData["id"],
        $orderData["user_id"],
        $orderData["created_at"],
        $orderData["total_price"],
        $orderData["status"],
        $orderData["updated_at"],
        $orderData["guestName"]        
      );
      
      // Add the newly created Order object to the array.
      $orders[] = $order;
  }
  
  // Return the array of hydrated Order objects.
  return $orders;
}
}