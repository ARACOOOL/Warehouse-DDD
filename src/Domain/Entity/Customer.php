<?php

namespace Warehouse\Domain\Entity;

use Ramsey\Uuid\Uuid;
use Warehouse\Domain\Address;
use Warehouse\Domain\Collection\ProductsCollection;
use Warehouse\Domain\Contract\Entity;
use Warehouse\Domain\Id;
use Warehouse\Domain\Status;

/**
 * Class Customer
 * @package Warehouse\Domain\Entity
 */
class Customer implements Entity
{
    /**
     * @var Id
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var Address
     */
    private $address;

    /**
     * Customer constructor.
     * @param Id $id
     * @param string $name
     * @param Address $address
     */
    public function __construct(Id $id, string $name, Address $address)
    {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
    }

    /**
     * @param array $products
     * @return Order
     * @throws \DomainException
     */
    public function createOrder(array $products = []): Order
    {
        return new Order(
            new Id(Uuid::uuid4()),
            $this,
            new ProductsCollection($products),
            new \DateTime(),
            new \DateTime(),
            new Status(Status::STATUS_OPEN)
        );
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Id
     */
    public function getID(): Id
    {
        return $this->id;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }
}