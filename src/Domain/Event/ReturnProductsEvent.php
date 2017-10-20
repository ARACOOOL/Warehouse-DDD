<?php

namespace Warehouse\Domain\Event;

use Warehouse\Domain\Entity\Product;

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
     * @param Product[] $products
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