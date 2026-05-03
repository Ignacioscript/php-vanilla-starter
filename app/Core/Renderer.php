<?php

declare(strict_types=1);

namespace App\Core;

final class Renderer
{
    private array $blocks = [];

    public function __construct(private string $basePath)
    {
    }

    public function render(string $view, array $params = []): string
    {
        $path = rtrim($this->basePath, '/') . '/' . ltrim($view, '/');
        if (!file_exists($path)) {
            throw new \RuntimeException("View not found: {$path}");
        }

        extract($params, EXTR_SKIP);
        ob_start();
        require $path;
        return ob_get_clean() ?: '';
    }

    public function startBlock(string $name): void
    {
        ob_start();
        $this->blocks[$name] = '';
    }

    public function endBlock(string $name): void
    {
        $this->blocks[$name] = ob_get_clean() ?: '';
    }

    public function renderBlock(string $name): void
    {
        echo $this->blocks[$name] ?? '';
    }
}
