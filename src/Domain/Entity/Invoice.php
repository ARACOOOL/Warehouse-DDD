<?php

namespace Warehouse\Domain\Entity;

use Ramsey\Uuid\Uuid;
use Warehouse\Domain\Address;
use Warehouse\Domain\Calculator\TotalPriceCalculatorInterface;
use Warehouse\Domain\Contract\Entity;
use Warehouse\Domain\Id;
use Warehouse\Domain\Money;
use Warehouse\Domain\Product\Product;

/**
 * Class Invoice
 * @package Warehouse\Domain
 */
class Invoice implements Entity
{
    const STATUS_SHIPPED = 2;
    const STATUS_OPENED = 1;
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
     * @var int
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
     * @param Id $id
     * @param Order $order
     * @param Product[] $products
     * @param int $status
     * @param \DateTime $createdAt
     * @param \DateTime $shippedAt
     * @internal param TotalPriceCalculatorInterface $calculator
     * @throws \InvalidArgumentException
     */
    public function __construct(
        Id $id,
        Order $order,
        array $products,
        int $status,
        \DateTime $createdAt,
        \DateTime $shippedAt = null
    ) {
        $this->id = $id;
        $this->order = $order;
        $this->products = $products;

        if ($status != self::STATUS_OPENED && $status != self::STATUS_SHIPPED) {
            throw new \InvalidArgumentException('Invalid status of invoice');
        }
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
            self::STATUS_OPENED,
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
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
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
     * @param TotalPriceCalculatorInterface $calculator
     */
    public function setCalculator(TotalPriceCalculatorInterface $calculator): void
    {
        $this->calculator = $calculator;
    }
}