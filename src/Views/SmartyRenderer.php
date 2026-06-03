<?php

namespace Jumpix\Views;

use Smarty\Smarty;

class SmartyRenderer implements ViewRendererInterface
{
    private Smarty $smarty;

    public function __construct($smarty) {
        $this->smarty = $smarty;
    }

    public function render(string $view, array $data = []): void
    {
        $this->smarty->assign($data);
        $this->smarty->display($view);
    }
}


