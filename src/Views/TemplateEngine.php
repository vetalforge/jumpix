<?php

namespace Jumpix\Views;

class TemplateEngine
{
    protected string $viewPath;

    public function __construct(string $viewPath = '../resources/views/')
    {
        $this->viewPath = rtrim($viewPath, '/') . '/';
    }

    public function render(string $view, array $data = []): string
    {
        $file = $this->viewPath . $view . '.php';

        if (!file_exists($file)) {
            throw new \Exception("Template file '{$view}.php' not found in {$this->viewPath}");
        }

        $output = file_get_contents($file);
        $output = $this->parseVariables($output, $data);
        return $output;
    }

    private function parseVariables(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                continue; // We skip the arrays, because they are collected by @foreach
            }
            $template = str_replace("{{ $key }}", htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'), $template);
        }

        $template = $this->parseConditionals($template, $data);
        $template = $this->parseLoops($template, $data);

        return $template;
    }

    private function parseConditionals(string $template, array $data): string
    {
        $template = preg_replace_callback('/@if\((.+?)\)(.*?)@else(.*?)@endif/s', function ($matches) use ($data) {
            $condition = trim($matches[1], '$ ');
            $trueBlock = $matches[2];
            $falseBlock = $matches[3];

            return !empty($data[$condition]) ? $trueBlock : $falseBlock;
        }, $template);

        $template = preg_replace_callback('/@if\((.+?)\)(.*?)@endif/s', function ($matches) use ($data) {
            $condition = trim($matches[1], '$ ');
            return !empty($data[$condition]) ? $matches[2] : '';
        }, $template);

        return $template;
    }

    private function parseLoops(string $template, array $data): string
    {
        return preg_replace_callback(
            '/@foreach\((\$.+?) as \$(.+?)\)(.*?)@endforeach/s',
            function ($matches) use ($data) {
                $arrayName = trim($matches[1], '$ ');
                $itemName = trim($matches[2]);
                $loopContent = $matches[3];

                if (!isset($data[$arrayName]) || !is_array($data[$arrayName])) {
                    return ''; // If the changes are not rendered or they are not massive, nothing is rendered
                }

                $renderedContent = '';
                foreach ($data[$arrayName] as $item) {
                    $renderedContent .= str_replace(
                        "{{ $itemName }}",
                        htmlspecialchars((string)$item, ENT_QUOTES, 'UTF-8'),
                        $loopContent
                    );
                }

                return $renderedContent;
            },
            $template
        );
    }
}


