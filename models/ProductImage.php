<?php

/**
 * Class ProductImage
 * Defines a product image in the platform
 */
class ProductImage 
{
  /**
   * @var int|null The unique identifier of the product image. Null for a new product image (not yet store in the database).
   */
  private ?int $id;

  /**
   * @var int The product identifier of the product image.
   */
  private int $productId;

  /**
   * @var int The image identifier of the product image.
   */
  private int $imageId;

  /**
   * Product image constructor
   * @param int|null $id The unique identifier of the product image.
   * @param int $productId The product identifier of the product image.
   * @param int $image The image identifier of the product image.
   */
  public function __construct(?int $id, int $productId, int $imageId)
  {
    $this->id = $id;
    $this->productId = $productId;
    $this->imageId = $imageId;
  }


  /**
   * Get the unique identifier of the product image
   * 
   * @return int|null The unique identifier of the product image. Null for a new product image
   */
  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * Set the uniqque identifier of the product image
   * @param int|null $id The unique identifier of the product image
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }


  /**
   * Get the product identifier of the product image
   * 
   * @return int The product identifier of the product image. 
   */
  public function getProductId(): int
  {
    return $this->productId;
  }

  /**
   * Set the product identifier of the product image
   * @param int The product identifier of the product image
   */
  public function setProductId(int $productId): void 
  {
    $this->productId = $productId;
  }


  /**
   * Get the image identifier of the product image
   * 
   * @return int The image identifier of the product image. 
   */
  public function getImageId(): int
  {
    return $this->imageId;
  }

  /**
   * Set the image identifier of the product image
   * @param int The image identifier of the product image
   */
  public function setImageId(int $imageId): void 
  {
    $this->imageId = $imageId;
  }
}