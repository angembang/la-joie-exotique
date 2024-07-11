<?php

/**
 * Class Delivery
 * Defines a delivrery in the platform
 */
class Delivery
{
  /**
   * @var int|null The unique identifier of the delivrery. Null for a new delivrery (not yet store into the database).
   */
  private ?int $id;

  /**
   * @var int The order identifier of the delivrery.
   */
  private int $orderId;

  /**
   * @var bool The if to user address of the delivrery.
   */
  private bool $toUserAddress;

  /**
   * @var int|null The address identifier of the delivrery. Null for the guest.
   */
  private ?int $addressId;

  /**
   * @var string|null The delivrery address of the the delivrery. Null if deliver to the user address.
   */
  private ?string $deliveryAddress;

  /**
   * @var string The status of the delivrery.
   */
  private string $status;

  /**
   * Delivrery constructor
   * @param int|null $id The unique identifier of the delivrery. Null for the new delivrery.
   * @param int $orderId The order identifier of the delivrery.
   * @param bool $toUserAddress The if to user address of the delivrery.
   * @param ?int $addressId The address identifier of the delivrery. Null for the guest. 
   * @param ?string $deliveryAddress The delivrery address of the the delivrery. Null if deliver to the user address. 
   * @param string $status The status of the delivrery.
   */
  public function __construct(?int $id, int $orderId, bool $toUserAddress, ?int $addressId, ?string $deliveryAddress, string $status)
  {
    $this->id = $id;
    $this->orderId = $orderId;
    $this->toUserAddress = $toUserAddress;
    $this->addressId = $addressId;
    $this->deliveryAddress = $deliveryAddress;
    $this->status = $status;
  }


  /**
   * Get the unique identifier of the delivrery
   * 
   * @return int|null The unique identifier of the delivrery. Null for a new delivrery.
   */
  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the delivrery
   * @param int|null The unique identifier of the delivrery.
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }


  /**
   * Get the order identifier of the delivrery
   * 
   * @return int The order identifier of the delivrery.
   */
  public function getOrderId(): int
  {
    return $this->orderId;
  }

  /**
   * Set the order identifier of the delivrery
   * @param int The order identifier of the delivrery.
   */
  public function setOrderId(int $orderId): void 
  {
    $this->orderId = $orderId;
  }


  /**
   * Get the if to user address of the delivrery
   * 
   * @return bool The if to user address of the delivrery.
   */
  public function getToUserAddress(): bool
  {
    return $this->toUserAddress;
  }

  /**
   * Set the if to user address of the delivrery
   * @param bool The if to user address of the delivrery.
   */
  public function setToUserAddress(bool $toUserAddress): void 
  {
    $this->toUserAddress = $toUserAddress;
  }


  /**
   * Get the address identifier of the delivrery
   * 
   * @return int The address identifier of the delivrery.
   */
  public function getAddressId(): ?int
  {
    return $this->addressId;
  }

  /**
   * Set the address identifier of the delivrery
   * @param int The address identifier of the delivrery.
   */
  public function setAddressId(int $addressId): void 
  {
    $this->addressId = $addressId;
  }


  /**
   * Get the delivrery address of the delivrery
   * 
   * @return ?string The delivrery address of the delivrery. Null if deliver to the user address.
   */
  public function getDeliveryAddress(): ?string
  {
    return $this->deliveryAddress;
  }

  /**
   * Set the delivrery address of the delivrery
   * @param ?string The delivrery address of the delivrery.
   */
  public function setDeliveryAddress(?string $deliveryAddress): void 
  {
    $this->deliveryAddress = $deliveryAddress;
  }


  /**
   * Get the status of the delivrery
   * 
   * @return string The status of the delivrery. 
   */
  public function getStatus(): string
  {
    return $this->status;
  }

  /**
   * Set the status of the delivrery
   * @param string The status of the delivrery.
   */
  public function setStatus(string $status): void 
  {
    $this->status = $status;
  }
}