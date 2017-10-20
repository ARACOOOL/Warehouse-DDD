<?php

namespace Warehouse\Domain\Calculator;

use Warehouse\Domain\Entity\Product;

/**
 * Interface TotalPriceCalculatorInterface
 * @package Warehouse\Domain
 */
interface TotalPriceCalculatorInterface
{
    /**
     * @param Product[] $products
     * @return int
     * @internal param Invoice $invoice
     */
    public function calculate(array $products): int;
}