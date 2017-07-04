<?php

namespace Warehouse\Domain\Repository;

use Warehouse\Domain\Entity\Invoice;

/**
 * Interface PurchasesRepositoryInterface
 * @package Warehouse\Domain\Repository
 */
interface PurchasesRepositoryInterface
{
    /**
     * @param Invoice $invoice
     */
    public function outgoing(Invoice $invoice): void;
}