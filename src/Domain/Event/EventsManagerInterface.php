<?php

namespace Warehouse\Domain\Event;

/**
 * Interface EventsManagerInterface
 * @package Warehouse\Domain\Event
 */
interface EventsManagerInterface
{
    /**
     * @param Event $event
     */
    public function dispatch(Event $event): void;

    /**
     * @param EventHandlerInterface $handler
     */
    public function addListener(EventHandlerInterface $handler): void;
}