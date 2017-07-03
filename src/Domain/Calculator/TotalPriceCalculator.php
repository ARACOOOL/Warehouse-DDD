<?php

namespace Warehouse\Domain\Calculator;

use Warehouse\Domain\Collection\ProductsCollection;

/**
 * Class TotalPriceCalculator
 * @package Warehouse\Domain\Calculator
 */
class TotalPriceCalculator implements TotalPriceCalculatorInterface
{
    /**
     * @param ProductsCollection $products
     * @return int
     * @internal param Invoice $invoice
     */
    public function calculate(ProductsCollection $products): int
    {
        $totalPrice = 0;
        foreach ($products as $key => $product) {
            $totalPrice += $product->getPrice();
        }

        return $totalPrice;
    }
}