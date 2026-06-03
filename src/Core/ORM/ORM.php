<?php

namespace Jumpix\Core\ORM;

final class ORM
{
    private RepositoryFactoryInterface $repositories;
    private string $driver;

    public function __construct(
        RepositoryFactoryInterface $repositories,
        string $driver
    ) {
        $this->repositories = $repositories;
        $this->driver = $driver;
    }

    public function driver(): string
    {
        return $this->driver;
    }

    public function isNative(): bool
    {
        return $this->driver === 'native';
    }

    public function isDoctrine(): bool
    {
        return $this->driver === 'doctrine';
    }

    public function repository(string $class): RepositoryInterface
    {
        return $this->repositories->for($class);
    }
}


