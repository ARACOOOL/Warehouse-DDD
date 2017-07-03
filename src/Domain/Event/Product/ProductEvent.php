<?php

namespace Warehouse\Domain\Event\Product;

use Warehouse\Domain\Entity\Product;
use Warehouse\Domain\Event\Event;

/**
 * Class ProductEvent
 * @package Warehouse\Domain\Event\Product
 */
abstract class ProductEvent extends Event
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * ProductIsNotAvailableEvent constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }
}