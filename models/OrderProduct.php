<?php

/**
 * Class OrderProduct
 * Defines an order product in the platform
 */
class OrderProduct
{
  /**
   * @var int The order identifier of the order product.
   */
  private int $orderId;

  /**
   * @var int The product identifier of the order product.
   */
  private int $productId;

  /**
   * @var int The quantity of the order product.
   */
  private int $quantity;

  /**
   * @var float The subtotal of the order product.
   */
  private float $subtotal;

  /**
   * Order product constructor
   * @param int $orderId The order identifier of the order product.
   * @param int $productId The product identifier of the order product.
   * @param int $quantity The quantity of the order product.
   * @param float $subtotal The subtotal of the order product.
   */
  public function __construct(int $orderId, int $productId, int $quantity, float $subtotal)
  {
    $this->orderId = $orderId;
    $this->productId = $productId;
    $this->quantity = $quantity;
    $this->subtotal = $subtotal;
  }

  
  /**
   * Get the order identifier of the order product
   * 
   * @return int The order identifier of the order product.
   */
  public function getOrderId(): int
  {
    return $this->orderId;
  }

  /**
   * Set the order identifier of the order product
   * @param int The order identifier of the order product.
   */
  public function setOrdeId(int $orderId): void 
  {
    $this->orderId = $orderId;
  }


  /**
   * Get the product identifier of the order product
   * 
   * @return int The product identifier of the order product.
   */
  public function getProductId(): int
  {
    return $this->productId;
  }

  /**
   * Set the product identifier of the order product
   * @param int The product identifier of the order product.
   */
  public function setProductId(int $productId): void 
  {
    $this->productId = $productId;
  }


  /**
   * Get the quantity of the order product
   * 
   * @return int The quantity of the order product.
   */
  public function getQuantity(): int
  {
    return $this->quantity;
  }

  /**
   * Set the quantity of the order product
   * @param int The quantity of the order product.
   */
  public function setQuantity(int $quantity): void 
  {
    $this->quantity = $quantity;
  }


  /**
   * Get the subtotal of the order product
   * 
   * @return float The subtotal of the order product.
   */
  public function getSubtotal(): float
  {
    return $this->subtotal;
  }

  /**
   * Set the subtotal of the order product
   * @param float The subtotal of the order product.
   */
  public function setSubtotal(float $subtotal): void 
  {
    $this->subtotal = $subtotal;
  }
}