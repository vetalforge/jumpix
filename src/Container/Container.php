<?php

namespace Jumpix\Container;

use Psr\Container\ContainerInterface;
use Jumpix\Container\Exceptions\NotFoundException;

final class Container implements ContainerInterface
{
    private static ?self $instance = null;
    private array $dependencies;
    private array $instances = [];

    public function __construct($deps = [])
    {
        $this->dependencies = $deps;
    }

    public static function instance($deps = [])
    {
        if (self::$instance === null) {
            self::$instance = new self($deps);
        }

        return self::$instance;
    }

    public function has(string $id): bool
    {
        return isset($this->dependencies[$id]);
    }

    public function get(string $id)
    {
        if ($this->has($id)) {
            return call_user_func($this->dependencies[$id], $this);
        }

        throw NotFoundException::create($id);
    }

    public function getSingleton(string $id)
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        $this->instances[$id] = $this->get($id);

        return $this->instances[$id];
    }
}


