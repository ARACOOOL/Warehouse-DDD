<?php

namespace Warehouse\Domain\Event;

/**
 * Interface EventsManagerInterface
 * @package Warehouse\Domain\Event
 */
interface EventsManagerInterface
{
    /**
     * @param string $eventName
     * @param Event $event
     */
    public function dispatch(string $eventName, Event $event): void;

    /**
     * @param EventHandlerInterface $handler
     */
    public function addListener(EventHandlerInterface $handler): void;
}