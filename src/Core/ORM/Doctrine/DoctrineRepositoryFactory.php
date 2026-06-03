<?php

namespace Jumpix\Core\ORM\Doctrine;

use Jumpix\Core\ORM\RepositoryFactoryInterface;
use Jumpix\Core\ORM\RepositoryInterface;

final class DoctrineRepositoryFactory implements RepositoryFactoryInterface
{
    private object $entityManager;

    public function __construct(object $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function for(string $class): RepositoryInterface
    {
        return new DoctrineRepository($this->entityManager, $class);
    }
}


