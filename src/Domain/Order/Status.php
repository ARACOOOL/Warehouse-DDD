<?php

namespace Warehouse\Domain\Order;

/**
 * Class Status
 * @package Warehouse\Domain
 */
class Status
{
    const STATUS_CLOSE = 0;
    const STATUS_OPEN = 1;
    /**
     * @var int
     */
    private $status;

    /**
     * Status constructor.
     * @param int $status
     * @throws \DomainException
     */
    public function __construct(int $status)
    {
        if ($status != self::STATUS_CLOSE && $status != self::STATUS_OPEN) {
            throw new \DomainException('Invalid status');
        }

        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->status == self::STATUS_CLOSE;
    }
}