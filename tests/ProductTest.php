<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Warehouse\Domain\Product\ObjectValues\ProductId;
use Warehouse\Domain\Product\Product;

/**
 * Class ProductTest
 * @package tests
 */
class ProductTest extends TestCase
{
    /**
     *
     */
    public function testIsProductExpired()
    {
        $product = new Product(
            new ProductId(Uuid::uuid4()),
            'test title',
            123,
            'test category',
            new \DateTime(),
            new \DateTime('yesterday')
        );
        self::assertTrue($product->isExpired());
    }

    /**
     *
     */
    public function testIsProductNotExpired()
    {
        $product = new Product(
            new ProductId(Uuid::uuid4()),
            'test title',
            123,
            'test category',
            new \DateTime(),
            new \DateTime('tomorrow')
        );
        self::assertFalse($product->isExpired());
    }

    public function testProductGetters()
    {
        $product = new Product(
            new ProductId(Uuid::uuid4()),
            'test title',
            123,
            'test category',
            new \DateTime(),
            new \DateTime('tomorrow')
        );

        self::assertEquals('test title', $product->getTitle());
        self::assertEquals('test category', $product->getCategory());
        self::assertInstanceOf(\DateTime::class, $product->getRegisteredDate());
        self::assertInstanceOf(\DateTime::class, $product->getExpiredDate());
    }
}
