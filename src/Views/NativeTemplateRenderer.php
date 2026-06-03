<?php

namespace Jumpix\Views;

class NativeTemplateRenderer implements ViewRendererInterface
{
    private TemplateEngine $templateEngine;

    public function __construct(TemplateEngine $templateEngine) {
        $this->templateEngine = $templateEngine;
    }

    public function render(string $view, array $data = []): void
    {
        echo $this->templateEngine->render($view, $data);
    }
}


