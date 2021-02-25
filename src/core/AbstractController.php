<?php

declare(strict_types=1);

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
        $file_path = "{$this->current_path}" . DIRECTORY_SEPARATOR . "{$file_name}.php";
        $output = $this->renderFile($file_path, $arguments);
        $arguments = [
            'content' => $output,
        ];
        $layout_file_name = "{$this->current_path}" . DIRECTORY_SEPARATOR . "layout.php";
        print $this->renderFile($layout_file_name, $arguments);;

    }

    /**
     * @param string $file_name
     * @param array $arguments
     * @return string
     */
    private function renderFile(string $file_name, array $arguments = []): string
    {
        if(file_exists($file_name)){
            extract($arguments);
            ob_start();
            require_once $file_name;
            $output = ob_get_clean();
        }
        return $output;
    }
}
