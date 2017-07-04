<?php

namespace Warehouse\Domain\Entity;

use Warehouse\Domain\Contract\Entity;
use Warehouse\Domain\Id;

/**
 * Class Supplier
 * @package Warehouse\Domain\Entity
 */
class Supplier implements Entity
{
    /**
     * @var Id
     */
    private $id;
    /**
     * @var string
     */
    private $name;

    /**
     * Supplier constructor.
     * @param Id $id
     * @param string $name
     */
    public function __construct(Id $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return Id
     */
    public function getID(): Id
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}