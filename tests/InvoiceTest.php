<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Warehouse\Domain\Calculator\TotalPriceCalculator;
use Warehouse\Domain\Customer\Customer;
use Warehouse\Domain\Customer\ObjectValues\Address;
use Warehouse\Domain\Invoice\Invoice;
use Warehouse\Domain\ObjectValues\Id;
use Warehouse\Domain\ObjectValues\Money;
use Warehouse\Domain\Order\ObjectValues\Status;
use Warehouse\Domain\Order\Order;
use Warehouse\Domain\Product\ObjectValues\ProductId;
use Warehouse\Domain\Product\Product;
use Warehouse\Domain\Invoice\ObjectValues\Status as InvoiceStatus;

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
            new InvoiceStatus(4),
            new \DateTime(),
            new \DateTime()
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
        self::assertEquals(new InvoiceStatus(InvoiceStatus::STATUS_OPENED), $invoice->getStatus());
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
