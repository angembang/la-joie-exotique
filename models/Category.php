<?php

/**
 * Defines a category in the platform
 */
class Category 
{
  /**
   * @var int|null The unique identifier of the category. null for a new category (not yet store in the database).
   */
  private ?int $id;

  /**
   * @var string The name of the category.
   */
  private string $name;

  /** 
   * @var string The description of the category.
  */
  private string $description;

  /**
   * Category constructor
   * @param int|null $id The identifier of the category.
   * @param string $name The name of the category.
   * @param string $description The description of the category.
   */
  public function __construct(?int $id, string $name, string $description)
  {
    $this->id = $id;
    $this->name = $name;
    $this->description = $description;
  }


  /**
   * Get the unique identifier of the category
   * 
   * @return int|null The unique identifier of the category. Null for a new category.
   */
  public function getId(): ?int 
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the category
   * @param int|null The unique identifier of the category.
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }


  /**
   * Get the name of the category
   * 
   * @return string The name of the category. 
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * Set the name of the category
   * @param string The name of the category.
   */
  public function setName(string $name): void 
  {
    $this->name = $name;
  }


  /**
   * Get the description of the category
   * 
   * @return string The description of the category. 
   */
  public function getDescription(): string
  {
    return $this->description;
  }

  /**
   * Set the description of the category
   * @param string The description of the category.
   */
  public function setDescription(string $description): void 
  {
    $this->description = $description;
  }
}