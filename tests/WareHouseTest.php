<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Warehouse\Domain\Entity\Invoice;
use Warehouse\Domain\Entity\Order;
use Warehouse\Domain\Entity\Product;
use Warehouse\Domain\Event\EventsManagerInterface;
use Warehouse\Domain\Event\OutgoingPurchaseEvent;
use Warehouse\Domain\Event\ReturnProductsEvent;
use Warehouse\Domain\Money;
use Warehouse\Domain\ProductId;
use Warehouse\Domain\ProductsContainer;
use Warehouse\Domain\Repository\ProductsRepositoryInterface;
use Warehouse\Domain\Repository\PurchasesRepositoryInterface;
use Warehouse\Domain\Warehouse;

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
            ->with(ReturnProductsEvent::getName(), $this->callback(function ($subject) {
                return $subject instanceof ReturnProductsEvent;
            }));

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

        $warehouse = new Warehouse($productRepository, $this->createMock(PurchasesRepositoryInterface::class),
            $eventManager);
        $warehouse->acceptContainer(new ProductsContainer([$product, $product2]));
    }

    /**
     *
     */
    public function testIsProductAvailableWithException(): void
    {
        $productRepository = $this->createMock(ProductsRepositoryInterface::class);
        $productRepository->expects(self::once())
            ->method('findOne')
            ->willThrowException(new \InvalidArgumentException());
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
            ->with(OutgoingPurchaseEvent::getName());
        $purchases = $this->createMock(PurchasesRepositoryInterface::class);
        $purchases->expects(self::once())
            ->method('outgoing');
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
}
