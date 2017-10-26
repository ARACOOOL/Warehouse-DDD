<?php

namespace Warehouse\Domain\Warehouse\Events;

use Warehouse\Domain\Customer\Customer;
use Warehouse\Domain\Event\Event;

/**
 * Class ReturnProductsEvent
 * @package Warehouse\Domain\Event
 */
class ProductsReturnedByCustomerEvent extends Event
{
    /**
     * @var array
     */
    private $products;
    /**
     * @var Customer
     */
    private $customer;

    /**
     * ReturnProductsEvent constructor.
     * @param \Warehouse\Domain\Product\Product[] $products
     * @param Customer $customer
     */
    public function __construct(array $products, Customer $customer)
    {
        $this->products = $products;
        $this->customer = $customer;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'return_products';
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }
}