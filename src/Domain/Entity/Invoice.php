<?php

namespace Warehouse\Domain\Entity;

use Ramsey\Uuid\Uuid;
use Warehouse\Domain\Address;
use Warehouse\Domain\Calculator\TotalPriceCalculatorInterface;
use Warehouse\Domain\Collection\ProductsCollection;
use Warehouse\Domain\Contract\Entity;
use Warehouse\Domain\Id;

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
     * @param ProductsCollection $products
     * @param int $status
     * @param \DateTime $createdAt
     * @param \DateTime $shippedAt
     * @param \Warehouse\Domain\Calculator\TotalPriceCalculatorInterface $calculator
     * @throws \InvalidArgumentException
     */
    public function __construct(
        Id $id,
        Order $order,
        ProductsCollection $products,
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
     * @param ProductsCollection $products
     * @return Invoice
     * @throws \InvalidArgumentException
     */
    public static function create(
        Order $order,
        ProductsCollection $products
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
     * @return int
     * @throws \BadMethodCallException
     */
    public function getTotalPrice(): int
    {
        if(null === $this->calculator) {
            throw new \BadMethodCallException('Total calculator didn\'t set');
        }
        return $this->calculator->calculate($this->getProducts());
    }

    /**
     * @return ProductsCollection
     */
    public function getProducts(): ProductsCollection
    {
        return $this->products;
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->getProducts()->totalCount();
    }

    /**
     * @param TotalPriceCalculatorInterface $calculator
     */
    public function setCalculator(TotalPriceCalculatorInterface $calculator): void
    {
        $this->calculator = $calculator;
    }
}