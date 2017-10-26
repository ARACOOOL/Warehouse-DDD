<?php

namespace Warehouse\Domain\Repository;

use Warehouse\Domain\Invoice\Invoice;
use Warehouse\Domain\ObjectValues\Money;

/**
 * Interface PurchasesRepositoryInterface
 * @package Warehouse\Domain\Repository
 */
interface PurchasesRepositoryInterface
{
    /**
     * @param \Warehouse\Domain\Invoice\Invoice $invoice
     * @param Money $money
     */
    public function outgoing(Invoice $invoice, Money $money): void;
}