<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Warehouse\Domain\Customer\Customer;
use Warehouse\Domain\Customer\ObjectValues\Address;
use Warehouse\Domain\Event\EventsManagerInterface;
use Warehouse\Domain\Invoice\Invoice;
use Warehouse\Domain\Invoice\ObjectValues\Status as InvoiceStatus;
use Warehouse\Domain\ObjectValues\Id;
use Warehouse\Domain\ObjectValues\Money;
use Warehouse\Domain\Order\{
    Events\OrderShipped, ObjectValues\Status, Order
};
use Warehouse\Domain\Product\{
    Events\ProductIsNotAvailableEvent, ObjectValues\ProductId, Product, ProductsContainer, Repositories\ProductsRepositoryInterface
};
use Warehouse\Domain\Repository\PurchasesRepositoryInterface;
use Warehouse\Domain\Warehouse\{
    Events\OutgoingPurchaseEvent, Events\ProductsReturnedByCustomerEvent, Events\ReturnProductsToSupplierEvent, Warehouse
};

/**
 * Class WareHouseTest
 * @package tests
 */
class WareHouseTest extends TestCase
{
    /**
     *
     */
    public function testAcceptContainer(): void
    {
        $eventManager = $this->createMock(EventsManagerInterface::class);
        $eventManager->expects(self::once())
            ->method('dispatch')
            ->with($this->isInstanceOf(ReturnProductsToSupplierEvent::class));

        $productId = new ProductId(Uuid::uuid4());
        $product = new Product(
            $productId,
            'test title',
            123,
            'test category',
            new \DateTime(),
            new \DateTime('yesterday')
        );

        $productId2 = new ProductId(Uuid::uuid4());
        $product2 = new Product(
            $productId2,
            'test title',
            123,
            'test category',
            new \DateTime(),
            new \DateTime('tomorrow')
        );

        $productRepository = $this->createMock(ProductsRepositoryInterface::class);
        $productRepository->expects(self::once())
            ->method('increment')
            ->with($productId2);

        $warehouse = new Warehouse(
            $productRepository,
            $this->createMock(PurchasesRepositoryInterface::class),
            $eventManager
        );
        $warehouse->acceptContainer(new ProductsContainer([$product, $product2]));
    }

    public function testIsProductAvailable()
    {
        $productRepository = $this->createMock(ProductsRepositoryInterface::class);
        $productRepository->expects(self::once())
            ->method('findOne')
            ->willReturn($this->createMock(Product::class));

        $warehouse = new Warehouse(
            $productRepository,
            $this->createMock(PurchasesRepositoryInterface::class),
            $this->createMock(EventsManagerInterface::class)
        );

        self::assertTrue($warehouse->isProductAvailable(new ProductId(Uuid::uuid4())));
    }

    /**
     *
     */
    public function testIsNotProductAvailable(): void
    {
        $productRepository = $this->createMock(ProductsRepositoryInterface::class);
        $productRepository->expects(self::once())
            ->method('findOne')
            ->willReturn(null);
        $warehouse = new Warehouse($productRepository, $this->createMock(PurchasesRepositoryInterface::class),
            $this->createMock(EventsManagerInterface::class));
        self::assertFalse($warehouse->isProductAvailable(new ProductId(Uuid::uuid4())));
    }

    /**
     *
     */
    public function testReceivingMoneyFromCustomer(): void
    {
        $eventManager = $this->createMock(EventsManagerInterface::class);
        $eventManager->expects(self::once())
            ->method('dispatch')
            ->with($this->isInstanceOf(OutgoingPurchaseEvent::class));
        $purchases = $this->createMock(PurchasesRepositoryInterface::class);
        $purchases->expects(self::once())
            ->method('outgoing')
            ->with($this->isInstanceOf(Invoice::class), $this->isInstanceOf(Money::class));
        $warehouse = new Warehouse(
            $this->createMock(ProductsRepositoryInterface::class),
            $purchases,
            $eventManager
        );

        $warehouse->receiveMoney(
            Invoice::create($this->createMock(Order::class), []),
            Money::USD(500)
        );
    }

