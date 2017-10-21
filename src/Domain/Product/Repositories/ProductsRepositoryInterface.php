<?php

namespace Warehouse\Domain\Product\Repositories;

use Warehouse\Domain\Product\ObjectValues\ProductId;
use Warehouse\Domain\Product\Product;
use Warehouse\Domain\Repository\RepositoryInterface;

/**
 * Interface ProductsRepositoryInterface
 * @package Warehouse\Domain\Repository
 */
interface ProductsRepositoryInterface extends RepositoryInterface
{
    /**
     * @param \Warehouse\Domain\Product\ObjectValues\ProductId $id
     */
    public function increment(ProductId $id): void;

    /**
     * @param ProductId $id
     */
    public function decrement(ProductId $id): void;

    /**
     * @param \Warehouse\Domain\Product\Product $product
     */
    public function insert(Product $product): void;

    /**
     * @param ProductId $id
     * @return int
     */
    public function getCountById(ProductId $id): int;
}