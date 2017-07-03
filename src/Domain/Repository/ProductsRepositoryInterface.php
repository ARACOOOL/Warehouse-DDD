<?php

namespace Warehouse\Domain\Repository;

use Warehouse\Domain\Entity\Product;
use Warehouse\Domain\ProductId;

/**
 * Interface ProductsRepositoryInterface
 * @package Warehouse\Domain\Repository
 */
interface ProductsRepositoryInterface extends RepositoryInterface
{
    /**
     * @param ProductId $id
     */
    public function increment(ProductId $id): void;

    /**
     * @param ProductId $id
     */
    public function decrement(ProductId $id): void;

    /**
     * @param Product $product
     */
    public function new(Product $product): void;

    /**
     * @param ProductId $id
     * @return int
     */
    public function getProductCount(ProductId $id): int;
}