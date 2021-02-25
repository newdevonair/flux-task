<?php

namespace Core;

abstract class AbstractController
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    protected function render(string $file_name, array $arguments = []): string
    {

    }
}
