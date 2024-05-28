<?php

/**
 * Class Product 
 * Defines a product in the platform
 */
class Product 
{
  /**
   * @var int|null The unique identifier of the product. Null for a new product (not yet store in the database).
   */
  private ?int $id;

  /**
   * @var string The name of the product.
   */
  private string $name;

  /**
   * @var string The description of the product.
   */
  private string $description;

  /**
   * @var float The price of the product.
   */
  private float $price;

  /**
   * @var int The tag identifier of the product.
   */
  private int $tagId;

  /**
   * @var int  The category identifier of the product. 
   */
  private int $categoryId;

  /**
   * Product constructor
   * @param int|null $id The unique identifier of the product.
   * @param string $name The name of the product.
   * @param string $description The description of the product.
   * @param float $price The price of the product.
   * @param int $tagId The tag identifier of the product;
   * @param int $categoryId The category identifier of the product
   */
  public function __construct(?int $id, string $name, string $description, float $price, int $tagId, int $categoryId) 
  {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->price = $price;
    $this->tagId = $tagId;
    $this->categoryId = $categoryId;
  }


  /**
   * Get the unique identifier of the product
   * 
   * @return int|null The unique identifier of the product. Null for a new product.
   */
  public function getId(): ?int 
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the product
   * @param int|null The unique identifier of the product.
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }


  /**
   * Get the name of the product
   * 
   * @return string The name of the product.
   */
  public function getName(): string 
  {
    return $this->name;
  }

  /**
   * Set the name of the product
   * @param string The name of the product.
   */
  public function setName(string $name): void 
  {
    $this->name = $name;
  }


  /**
   * Get the description of the product
   * 
   * @return string The description of the product.
   */
  public function getDescription(): string 
  {
    return $this->description;
  }

  /**
   * Set the description of the product
   * @param string The description of the product.
   */
  public function setDescription(string $description): void 
  {
    $this->description = $description;
  }


  /**
   * Get the price of the product
   * 
   * @return float The price of the product.
   */
  public function getPrice(): float 
  {
    return $this->price;
  }

  /**
   * Set the price of the product
   * @param float The price of the product.
   */
  public function setPrice(float $price): void 
  {
    $this->price = $price;
  }
  
  
  /**
   * Get the tag identifier of the product
   * 
   * @return int The tag identifier of the product.
   */
  public function getTagId(): int 
  {
    return $this->tagId;
  }

  /**
   * Set the tag identifier of the product
   * @param int The tag identifier of the product.
   */
  public function setTagId(int $tagId): void 
  {
    $this->tagId = $tagId;
  }


  /**
   * Get the category identifier of the product
   * 
   * @return int The category identifier of the product.
   */
  public function getCategoryId(): int 
  {
    return $this->categoryId;
  }

  /**
   * Set the category identifier of the product
   * @param int The category identifier of the product.
   */
  public function setCategoryId(int $categoryId): void 
  {
    $this->categoryId = $categoryId;
  }

}