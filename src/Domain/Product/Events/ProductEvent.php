<?php

namespace Warehouse\Domain\Product\Events;

use Warehouse\Domain\Event\Event;
use Warehouse\Domain\Product\Product;

/**
 * Class ProductEvent
 * @package Warehouse\Domain\Event\Product
 */
abstract class ProductEvent extends Event
{
    /**
     * @var \Warehouse\Domain\Product\Product
     */
    protected $product;

    /**
     * ProductIsNotAvailableEvent constructor.
     * @param \Warehouse\Domain\Product\Product $product
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