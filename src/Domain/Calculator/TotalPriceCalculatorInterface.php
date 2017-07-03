<?php

namespace Warehouse\Domain\Calculator;

use Warehouse\Domain\Collection\ProductsCollection;

/**
 * Interface TotalPriceCalculatorInterface
 * @package Warehouse\Domain
 */
interface TotalPriceCalculatorInterface
{
    /**
     * @param ProductsCollection $products
     * @return int
     * @internal param Invoice $invoice
     */
    public function calculate(ProductsCollection $products): int;
}