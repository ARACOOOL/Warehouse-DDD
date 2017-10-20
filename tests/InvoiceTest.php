<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Warehouse\Domain\Address;
use Warehouse\Domain\Calculator\TotalPriceCalculator;
use Warehouse\Domain\Entity\Customer;
use Warehouse\Domain\Entity\Invoice;
use Warehouse\Domain\Entity\Order;
use Warehouse\Domain\Entity\Product;
use Warehouse\Domain\Id;
use Warehouse\Domain\Money;
use Warehouse\Domain\ProductId;
use Warehouse\Domain\Status;

/**
 * Class InvoiceTest
 * @package tests
 */
class InvoiceTest extends TestCase
{
    /**
     *
     */
    public function testInvalidStatusValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid status of invoice');

        new Invoice(
            new Id('test'),
            $this->createMock(Order::class),
            [],
            4,
            new \DateTime(),
            new \DateTime(),
            new TotalPriceCalculator()
        );
    }

    /**
     *
     */
    public function testInvoiceCreation(): void
    {
        $invoice = Invoice::create($this->createMock(Order::class), []);
        $invoice->setCalculator(new TotalPriceCalculator());

        self::assertInstanceOf(Invoice::class, $invoice);
        self::assertEquals(Invoice::STATUS_OPENED, $invoice->getStatus());
    }

    /**
     *
     */
    public function testTotalPrice(): void
    {
        $order = new Order(
            new Id('test'),
            new Customer(new Id(Uuid::uuid4()), 'test', new Address('test', 'test', 'test', 123)),
            [],
            new \DateTime(),
            new \DateTime('yesterday'),
            new Status(Status::STATUS_OPEN)
        );
        $invoice = Invoice::create($order, [
            new Product(
                new ProductId(Uuid::uuid4()),
                'test title',
                123,
                'test category',
                new \DateTime(),
                new \DateTime('tomorrow')
            ),
            new Product(
                new ProductId(Uuid::uuid4()),
                'test title',
                5,
                'test category',
                new \DateTime(),
                new \DateTime('tomorrow')
            )
        ]);
        $invoice->setCalculator(new TotalPriceCalculator());
        self::assertInstanceOf(Money::class, $invoice->getTotalPrice());
    }

    /**
     *
     */
    public function testInvoiceCalculatorDidntSetException(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $invoice = Invoice::create(new Order(
            new Id('test'),
            new Customer(new Id(Uuid::uuid4()), 'test', new Address('test', 'test', 'test', 123)),
            [],
            new \DateTime(),
            new \DateTime('yesterday'),
            new Status(Status::STATUS_OPEN)
        ), []);
        $invoice->getTotalPrice();
    }

    /**
     *
     */
    public function testTotalCount(): void
    {
        $order = new Order(
            new Id('test'),
            new Customer(new Id(Uuid::uuid4()), 'test', new Address('test', 'test', 'test', 123)),
            [],
            new \DateTime(),
            new \DateTime('yesterday'),
            new Status(Status::STATUS_OPEN)
        );
        $invoice = Invoice::create($order, [
            new Product(
                new ProductId(Uuid::uuid4()),
                'test title',
                123,
                'test category',
                new \DateTime(),
                new \DateTime('tomorrow')
            ),
            new Product(
                new ProductId(Uuid::uuid4()),
                'test title',
                5,
                'test category',
                new \DateTime(),
                new \DateTime('tomorrow')
            )
        ]);
        $invoice->setCalculator(new TotalPriceCalculator());

        self::assertEquals(2, $invoice->getTotalCount());
    }
}
