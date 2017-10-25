<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Warehouse\Domain\Customer\Customer;
use Warehouse\Domain\Customer\ObjectValues\Address;
use Warehouse\Domain\ObjectValues\Id;
use Warehouse\Domain\Order\Order;

/**
 * Class CustomerTest
 * @package tests
 */
class CustomerTest extends TestCase
{
    public function testCustomerGetters()
    {
        $customer = new Customer(
            new Id(Uuid::uuid4()),
            'Test customer',
            new Address('US', 'Philadelphia, PA', '1815 Gerry Rw', 19118)
        );
        self::assertEquals('Test customer', $customer->getName());
        self::assertInstanceOf(Address::class, $customer->getAddress());
        self::assertInstanceOf(Id::class, $customer->getID());
        self::assertInstanceOf(Order::class, $customer->createOrder());
    }
}