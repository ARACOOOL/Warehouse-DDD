<?php

namespace Warehouse\Domain\ObjectValues;

/**
 * Class Id
 * @package Warehouse\Domain
 */
class Id
{
    /**
     * @var string
     */
    private $id;

    /**
     * Id constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id();
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }
}