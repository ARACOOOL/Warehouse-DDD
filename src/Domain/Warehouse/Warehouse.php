<?php

namespace Warehouse\Domain\Warehouse;

use Warehouse\Domain\Customer\Customer;
use Warehouse\Domain\Event\EventsManagerInterface;
use Warehouse\Domain\Invoice\Invoice;
use Warehouse\Domain\Invoice\ObjectValues\Status;
use Warehouse\Domain\ObjectValues\Money;
use Warehouse\Domain\Order\Events\OrderShipped;
use Warehouse\Domain\Order\Order;
use Warehouse\Domain\Product\Events\ProductIsNotAvailableEvent;
use Warehouse\Domain\Product\ObjectValues\ProductId;
use Warehouse\Domain\Product\Product;
use Warehouse\Domain\Product\ProductsContainer;
use Warehouse\Domain\Product\Repositories\ProductsRepositoryInterface;
use Warehouse\Domain\Repository\PurchasesRepositoryInterface;
use Warehouse\Domain\Warehouse\Events\OutgoingPurchaseEvent;
use Warehouse\Domain\Warehouse\Events\ProductsReturnedByCustomerEvent;
use Warehouse\Domain\Warehouse\Events\ReturnProductsToSupplierEvent;

/**
 * Class Warehouse
 * @package Warehouse\Domain
 */
final class Warehouse
{
    /**
     * @var EventsManagerInterface
     */
    private $eventManager;
    /**
     * @var ProductsRepositoryInterface
     */
    private $productRepository;
    /**
     * @var PurchasesRepositoryInterface
     */
    private $purchasesRepository;

    /**
     * Warehouse constructor.
     * @param ProductsRepositoryInterface $productsRepository
     * @param PurchasesRepositoryInterface $purchasesRepository
     * @param EventsManagerInterface $eventsManager
     */
    public function __construct(
        ProductsRepositoryInterface $productsRepository,
        PurchasesRepositoryInterface $purchasesRepository,
        EventsManagerInterface $eventsManager
    ) {
        $this->productRepository = $productsRepository;
        $this->eventManager = $eventsManager;
        $this->purchasesRepository = $purchasesRepository;
    }

    /**
     * @param ProductsContainer $container
     */
    public function acceptContainer(ProductsContainer $container): void
    {
        $this->placeProducts($container->getProducts());
    }

    /**
     * @param \Warehouse\Domain\Product\Product[] $products
     */
    private function placeProducts(array $products): void
    {
        $returnProducts = [];
        foreach ($products as $product) {
            if ($product->isExpired()) {
                $returnProducts[] = $product;
            } else {
                $this->productRepository->increment($product->getID());
            }
        }

        if (count($returnProducts)) {
            $this->eventManager->dispatch(ReturnProductsToSupplierEvent::getName(), new ReturnProductsToSupplierEvent($returnProducts));
        }
    }

    /**
     * @param Order $order
     * @return Invoice
     * @throws \InvalidArgumentException
     */
    public function acceptOrder(Order $order): Invoice
    {
        $availableProducts = [];
        foreach ($order->getProducts() as $product) {
            if (!$this->isProductAvailable($product->getID())) {
                $this->eventManager->dispatch(ProductIsNotAvailableEvent::getName(),
                    new ProductIsNotAvailableEvent($product));
                continue;
            }

            $productId = $product->getID();
            $availableProducts[$productId->id()] = $order->getCountOfProduct($productId);
        }

        return $this->createInvoice($order, $availableProducts);
    }

    /**
     * @param ProductId $id
     * @return bool
     */
    public function isProductAvailable(ProductId $id): bool
    {
        return (bool)$this->productRepository->findOne($id);
    }

    /**
     * @param Order $order
     * @param array $availableProducts
     * @return Invoice
     * @throws \InvalidArgumentException
     */
    private function createInvoice(Order $order, array $availableProducts): Invoice
    {
        return Invoice::create($order, $availableProducts);
    }

    /**
     * @param Invoice $invoice
     * @throws \InvalidArgumentException
     */
    public function sendOrder(Invoice $invoice): void
    {
        $invoice->setShippedAt(new \DateTime());
        $invoice->setStatus(new Status(Status::STATUS_SHIPPED));
        $this->eventManager->dispatch(OrderShipped::getName(), new OrderShipped($invoice));
    }

    /**
     * @param Product[] $products
     */
    public function acceptReturnedProducts(array $products): void
    {
        $this->placeProducts($products);
        $this->eventManager->dispatch(ProductsReturnedByCustomerEvent::getName(), new ProductsReturnedByCustomerEvent($products));
    }

    /**
     * @param \Warehouse\Domain\Customer\Customer $customer
     */
    public function registerCustomer(Customer $customer): void
    {

    }

    /**
     * @param Product $product
     */
    public function registerProduct(Product $product): void
    {
        $this->productRepository->insert($product);
    }

    /**
     * @param ProductId $id
     * @return int
     */
    public function getCountOfProduct(ProductId $id): int
    {
        return $this->productRepository->getCountById($id);
    }

    /**
     * @param Invoice $invoice
     * @param Money $money
     */
    public function receiveMoney(Invoice $invoice, Money $money): void
    {
        $this->purchasesRepository->outgoing($invoice);
        $this->eventManager->dispatch(OutgoingPurchaseEvent::getName(), new OutgoingPurchaseEvent($invoice));
    }
}