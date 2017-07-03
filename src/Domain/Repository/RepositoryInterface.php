<?php

namespace Warehouse\Domain\Repository;

use Warehouse\Domain\Contract\Entity;
use Warehouse\Domain\Id;

/**
 * Interface RepositoryInterface
 * @package Warehouse\Domain\Repository
 */
interface RepositoryInterface
{
    /**
     * @param Id $id
     * @return Entity
     */
    public function findOne(Id $id): Entity;
}