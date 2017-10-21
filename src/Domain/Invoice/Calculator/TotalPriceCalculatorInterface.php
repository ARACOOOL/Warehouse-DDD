<?php

namespace Warehouse\Domain\Invoice\Calculator;

use Warehouse\Domain\Product\Product;

/**
 * Interface TotalPriceCalculatorInterface
 * @package Warehouse\Domain
 */
interface TotalPriceCalculatorInterface
{
    /**
     * @param \Warehouse\Domain\Product\Product[] $products
     * @return int
     * @internal param Invoice $invoice
     */
    public function calculate(array $products): int;
}