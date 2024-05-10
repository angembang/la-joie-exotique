<?php

/**
 * Class Image
 * Defines an image in the platform
 */
class Image
{
  /**
   * @var int|null The unique identifier of the image. Null for a new image (not yet store into the database).
   */
  private ?int $id;

  /**
   * @var string The name of the image.
   */
  private string $name;

  /**
   * @var string The file name of the image.
   */
  private string $fileName;

  /**
   * @var string The alt of the image.
   */
  private string $alt;

  /**
   * Image constructor
   * @param int|null $id The unique identifier of the image. Null for a new image.
   * @param string $name The name of the image.
   * @param string $fileName The file name of the image.
   * @param string $alt The alt of the image.
   */
  public function __construct(?int $id, string $name, string $fileName, string $alt)
  {
    $this->id = $id;
    $this->name = $name;
    $this->fileName = $fileName;
    $this->alt = $alt;
  }


  /**
   * Get the unique identifier of the image.
   * 
   * @return int|null The unique identifier of the image. Null for a new image.
   */
  public function getId(): ?int 
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the image
   * @param int|null The unique identifier of the image.
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }


  /**
   * Get the name of the image.
   * 
   * @return string The name of the image. 
   */
  public function getName(): string 
  {
    return $this->name;
  }

  /**
   * Set the name of the image
   * @param string The name of the image.
   */
  public function setName(string $name): void 
  {
    $this->name = $name;
  }


  /**
   * Get the file name of the image.
   * 
   * @return string The file name of the image. 
   */
  public function getFileName(): string 
  {
    return $this->fileName;
  }

  /**
   * Set the file name of the image
   * @param string The file name of the image.
   */
  public function setFileName(string $fileName): void 
  {
    $this->fileName = $fileName;
  }


  /**
   * Get the alt of the image.
   * 
   * @return string The alt of the image. 
   */
  public function getAlt(): string 
  {
    return $this->alt;
  }

  /**
   * Set the alt of the image
   * @param string The altof the image.
   */
  public function setAlt(string $alt): void 
  {
    $this->alt = $alt;
  }
}