<?php

namespace Warehouse\Domain\Order;

use Warehouse\Domain\Contract\Entity;
use Warehouse\Domain\Customer\Customer;
use Warehouse\Domain\Id;
use Warehouse\Domain\Order\ObjectValues\Status;
use Warehouse\Domain\Product\Product;

/**
 * Class Order
 * @package Warehouse\Domain
 */
class Order implements Entity
{
    /**
     * @var Id
     */
    private $id;
    /**
     * @var Customer
     */
    private $customer;
    /**
     * @var \DateTime
     */
    private $createdAt;
    /**
     * @var \DateTime
     */
    private $updatedAt;
    /**
     * @var Status
     */
    private $status;
    /**
     * @var Product[]
     */
    private $products;

    /**
     * Order constructor.
     * @param Id $id
     * @param Customer $customer
     * @param Product[] $products
     * @param \DateTime $createdAt
     * @param \DateTime $updatedAt
     * @param Status $status
     */
    public function __construct(
        Id $id,
        Customer $customer,
        array $products,
        \DateTime $createdAt,
        \DateTime $updatedAt,
        Status $status
    ) {
        $this->id = $id;
        $this->customer = $customer;
        $this->products = $products;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->status = $status;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
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
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return Id
     */
    public function getID(): Id
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param Product $product
     * @internal param Product $id
     * @throws \DomainException
     */
    public function addProduct(Product $product): void
    {
        if ($this->getStatus()->isClosed()) {
            throw new \DomainException('Can\'t change order, it was closed');
        }

        $this->products[] = $product;
        $this->updatedAt = new \DateTime();
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
     * @throws \DomainException
     */
    public function setStatus(Status $status): void
    {
        if ($this->status->isClosed() && !$status->isClosed()) {
            throw new \DomainException('Order was closed');
        }

        $this->status = $status;
    }

    /**
     * @param Product $product
     */
    public function removeProduct(Product $product): void
    {
        foreach ($this->products as $key => $item) {
            if ($item->getID() == $product->getID()) {
                unset($this->products[$key]);
            }
        }
    }
}