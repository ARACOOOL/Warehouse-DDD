<?php

namespace Warehouse\Domain\Product\Events;

/**
 * Class ProductIsNotAvailableEvent
 * @package Warehouse\Domain\Event
 */
class ProductIsNotAvailableEvent extends ProductEvent
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'product_is_not_available';
    }
}