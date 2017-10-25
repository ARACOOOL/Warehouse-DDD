<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Warehouse\Domain\ObjectValues\Id;
use Warehouse\Domain\Supplier\Supplier;

/**
 * Class SupplierTest
 * @package tests
 */
class SupplierTest extends TestCase
{
    public function testSupplierGetters()
    {
        $customer = new Supplier(
            new Id(Uuid::uuid4()),
            'Test customer'
        );
        self::assertEquals('Test customer', $customer->getName());
        self::assertInstanceOf(Id::class, $customer->getID());
    }
}