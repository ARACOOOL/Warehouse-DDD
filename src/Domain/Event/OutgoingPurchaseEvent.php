<?php

namespace Warehouse\Domain\Event;

use Warehouse\Domain\Invoice\Invoice;

/**
 * Class OutgoingPurchaseEvent
 * @package Warehouse\Domain\Event
 */
class OutgoingPurchaseEvent extends Event
{
    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * OutgoingPurchaseEvent constructor.
     * @param \Warehouse\Domain\Invoice\Invoice $invoice
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
        return 'outgoing_purchase';
    }

    /**
     * @return \Warehouse\Domain\Invoice\Invoice
     */
    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }
}