<?php

namespace Warehouse\Domain\Event;

use Warehouse\Domain\Product\Product;

/**
 * Class ReturnProductsEvent
 * @package Warehouse\Domain\Event
 */
class ReturnProductsEvent extends Event
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
    public static function getName(): string
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