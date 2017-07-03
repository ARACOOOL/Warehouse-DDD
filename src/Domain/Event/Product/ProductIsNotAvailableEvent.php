<?php

namespace Warehouse\Domain\Event\Product;

/**
 * Class ProductIsNotAvailableEvent
 * @package Warehouse\Domain\Event
 */
class ProductIsNotAvailableEvent extends ProductEvent
{
    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'product_is_not_available';
    }
}