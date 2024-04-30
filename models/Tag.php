<?php

/**
 * Defines a tag in the patform
 */
class Tag 
{
  /**
   * @var int|null The unique identifier of the tag. Null for a new tag (not yet store in the database).
   */
  private ?int $id;

  /**
   * @var int The category ID of the tag
   */
  private int $categoryId;

  /**
   * @var string The name of the tag
   */
  private string $name;

  /**
   * @var string The description of the tag
   */
  private string $description;

  /**
   * Tag constructor
   * @param int|null $id The unique identifier of the tag.
   * @param int $categoryId The category identifier of the tag.
   * @param string $name The name of the tag.
   * @param string $description The description of the tag.
   */
  public function __construct(?int $id, int $categoryId, string $name, string $description)
  {
    $this->id = $id;
    $this->categoryId = $categoryId;
    $this->name = $name;
    $this->description = $description;
  }


  /**
   * Get the unique identifier of the tag
   * 
   * @return int|null The unique identifier of the tag. Null for a new tag.
   */
  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the tag.
   * @param int|null the unique identifier of the tag
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }


  /**
   * Get the category identifier of the tag
   * 
   * @return int The category identifier of the tag.
   */
  public function getCategoryId(): int
  {
    return $this->categoryId;
  }

  /**
   * Set the category identifier of the tag.
   * @param int the category identifier of the tag
   */
  public function setCategoryId(int $categoryId): void 
  {
    $this->categoryId = $categoryId;
  }


  /**
   * Get the name of the tag
   * 
   * @return string The name of the tag. 
   */
  public function getName(): string
  {
    return $this->name;
  }

  /**
   * Set the name of the tag.
   * @param string the name of the tag
   */
  public function setName(string $name): void 
  {
    $this->name = $name;
  }


  /**
   * Get the description of the tag
   * 
   * @return string The description of the tag. 
   */
  public function getDescription(): string
  {
    return $this->description;
  }

  /**
   * Set the description of the tag.
   * @param string the description of the tag
   */
  public function setDescription(string $description): void 
  {
    $this->description = $description;
  }
}