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
   * @var int  The image1 identifier of the product. 
   */
  private int $image1Id;

  /**
   * @var int|null  The image2 identifier of the product. Null if no image is set
   */
  private ?int $image2Id;

  /**
   * @var int|null  The image3 identifier of the product. Null if no image is set
   */
  private ?int $image4Id;

  /**
   * @var int|null  The image4 identifier of the product. Null if no image is set
   */
  private ?int $image3Id;

  /**
   * @var array The Array to store images associated with the product. 
   */
  private $images = [];



  /**
   * Product constructor
   * @param int|null $id The unique identifier of the product.
   * @param string $name The name of the product.
   * @param string $description The description of the product.
   * @param float $price The price of the product.
   * @param int $tagId The tag identifier of the product;
   * @param int $categoryId The category identifier of the product
   * @param int $image1Id The image1 identifier of the product
   * @param int|null $image2Id The image2 identifier of the product
   * @param int|null $image3Id The image3 identifier of the product
   * @param int|null $image4Id The image4 identifier of the product
   */
  public function __construct(?int $id, string $name, string $description, float $price, int $tagId, int $categoryId, int $image1Id, ?int $image2Id, ?int $image3Id, ?int $image4Id) 
  {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
    $this->price = $price;
    $this->tagId = $tagId;
    $this->categoryId = $categoryId;
    $this->image1Id = $image1Id;
    $this->image2Id = $image2Id;
    $this->image3Id = $image3Id;
    $this->image4Id = $image4Id;
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


   /**
   * Get the image1 identifier of the product
   * 
   * @return int The image1 identifier of the product.
   */
  public function getImage1Id(): int 
  {
    return $this->image1Id;
  }

  /**
   * Set the image1 identifier of the product
   * @param int The image1 identifier of the product.
   */
  public function setImage1Id(int $image1Id): void 
  {
    $this->image1Id = $image1Id;
  }


   /**
   * Get the image2 identifier of the product
   * 
   * @return int|null The image2 identifier of the product, or null if no image2 is set.
   */
  public function getImage2Id(): ?int 
  {
    return $this->image2Id;
  }

  /**
   * Set the image2 identifier of the product
   * @param int|null The image2 identifier of the product.
   */
  public function setImage2Id(?int $image2Id): void 
  {
    $this->image2Id = $image2Id;
  }


  /**
   * Get the image3 identifier of the product
   * 
   * @return int|null The image3 identifier of the product, or null if no image3 is set.
   */
  public function getImage3Id(): ?int 
  {
    return $this->image3Id;
  }

  /**
   * Set the image3 identifier of the product
   * @param int|null The image3 identifier of the product.
   */
  public function setImage3Id(?int $image3Id): void 
  {
    $this->image3Id = $image3Id;
  }


  /**
   * Get the image4 identifier of the product
   * 
   * @return int|null The image4 identifier of the product, or null if no image4 is set.
   */
  public function getImage4Id(): ?int 
  {
    return $this->image4Id;
  }

  /**
   * Set the image4 identifier of the product
   * @param int|null The image4 identifier of the product.
   */
  public function setImage4Id(?int $image4Id): void 
  {
    $this->image4Id = $image4Id;
  }


  /**
   * Magic method to dynamically retrieve the value of a property.
   *
   * @param string $name The name of the property being accessed.
   * 
   * @return mixed|null The value of the property if it exists, null otherwise.
   */
  public function __get($name) {
    if ($name === 'images') {
      return $this->images;
    }
  }

  /**
   * Magic method to dynamically set the value of a property.
   *
   * @param string $name The name of the property being set.
   * @param mixed $value The value to set for the property.
   * 
   * @return void
   */
  public function __set($name, $value) {
    if ($name === 'images') {
      $this->images = $value;
    }
  }
}