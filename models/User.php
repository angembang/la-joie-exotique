<?php

/** 
 * Class User
 * Defines an user in the plateform.
 */
class User 
{
  /**
   * @var int|null the unique identifier of the user. Null for a new user (not yet store in the database) 
   */ 
  private ?int $id;
  
  /**
   * @var string the email or phone number of the user. 
   */ 
  private string $emailOrPhone;
  
  /**
   * @var string the password of the user. 
   */
  private string $password;
  
  /**
   * @var string the role of the user. 
   */
   private string $role;
  
   /**
   * @var int the address ID of the user. 
   */
  private int $addressId;

  /**
   * User constructor
   * @param int|null $id The unique identifier of the user. Null for a new user.
   * @param string $emailOrPhone The email or phone number of the user.
   * @param string $password The password of the user.
   * @param string $role The role of the user.
   * @param int $addressId The address ID of the user.
   */
  public function __construct(?int $id, string $emailOrPhone, string $password, string $role, int $addressId)
  {
    $this->id = $id;
    $this->emailOrPhone = $emailOrPhone;
    $this->password = $password;
    $this->role = $role;
    $this->addressId = $addressId;
  }

  
  /**
   * Get the unique identifier of the user
   * 
   * @return int|null The unique identifier of the user. Null for a new user.
   */
  public function getId(): ?int 
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the user.
   * @param int|null The unique identifier of the user. 
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }


  /**
   * Get the email or phone of the user
   * 
   * @return string The email or phone number of the user. 
   */
  public function getEmailOrPhone(): string 
  {
    return $this->emailOrPhone;
  }

  /**
   * Set the email or phone of the user.
   * @param string The email or phone of the user. 
   */
  public function setEmailOrPhone(string $emailOrPhone): void 
  {
    $this->emailOrPhone = $emailOrPhone;
  }
  
  
  /**
   * Get the password of the user
   * 
   * @return string The password of the user. 
   */
  public function getPassword(): string 
  {
    return $this->password;
  }

  /**
   * Set the password of the user.
   * @param string The password of the user. 
   */
  public function setPassword(string $password): void 
  {
    $this->password = $password;
  }


  /**
   * Get the role of the user
   * 
   * @return string The role of the user. 
   */
  public function getRole(): string 
  {
    return $this->role;
  }

  /**
   * Set the role of the user.
   * @param string The role of the user. 
   */
  public function setRole(string $role): void 
  {
    $this->role = $role;
  }


  /**
   * Get the address identifier of the user
   * 
   * @return int The address identifier of the user. 
   */
  public function getAddressId(): int 
  {
    return $this->addressId;
  }

  /**
   * Set the address identifier of the user.
   * @param int The address identifier of the user. 
   */
  public function setAddressId(int $addressId): void 
  {
    $this->addressId = $addressId;
  }

}