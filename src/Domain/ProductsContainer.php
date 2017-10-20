<?php

namespace Warehouse\Domain;

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