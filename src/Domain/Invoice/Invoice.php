<?php

namespace Warehouse\Domain\Invoice;

use Ramsey\Uuid\Uuid;
use Warehouse\Domain\Customer\ObjectValues\Address;
use Warehouse\Domain\Entity;
use Warehouse\Domain\Invoice\Calculator\TotalPriceCalculatorInterface;
use Warehouse\Domain\Invoice\ObjectValues\Status;
use Warehouse\Domain\ObjectValues\Id;
use Warehouse\Domain\ObjectValues\Money;
use Warehouse\Domain\Order\Order;
use Warehouse\Domain\Product\Product;

/**
 * Class Invoice
 * @package Warehouse\Domain
 */
class Invoice implements Entity
{
    /**
     * @var Id
     */
    private $id;
    /**
     * @var Order
     */
    private $order;
    /**
     * @var array
     */
    private $products;
    /**
     * @var Status
     */
    private $status;
    /**
     * @var \DateTime
     */
    private $createdAt;
    /**
     * @var \DateTime
     */
    private $shippedAt;
    /**
     * @var TotalPriceCalculatorInterface
     */
    private $calculator;

    /**
     * Invoice constructor.
     * @param \Warehouse\Domain\ObjectValues\Id $id
     * @param Order $order
     * @param Product[] $products
     * @param Status $status
     * @param \DateTime $createdAt
     * @param \DateTime $shippedAt
     */
    public function __construct(
        Id $id,
        Order $order,
        array $products,
        Status $status,
        \DateTime $createdAt,
        \DateTime $shippedAt = null
    ) {
        $this->id = $id;
        $this->order = $order;
        $this->products = $products;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->shippedAt = $shippedAt;
    }

    /**
     * @param Order $order
     * @param Product[] $products
     * @return Invoice
     * @throws \InvalidArgumentException
     */
    public static function create(
        Order $order,
        array $products
    ): Invoice {
        return new self(
            new Id(Uuid::uuid4()),
            $order,
            $products,
            new Status(Status::STATUS_OPENED),
            new \DateTime(),
            null
        );
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getShippedAt(): \DateTime
    {
        return $this->shippedAt;
    }

    /**
     * @param \DateTime $shippedAt
     */
    public function setShippedAt(\DateTime $shippedAt): void
    {
        $this->shippedAt = $shippedAt;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * @param Status $status
     */
    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    /**
     * @return Address
     */
    public function getCustomerAddress(): Address
    {
        return $this->order->getCustomer()->getAddress();
    }

    /**
     * @return Id
     */
    public function getOrderId(): Id
    {
        return $this->order->getID();
    }

    /**
     * @return Id
     */
    public function getID(): Id
    {
        return $this->id;
    }

    /**
     * @return Money
     * @throws \BadMethodCallException
     */
    public function getTotalPrice(): Money
    {
        if (null === $this->calculator) {
            throw new \BadMethodCallException('Total calculator didn\'t set');
        }
        return Money::USD($this->calculator->calculate($this->getProducts()));
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return count($this->getProducts());
    }

    /**
     * @param \Warehouse\Domain\Invoice\Calculator\TotalPriceCalculatorInterface $calculator
     */
    public function setCalculator(TotalPriceCalculatorInterface $calculator): void
    {
        $this->calculator = $calculator;
    }
}