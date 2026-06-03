<?php

namespace Jumpix\Http;

class Session
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function getValue($key)
    {
        if (key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        } else {
            return false;
        }
    }

    public function setValue($key, $value)
    {
        $_SESSION[$key] = $value;
    }
}


