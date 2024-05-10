<?php

/**
 * Class Payment
 * Defines a payment in the platform
 */
class Payment 
{
  /**
   * @var int|null The unique identifier of the payment. Null for a new payment (not yet store in the database).
   */
  private ?int $id;

  /**
   * @var int The order identifier of the payment.
   */
  private int $orderId;

  /**
   * @var string The payment method of the payment.
   */
  private string $paymentMethod;

  /**
   * @var DateTime The payment date of the payment.
   */
  private DateTime $paymentDate;

  /**
   * @var float The amount of the payment.
   */
  private float $amount;

  /**
   * @var string The status of the payment.
   */
  private string $status;

  /**
   * Payment constructor
   * @param int|null $id The unique identifier of the payment. Null for a new payment.
   * @param int $orderId The order identifier of the payment.
   * @param string $paymentmethod The payment method of the payment.
   * @param DateTime $paymentDate The payment date of the payment.
   * @param float $amount The amount of the payment.
   * @param string $status The status of the payment.
   */
  public function __construct(?int $id, int $orderId, string $paymentMethod, DateTime $paymentDate, float $amount, string $status) 
  {
    $this->id = $id;
    $this->orderId = $orderId;
    $this->paymentMethod = $paymentMethod;
    $this->paymentDate = $paymentDate;
    $this->amount = $amount;
    $this->status = $status;
  }


  /**
   * Get the unique identifier of the payment
   * 
   * @return int|null The unique identifier of the payment. Null for a new payment.
   */
  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * Set the unique identifier of the payment
   * @param int|null The unique identifier of the payment
   */
  public function setId(?int $id): void 
  {
    $this->id = $id;
  }


  /**
   * Get the order identifier of the payment
   * 
   * @return int The order identifier of the payment. 
   */
  public function getOrderId(): int
  {
    return $this->orderId;
  }

  /**
   * Set the order identifier of the payment
   * @param int The order identifier of the payment
   */
  public function setOrderId(int $orderId): void 
  {
    $this->orderId = $orderId;
  }


  /**
   * Get the payment method of the payment
   * 
   * @return string The payment method of the payment.
   */
  public function getPaymentMethod(): string
  {
    return $this->paymentMethod;
  }

  /**
   * Set the payment method of the payment
   * @param string The payment method of the payment
   */
  public function setPaymentMethod(string $paymentMethod): void 
  {
    $this->paymentMethod = $paymentMethod;
  }


  /**
   * Get the payment date of the payment
   * 
   * @return DateTime The payment date of the payment. 
   */
  public function getPaymentDate(): DateTime
  {
    return $this->paymentDate;
  }

  /**
   * Set the payment date of the payment
   * @param DateTime The payment date of the payment
   */
  public function setPaymentDate(DateTime $paymentDate): void 
  {
    $this->paymentDate = $paymentDate;
  }


  /**
   * Get the amount of the payment
   * 
   * @return float The amount of the payment. Null for a new payment.
   */
  public function getAmount(): float
  {
    return $this->amount;
  }

  /**
   * Set the amount of the payment
   * @param float The amount of the payment
   */
  public function setAmount(float $amount): void 
  {
    $this->amount = $amount;
  }


  /**
   * Get the status of the payment
   * 
   * @return string The status of the payment.
   */
  public function getStatus(): string
  {
    return $this->status;
  }

  /**
   * Set the status of the payment
   * @param string The status of the payment
   */
  public function setStatus(string $status): void 
  {
    $this->status = $status;
  }

}