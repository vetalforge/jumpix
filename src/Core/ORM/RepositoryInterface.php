<?php

namespace Jumpix\Core\ORM;

interface RepositoryInterface
{
    public function find($id);

    public function all(): array;

    public function save($entity): void;

    public function delete($entity): void;
}


