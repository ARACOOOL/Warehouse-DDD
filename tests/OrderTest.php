<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Warehouse\Domain\Customer\Customer;
use Warehouse\Domain\Customer\ObjectValues\Address;
use Warehouse\Domain\Entity\Order;
use Warehouse\Domain\Id;
use Warehouse\Domain\Product\ObjectValues\ProductId;
use Warehouse\Domain\Product\Product;
use Warehouse\Domain\Status;

class OrderTest extends TestCase
{
    /**
     *
     */
    public function testIsOrderClosed(): void
    {
        $order = new Order(
            new Id('test'),
            new Customer(new Id(Uuid::uuid4()), 'test', new Address('test', 'test', 'test', 123)),
            [],
            new \DateTime(),
            new \DateTime(),
            new Status(Status::STATUS_OPEN)
        );

        self::assertFalse($order->getStatus()->isClosed());

        $order = new Order(
            new Id('test'),
            new Customer(new Id(Uuid::uuid4()), 'test', new Address('test', 'test', 'test', 123)),
            [],
            new \DateTime(),
            new \DateTime(),
            new Status(Status::STATUS_CLOSE)
        );

        self::assertTrue($order->getStatus()->isClosed());
    }

    /**
     *
     */
    public function testOrderStatusException(): void
    {
        $this->expectException(\DomainException::class);
        new Order(
            new Id('test'),
            new Customer(new Id(Uuid::uuid4()), 'test', new Address('test', 'test', 'test', 123)),
            [],
            new \DateTime(),
            new \DateTime(),
            new Status(3)
        );
    }

    /**
     *
     */
    public function testChangeStatus(): void
    {
        $order = new Order(
            new Id('test'),
            new Customer(new Id(Uuid::uuid4()), 'test', new Address('test', 'test', 'test', 123)),
            [],
            new \DateTime(),
            new \DateTime(),
            new Status(Status::STATUS_OPEN)
        );

        $order->setStatus(new Status(Status::STATUS_CLOSE));
        self::assertTrue($order->getStatus()->isClosed());
    }

    /**
     *
     */
    public function testCantOpenClosedOrder(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Order was closed');

        $order = new Order(
            new Id('test'),
            new Customer(new Id(Uuid::uuid4()), 'test', new Address('test', 'test', 'test', 123)),
            [],
            new \DateTime(),
            new \DateTime(),
            new Status(Status::STATUS_CLOSE)
        );

        $order->setStatus(new Status(Status::STATUS_OPEN));
    }

    public function testCantChangeOrderAfterItClosed(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Can\'t change order, it was closed');

        $order = new Order(
            new Id('test'),
            new Customer(new Id(Uuid::uuid4()), 'test', new Address('test', 'test', 'test', 123)),
            [],
            new \DateTime(),
            new \DateTime(),
            new Status(Status::STATUS_CLOSE)
        );

        $order->addProduct(
            new Product(
                new ProductId(Uuid::uuid4()),
                'test title',
                123,
                'test category',
                new \DateTime(),
                new \DateTime('tomorrow')
            )
        );
    }

    /**
     *
     */
    public function testUpdateUpdateDateAfterOrderChange(): void
    {
        $order = new Order(
            new Id('test'),
            new Customer(new Id(Uuid::uuid4()), 'test', new Address('test', 'test', 'test', 123)),
            [],
            new \DateTime(),
            new \DateTime('yesterday'),
            new Status(Status::STATUS_OPEN)
        );
        self::assertEquals(new \DateTime('yesterday'), $order->getUpdatedAt());

        $order->addProduct(
            $this->createMock(Product::class)
        );
        self::assertEquals((new \DateTime())->format(DATE_ATOM), $order->getUpdatedAt()->format(DATE_ATOM));
    }

    /**
     *
     */
    public function testAddingAndRemovingProducts()
    {
        $order = new Order(
            new Id('test'),
            new Customer(new Id(Uuid::uuid4()), 'test', new Address('test', 'test', 'test', 123)),
            [],
            new \DateTime(),
            new \DateTime('yesterday'),
            new Status(Status::STATUS_OPEN)
        );

        $product = new Product(
            new ProductId(Uuid::uuid4()),
            'test title',
            123,
            'test category',
            new \DateTime(),
            new \DateTime('tomorrow')
        );

        $order->addProduct($product);
        $order->addProduct(new Product(
            new ProductId(Uuid::uuid4()),
            'test title',
            123,
            'test category',
            new \DateTime(),
            new \DateTime('tomorrow')
        ));
        self::assertCount(2, $order->getProducts());

        $order->removeProduct($product);
        self::assertCount(1, $order->getProducts());
    }
}
