<?php

namespace Jumpix\Core\ORM\Native;

use Jumpix\Core\ORM\RepositoryFactoryInterface;
use Jumpix\Core\ORM\RepositoryInterface;

final class NativeRepositoryFactory implements RepositoryFactoryInterface
{
    public function for(string $class): RepositoryInterface
    {
        return new NativeRepository($class);
    }
}


