<?php

/**
 * Class Order
 * Defines an order in the platform
 */
class Order 
{
  /**
   * @var int|null The unique identifier of the order. Null for a new order (not yet store in the database).
   */
  private ?int $id;

  /**
   * @var int The user identifier of the order.
   */
  private int $userId;

  /**
   * @var DateTime The created datetime of the order.
   */
  private DateTime $createdAt;

  /**
   * @var float The total price of the order.
   */
  private float $totalPrice;

  /**
   * @var string The status of the order.
   */
  private string $status;

  /**
   * @var DateTime|null The update datetime of the order. Null if not updated
   */
  private ?DateTime $updatedAt;

  /**
   * Order constructor
   * @param int|null $id The unique identifier of the order. Null for a new order.
   * @param int $userId The user identifier of the order.
   * @param DateTime $createdAt The created date of the order.
   * @param float $totalPrice The total price of the order.
   * @param string $status The status of the order.
   * @param DateTime $updatedAt The update datetime of the order.
   */
  public function __construct(?int $id, int $userId, DateTime $createdAt, float $totalPrice, string $status, ?DateTime $updatedAt)
  {
    $this->id = $id;
    $this->userId = $userId;
    $this->createdAt =  new DateTime(); // Set to current date and time automatically
    $this->totalPrice = $totalPrice;
    $this->status = $status;
    $this->updatedAt = $updatedAt;
  }


  /**
   * Get the unique identifier of the order
   * 
   * @return int|null The unique identifier of the order. Null for a new order.
   */
  public function getId(): ?int 
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the order.
   * @param int|null The unique identifier of the order.
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }


  /**
   * Get the user identifier of the order
   * 
   * @return int The user identifier of the order.
   */
  public function getUserId(): int 
  {
    return $this->userId;
  }

  /**
   * Set the user identifier of the order.
   * @param int The user identifier of the order.
   */
  public function setUserId(int $userId): void 
  {
    $this->userId = $userId;
  }

  /**
   * Get the created datetime of the order
   * 
   * @return DateTime The created datetime of the order. 
   */
  public function getCreatedAt(): DateTime 
  {
    return $this->createdAt;
  }

  /**
   * Set the created datetime of the order.
   * @param DateTime The created datetime of the order.
   */
  public function setCreatedAt(DateTime $createdAt): void 
  {
    $this->createdAt = $createdAt;
  }


  /**
   * Get the total price of the order
   * 
   * @return float The total price of the order. 
   */
  public function getTotalPrice(): float
  {
    return $this->totalPrice;
  }

  /**
   * Set the total price of the order.
   * @param float The total price of the order.
   */
  public function setTotalPrice(float $totalPrice): void 
  {
    $this->totalPrice = $totalPrice;
  }


  /**
   * Get the status of the order
   * 
   * @return string The status of the order. 
   */
  public function getStatus(): string
  {
    return $this->status;
  }

  /**
   * Set the status of the order.
   * @param string The total price of the order.
   */
  public function setStatus(string $status): void 
  {
    $this->status= $status;
  }
  
  
  /**
   * Get the updated datetime of the order
   * 
   * @return DateTime The updated datetime of the order. 
   */
  public function getUpdatedAt(): ?DateTime 
  {
    return $this->updatedAt;
  }

  /**
   * Set the updated datetime of the order.
   * @param DateTime The updated datetime of the order.
   */
  public function setUpdatedAt(?DateTime $updatedAt): void 
  {
    $this->updatedAt = $updatedAt;
  }
}