<?php

namespace Warehouse\Domain\Contract;

use Warehouse\Domain\Id;

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