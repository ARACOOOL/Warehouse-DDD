<?php

namespace Warehouse\Domain\Repository;

use Warehouse\Domain\Invoice\Invoice;

/**
 * Interface PurchasesRepositoryInterface
 * @package Warehouse\Domain\Repository
 */
interface PurchasesRepositoryInterface
{
    /**
     * @param \Warehouse\Domain\Invoice\Invoice $invoice
     */
    public function outgoing(Invoice $invoice): void;
}