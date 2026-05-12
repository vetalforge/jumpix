<?php

namespace app\Container;

use Psr\Container\ContainerInterface;
use app\Container\Exceptions\NotFoundException;

final class Container implements ContainerInterface
{
    private static $instance;

    private $dependencies;

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
        } else {
            throw NotFoundException::create($id);
        }
    }
}