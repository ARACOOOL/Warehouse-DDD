<?php

namespace Warehouse\Domain\Product;

use Warehouse\Domain\Contract\Entity;
use Warehouse\Domain\Id;
use Warehouse\Domain\Product\ObjectValues\ProductId;

/**
 * Class Product
 * @package Warehouse\Domain
 */
class Product implements Entity
{
    /**
     * @var Id
     */
    private $id;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $category;
    /**
     * @var \DateTime
     */
    private $registeredDate;
    /**
     * @var \DateTime
     */
    private $expiredDate;
    /**
     * @var int
     */
    private $price;

    /**
     * Product constructor.
     * @param \Warehouse\Domain\Product\ObjectValues\ProductId $id
     * @param string $title
     * @param int $price
     * @param string $category
     * @param \DateTime $registeredDate
     * @param \DateTime $expiredDate
     */
    public function __construct(
        ProductId $id,
        string $title,
        int $price,
        string $category,
        \DateTime $registeredDate,
        \DateTime $expiredDate
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->category = $category;
        $this->registeredDate = $registeredDate;
        $this->expiredDate = $expiredDate;
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return \DateTime
     */
    public function getRegisteredDate(): \DateTime
    {
        return $this->registeredDate;
    }

    /**
     * @return \DateTime
     */
    public function getExpiredDate(): \DateTime
    {
        return $this->expiredDate;
    }

    /**
     * @return Id
     */
    public function getID(): Id
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expiredDate < new \DateTime();
    }
}