<?php

namespace Jumpix\Core\ORM;

interface RepositoryFactoryInterface
{
    public function for(string $class): RepositoryInterface;
}


