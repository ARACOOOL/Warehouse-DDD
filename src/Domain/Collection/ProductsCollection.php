<?php

namespace Warehouse\Domain\Collection;

use Warehouse\Domain\Entity\Product;
use Webmozart\Assert\Assert;

/**
 * Class ProductsCollection
 * @package Warehouse\Domain\Collection
 */
final class ProductsCollection implements \Iterator
{
    private $products;
    private $count;

    /**
     * ProductsCollection constructor.
     * @param array $products
     */
    public function __construct(array $products = [])
    {
        Assert::allIsInstanceOf($products, Product::class);
        foreach ($products as $product) {
            $this->addProduct($product);
        }
    }

    /**
     * @param Product $product
     */
    public function addProduct(Product $product): void
    {
        $id = $product->getID()->id();
        if (!isset($this->products[$id])) {
            $this->products[$id] = $product;
            $this->count[$id] = 1;
            return;
        }

        $this->count[$id] += 1;
    }

    /**
     * @param Product $product
     */
    public function removeProduct(Product $product): void
    {
        $id = $product->getID()->id();
        if (isset($this->products[$id])) {
            unset($this->products[$id], $this->count[$id]);
        }
    }

    /**
     * @return Product
     */
    public function current(): Product
    {
        return current($this->products);
    }

    /**
     *
     */
    public function next(): void
    {
        next($this->products);
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->products);
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return key($this->products) !== null;
    }

    /**
     *
     */
    public function rewind(): void
    {
        reset($this->products);
    }

    /**
     * @return int
     */
    public function totalCount(): int
    {
        return array_sum($this->count);
    }
}