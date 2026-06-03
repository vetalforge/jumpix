<?php

namespace Jumpix\Views;

interface ViewRendererInterface
{
    public function render(string $view, array $data = []): void;
}


