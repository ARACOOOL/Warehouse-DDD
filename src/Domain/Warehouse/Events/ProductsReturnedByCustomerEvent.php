<?php

namespace Warehouse\Domain\Warehouse\Events;

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
     * ReturnProductsEvent constructor.
     * @param \Warehouse\Domain\Product\Product[] $products
     */
    public function __construct(array $products)
    {
        $this->products = $products;
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
}