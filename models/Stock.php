<?php

/**
 * class Stock
 * Defines a stock in the platform
 */
class Stock 
{
  /**
   * @var int|null The product identifier of the stock, null for a new stock.
   */
  private ?int $id;
  
  /**
   * @var int The product identifier of the stock.
   */
  private int $productId;

  /**
   * @var int The quantity of the stock.
   */
  private int $quantity;

  /**
   * Stock constructor
   * @param int|null $id The unique identifier of the stock.
   * @param int $productId The product Identifier of the stock.
   * @param int $quantity The quantity of the stock.
   */
  public function __construct(?int $id, int $productId, int $quantity) 
  {
    $this->productId = $productId;
    $this->quantity = $quantity;
  }

  
  /**
   * Get the product identifier of the stock
   * 
   * @return int The product identifier of the stock.
   */
  public function getProductId(): int
  {
    return $this->productId;
  }

  /**
   * Set the product identifier of the stock
   * @param int The product identifier of the stock
   */
  public function setProductId(int $productId): void 
  {
    $this->productId = $productId;
  }


  /**
   * Get the quantity of the stock
   * 
   * @return int The quantity of the stock.
   */
  public function getQuantity(): int
  {
    return $this->quantity;
  }

  /**
   * Set the quantity of the stock
   * @param int The quantity of the stock
   */
  public function setQuantity(int $quantity): void 
  {
    $this->quantity = $quantity;
  }
}