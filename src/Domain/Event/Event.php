<?php

namespace Warehouse\Domain\Event;

/**
 * Class Event
 * @package Warehouse\Domain\Event
 */
abstract class Event
{
    /**
     * @return string
     */
    abstract public function getName(): string;
}