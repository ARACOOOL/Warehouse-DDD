<?php

namespace Warehouse\Domain;

use Warehouse\Domain\ObjectValues\Id;

/**
 * Class Entity
 * @package Warehouse\Domain\Contracts
 */
interface Entity
{
    /**
     * @return Id
     */
    public function getID();
}