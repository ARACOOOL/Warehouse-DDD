<?php

namespace Warehouse\Domain;

use Warehouse\Domain\Collection\ProductsCollection;

/**
 * Class ProductsContainer
 * @package Warehouse\Domain
 */
final class ProductsContainer
{
    /**
     * @var ProductsCollection
     */
    private $products;

    /**
     * ProductsContainer constructor.
     * @param ProductsCollection $products
     */
    public function __construct(ProductsCollection $products)
    {
        $this->products = $products;
    }

    /**
     * @return ProductsCollection
     */
    public function getProducts(): ProductsCollection
    {
        return $this->products;
    }
}