    /**
     *
     */
    public function testAcceptOrderAndWeHaveAllProducts()
    {
        $eventManager = $this->createMock(EventsManagerInterface::class);

        $productRepository = $this->createMock(ProductsRepositoryInterface::class);
        $productRepository->expects(self::once())
            ->method('findOne')
            ->willReturn($this->createMock(Product::class));

        $warehouse = new Warehouse(
            $productRepository,
            $this->createMock(PurchasesRepositoryInterface::class),
            $eventManager
        );
        $order = new Order(
            new Id('sdf'),
            $this->createMock(Customer::class),
            [],
            new \DateTime(),
            new \DateTime(),
            new Status(Status::STATUS_OPEN)
        );
        $order->addProduct(new Product(
            new ProductId(Uuid::uuid4()->toString()),
            'test title',
            123,
            'test category',
            new \DateTime(),
            new \DateTime('tomorrow')
        ));
        $invoice = $warehouse->acceptOrder($order);

        self::assertInstanceOf(Invoice::class, $invoice);
    }

    /**
     *
     */
    public function testAcceptOrderAndWeDoNotHaveProduct()
    {
        $eventManager = $this->createMock(EventsManagerInterface::class);
        $eventManager->expects(self::once())
            ->method('dispatch')
            ->with($this->isInstanceOf(ProductIsNotAvailableEvent::class));

        $productRepository = $this->createMock(ProductsRepositoryInterface::class);
        $productRepository->expects(self::once())
            ->method('findOne')
            ->willReturn(null);

        $warehouse = new Warehouse(
            $productRepository,
            $this->createMock(PurchasesRepositoryInterface::class),
            $eventManager
        );

        $order = new Order(
            new Id('sdf'),
            $this->createMock(Customer::class),
            [],
            new \DateTime(),
            new \DateTime(),
            new Status(Status::STATUS_OPEN)
        );
        $order->addProduct(new Product(
            new ProductId(Uuid::uuid4()->toString()),
            'test title',
            123,
            'test category',
            new \DateTime(),
            new \DateTime('tomorrow')
        ));

        $invoice = $warehouse->acceptOrder($order);

        self::assertInstanceOf(Invoice::class, $invoice);
    }

    /**
     *
     */
    public function testCountOfProduct()
    {
        $productRepository = $this->createMock(ProductsRepositoryInterface::class);
        $productRepository->expects(self::once())
            ->method('getCountById')
            ->willReturn(2);
        $warehouse = new Warehouse(
            $productRepository,
            $this->createMock(PurchasesRepositoryInterface::class),
            $this->createMock(EventsManagerInterface::class)
        );

        self::assertEquals(2, $warehouse->getCountOfProduct(new ProductId(Uuid::uuid4()->toString())));
    }

    public function testReturnedProducts()
    {
        $eventManager = $this->createMock(EventsManagerInterface::class);
        $eventManager->expects(self::exactly(2))
            ->method('dispatch')
            ->withConsecutive([$this->isInstanceOf(ReturnProductsToSupplierEvent::class)],
                [$this->isInstanceOf(ProductsReturnedByCustomerEvent::class)]);

        $productId = new ProductId(Uuid::uuid4()->toString());

        $product = $this->createMock(Product::class);
        $product->expects(self::once())
            ->method('isExpired')
            ->willReturn(true);

        $product2 = $this->createMock(Product::class);
        $product2->expects(self::once())
            ->method('isExpired')
            ->willReturn(false);
        $product2->expects(self::once())
            ->method('getID')
            ->willReturn($productId);

        $productRepository = $this->createMock(ProductsRepositoryInterface::class);
        $productRepository->expects(self::once())
            ->method('increment')
            ->with($productId);

        $warehouse = new Warehouse(
            $productRepository,
            $this->createMock(PurchasesRepositoryInterface::class),
            $eventManager
        );

        $warehouse->acceptReturnedProducts([$product, $product2],
            new Customer(new Id('test'), 'test name', new Address('test', 'test', 'test', 19999)));
    }

    public function testSendOrder()
    {
        $eventManager = $this->createMock(EventsManagerInterface::class);
        $eventManager->expects(self::once())
            ->method('dispatch')
            ->with($this->isInstanceOf(OrderShipped::class));

        $warehouse = new Warehouse(
            $this->createMock(ProductsRepositoryInterface::class),
            $this->createMock(PurchasesRepositoryInterface::class),
            $eventManager
        );

        $invoice = $this->createMock(Invoice::class);
        $invoice->expects(self::once())
            ->method('setShippedAt')
            ->with($this->isInstanceOf(\DateTime::class));
        $invoice->expects(self::once())
            ->method('setStatus')
            ->with($this->isInstanceOf(InvoiceStatus::class));

        $warehouse->sendOrder($invoice);
    }
}
