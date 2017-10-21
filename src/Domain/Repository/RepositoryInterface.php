<?php

namespace Warehouse\Domain\Repository;

use Warehouse\Domain\Entity;
use Warehouse\Domain\ObjectValues\Id;

/**
 * Interface RepositoryInterface
 * @package Warehouse\Domain\Repository
 */
interface RepositoryInterface
{
    /**
     * @param \Warehouse\Domain\ObjectValues\Id $id
     * @return Entity
     */
    public function findOne(Id $id): ?Entity;
}