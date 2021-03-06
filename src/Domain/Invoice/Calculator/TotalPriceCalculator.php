<?php

namespace Warehouse\Domain\Invoice\Calculator;

use Warehouse\Domain\Product\Product;

/**
 * Class TotalPriceCalculator
 * @package Warehouse\Domain\Calculator
 */
class TotalPriceCalculator implements TotalPriceCalculatorInterface
{
    /**
     * @param Product[] $products
     * @return int
     */
    public function calculate(array $products): int
    {
        $totalPrice = 0;
        foreach ($products as $key => $product) {
            $totalPrice += $product->getPrice();
        }

        return $totalPrice;
    }
}