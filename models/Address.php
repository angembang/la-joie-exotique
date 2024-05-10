<?php

/**
 * Class Address 
 * Defines an address in the platform
 */
class Address 
{
  /**
   * @var int|null The unique identifier of the address. Null for a new address (not yet store into the database).
   */
  private ?int $id;

  /**
   * @var string The city of the address.
   */
  private string $city;

  /**
   * @var string The postal code of the address.
   */
  private string $postalCode;

  /**
   * @var string The street of the address.
   */
  private string $street;

  /**
   * @var string The number of the address.
   */
  private string $number;

  /**
   * @var string The digicode or appart name of the address.
   */
  private string $digicodeOrApptName;

  /**
   * Address constructor
   * @param int|null $id The unique identifier of the address. Null for a new address.
   * @param string $city The city of the address.
   * @param string $postalCode The postal code of the address. 
   * @param string $street The street of the address. 
   * @param string $number The number of the address. 
   * @param string $digicodeOrApptName The digicode or appart name of the address.
   */
  public function __construct(?int $id, string $city, string $postalCode, string $street, string $number, string $digicodeOrApptName)
  {
    $this->id = $id;
    $this->city = $city;
    $this->postalCode = $postalCode;
    $this->street = $street;
    $this->number = $number;
    $this->digicodeOrApptName = $digicodeOrApptName;
  }


  /**
   * Get the unique identifier of the address
   * 
   * @return int|null The unique identifier of the address. Null for a new address.
   */
  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the address
   * @param int|null The unique identifier of the address
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }


  /**
   * Get the city of the address
   * 
   * @return string The city of the address. 
   */
  public function getCity(): string
  {
    return $this->city;
  }

  /**
   * Set the city of the address
   * @param string The city of the address
   */
  public function setCity(string $city): void 
  {
    $this->city = $city;
  }


  /**
   * Get the postal code of the address
   * 
   * @return string The postal code of the address. 
   */
  public function getPostalCode(): string
  {
    return $this->postalCode;
  }

  /**
   * Set the postal code of the address
   * @param string The postal code of the address
   */
  public function setPostalCode(string $postalCode): void 
  {
    $this->postalCode = $postalCode;
  }


  /**
   * Get the street of the address
   * 
   * @return string The street of the address. 
   */
  public function getStreet(): string
  {
    return $this->street;
  }

  /**
   * Set the street of the address
   * @param string The street of the address
   */
  public function setStreet(string $street): void 
  {
    $this->street = $street;
  }


  /**
   * Get the number of the address
   * 
   * @return string The number of the address. 
   */
  public function getNumber(): string
  {
    return $this->number;
  }

  /**
   * Set the number of the address
   * @param string The number of the address
   */
  public function setNumber(string $number): void 
  {
    $this->number = $number;
  }


  /**
   * Get the digicode or appart name of the address
   * 
   * @return string The digicode or appart name of the address. 
   */
  public function getDigicodeOrApptName(): string
  {
    return $this->digicodeOrApptName;
  }

  /**
   * Set the digicode or appart name of the address
   * @param string The digicode or appart name of the address
   */
  public function seDigicodeOrApptName(string $digicodeOrApptName): void 
  {
    $this->digicodeOrApptName = $digicodeOrApptName;
  }
}