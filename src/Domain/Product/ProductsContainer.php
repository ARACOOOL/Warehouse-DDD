<?php

namespace Warehouse\Domain\Product;

use Webmozart\Assert\Assert;

/**
 * Class ProductsContainer
 * @package Warehouse\Domain
 */
final class ProductsContainer
{
    /**
     * @var array
     */
    private $products;

    /**
     * ProductsContainer constructor.
     * @param array $products
     */
    public function __construct(array $products)
    {
        Assert::allIsInstanceOf($products, Product::class);
        $this->products = $products;
    }

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }
}