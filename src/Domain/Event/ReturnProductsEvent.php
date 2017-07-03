<?php

namespace Warehouse\Domain\Event;

use Warehouse\Domain\Collection\ProductsCollection;
use Warehouse\Domain\ProductsContainer;

/**
 * Class ReturnProductsEvent
 * @package Warehouse\Domain\Event
 */
class ReturnProductsEvent extends Event
{
    /**
     * @var ProductsContainer
     */
    private $products;

    /**
     * ReturnProductsEvent constructor.
     * @param ProductsCollection $products
     */
    public function __construct(ProductsCollection $products)
    {
        $this->products = $products;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'return_products';
    }

    /**
     * @return ProductsCollection
     */
    public function getProducts(): ProductsCollection
    {
        return $this->products;
    }
}