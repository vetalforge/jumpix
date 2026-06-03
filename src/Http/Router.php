<?php

namespace Jumpix\Http;

class Router
{
    private $routes;

    public function __construct($routes = [])
    {
        $this->routes = $routes;
    }

    public function getActionData($uri)
    {
        return array_key_exists($uri, $this->routes) ? $this->routes[$uri] : [];
    }
}


