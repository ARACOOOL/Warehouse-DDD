<?php

namespace Warehouse\Domain\Product\ObjectValues;

use Warehouse\Domain\Id;
use Webmozart\Assert\Assert;

/**
 * Class ProductId
 * @package Warehouse\Domain
 */
class ProductId extends Id
{
    /**
     * ProductId constructor.
     * @param string $id
     */
    public function __construct($id)
    {
        Assert::uuid($id);
        parent::__construct($id);
    }
}