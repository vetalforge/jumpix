<?php

namespace Jumpix\Core\ORM\Doctrine;

use Jumpix\Core\ORM\RepositoryInterface;

final class DoctrineRepository implements RepositoryInterface
{
    private object $entityManager;
    private string $entityClass;

    public function __construct(object $entityManager, string $entityClass)
    {
        $this->entityManager = $entityManager;
        $this->entityClass = $entityClass;
    }

    public function find($id)
    {
        return $this->entityManager->find($this->entityClass, $id);
    }

    public function all(): array
    {
        return $this->entityManager
            ->getRepository($this->entityClass)
            ->findAll();
    }

    public function save($entity): void
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function delete($entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}


