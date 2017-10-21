<?php

namespace Warehouse\Domain\Invoice\ObjectValues;

/**
 * Class Status
 * @package Warehouse\Domain\Invoice\ObjectValues
 */
class Status
{
    const STATUS_SHIPPED = 2;
    const STATUS_OPENED = 1;
    
    /**
     * @var int
     */
    private $status;

    /**
     * Status constructor.
     * @param int $status
     * @throws \InvalidArgumentException
     */
    public function __construct(int $status)
    {
        if ($status != self::STATUS_OPENED && $status != self::STATUS_SHIPPED) {
            throw new \InvalidArgumentException('Invalid status of invoice');
        }
        $this->status = $status;
    }
}