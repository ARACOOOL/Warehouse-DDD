<?php

namespace Warehouse\Domain\Order\Events;

use Warehouse\Domain\Event\Event;
use Warehouse\Domain\Invoice\Invoice;

/**
 * Class OrderShipped
 * @package Warehouse\Domain\Event
 */
class OrderShipped extends Event
{
    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * OrderShipped constructor.
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'order_shipped';
    }

    /**
     * @return \Warehouse\Domain\Invoice\Invoice
     */
    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }
}