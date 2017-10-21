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
     * @param Product[] $products
     * @return int
     */
    public function calculate(array $products): int;
}