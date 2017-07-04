<?php

namespace Warehouse\Domain;

use Warehouse\Domain\Collection\ProductsCollection;
use Warehouse\Domain\Entity\Customer;
use Warehouse\Domain\Entity\Invoice;
use Warehouse\Domain\Entity\Order;
use Warehouse\Domain\Entity\Product;
use Warehouse\Domain\Event\EventsManagerInterface;
use Warehouse\Domain\Event\OrderShipped;
use Warehouse\Domain\Event\OutgoingPurchaseEvent;
use Warehouse\Domain\Event\Product\ProductIsNotAvailableEvent;
use Warehouse\Domain\Event\ReturnProductsEvent;
use Warehouse\Domain\Repository\ProductsRepositoryInterface;
use Warehouse\Domain\Repository\PurchasesRepositoryInterface;

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
        $this->disassembleProducts($container->getProducts());
    }

    /**
     * @param ProductsCollection $products
     */
    private function disassembleProducts(ProductsCollection $products): void
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
            $this->returnProductsToSupplier(new ProductsContainer(new ProductsCollection($returnProducts)));
        }
    }

    /**
     * @param ProductsContainer $container
     */
    public function returnProductsToSupplier(ProductsContainer $container): void
    {
        $this->eventManager->dispatch(ReturnProductsEvent::getName(),
            new ReturnProductsEvent($container->getProducts()));
    }

    /**
     * @param Order $order
     * @throws \InvalidArgumentException
     */
    public function acceptOrder(Order $order): void
    {
        $availableProducts = [];
        /* @var $item Product */
        foreach ($order->getProducts() as $productId => $count) {
            if (!$this->isProductAvailable(new ProductId($productId))) {
                $this->eventManager->dispatch(ProductIsNotAvailableEvent::getName(),
                    new ProductIsNotAvailableEvent($productId));
                continue;
            }

            $availableProducts[$productId] = $count;
        }

        $invoice = $this->createInvoice($order, $availableProducts);
        $this->sendOrder($invoice);
    }

    /**
     * @param ProductId $id
     * @return bool
     */
    public function isProductAvailable(ProductId $id): bool
    {
        try {
            return (bool)$this->productRepository->findOne($id);
        } catch (\InvalidArgumentException $exception) {
            return false;
        }
    }

    /**
     * @param Order $order
     * @param array $availableProducts
     * @return Invoice
     * @throws \InvalidArgumentException
     */
    private function createInvoice(Order $order, array $availableProducts): Invoice
    {
        return Invoice::create($order, new ProductsCollection($availableProducts));
    }

    /**
     * @param Invoice $invoice
     */
    private function sendOrder(Invoice $invoice): void
    {
        $invoice->setShippedAt(new \DateTime());
        $invoice->setStatus(Invoice::STATUS_SHIPPED);
        $this->eventManager->dispatch(OrderShipped::getName(), new OrderShipped($invoice));
    }

    /**
     * @param ProductsCollection $products
     */
    public function acceptReturnedProducts(ProductsCollection $products): void
    {
        $this->disassembleProducts($products);
        $this->eventManager->dispatch(ReturnProductsEvent::getName(), new ReturnProductsEvent($products));
    }

    /**
     * @param Customer $customer
     */
    public function registerCustomer(Customer $customer): void
    {

    }

    /**
     * @param Product $product
     */
    public function registerProduct(Product $product): void
    {
        $this->productRepository->new($product);
    }

    /**
     * @param ProductId $id
     * @return int
     */
    public function getProductCount(ProductId $id): int
    {
        return $this->productRepository->getProductCount($id);
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