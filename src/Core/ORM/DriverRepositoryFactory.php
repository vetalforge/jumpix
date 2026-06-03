<?php

namespace Jumpix\Core\ORM;

final class DriverRepositoryFactory implements RepositoryFactoryInterface
{
    private string $driver;
    private $nativeFactory;
    private $doctrineFactory;

    public function __construct(
        string $driver,
        callable $nativeFactory,
        callable $doctrineFactory
    ) {
        $this->driver = $driver;
        $this->nativeFactory = $nativeFactory;
        $this->doctrineFactory = $doctrineFactory;
    }

    public function for(string $class): RepositoryInterface
    {
        $factory = $this->driver === 'doctrine'
            ? ($this->doctrineFactory)()
            : ($this->nativeFactory)();

        return $factory->for($class);
    }
}


