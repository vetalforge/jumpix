<?php

namespace Jumpix\Http;

class Request
{
    private $domain_sym;

    private $domain_addition;

    public function __construct($domain_sym, $domain_addition)
    {
        $this->domain_sym = $domain_sym;
        $this->domain_addition = $domain_addition;
    }

    public function getUri()
    {
        $request_uri = $_SERVER['REQUEST_URI'];

        if (!$this->domain_sym) {
            $request_uri = str_replace($this->domain_addition, '', $request_uri);
        }

        return $request_uri;
    }

    public static function post($key)
    {
        return !empty($_POST[$key]) ? $_POST[$key] : false;
    }

    public static function get($key)
    {
        return !empty($_GET[$key]) ? $_GET[$key] : false;
    }
}


