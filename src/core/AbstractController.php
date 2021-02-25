<?php

namespace Core;

abstract class AbstractController
{
    private string $current_path;

    /**
     * AbstractController constructor.
     * @param string $current_path
     */
    public function __construct(string $current_path)
    {
        $this->current_path = APPLICATION_DIR .  DIRECTORY_SEPARATOR . "src". DIRECTORY_SEPARATOR . "modules" . DIRECTORY_SEPARATOR ."{$current_path}" . DIRECTORY_SEPARATOR . "view";
    }

    /**
     * @param string $file_name
     * @param array $arguments
     */
    protected function render(string $file_name, array $arguments = []): void
    {
        $output = NULL;
        echo $file_path = "{$this->current_path}" . DIRECTORY_SEPARATOR . "{$file_name}.php";
        if(file_exists($file_path)){
            extract($arguments);
            ob_start();
            require_once $file_path;
            $output = ob_get_clean();
        }
        print $output;
    }
}
