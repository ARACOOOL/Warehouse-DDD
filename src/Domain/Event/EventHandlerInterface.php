<?php

namespace Warehouse\Domain\Event;

/**
 * Interface EventHandlerInterface
 * @package Warehouse\Domain\Event
 */
interface EventHandlerInterface
{
    /**
     * @param Event $event
     */
    public function handle(Event $event);

    /**
     * @return array
     */
    public function getListeners(): array;
}