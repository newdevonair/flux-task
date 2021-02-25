<?php

declare(strict_types=1);

namespace Core;

class Controller
{
    private const DEFAULT_ACTION = 'index';
    private const DEFAULT_CONTROLLER = 'Site';

    private array $controller_list;

    public function __construct(array $controller_list)
    {
        $this->controller_list = $controller_list;
    }

    /**
     * @description
     * in this version application do not support parameters passed as url path, instead of query params
     */
    public function runAction()
    {
        $request = $_SERVER['REQUEST_URI'];
        $controller_name = self::DEFAULT_CONTROLLER;
        $action_name = self::DEFAULT_ACTION;
        if (strlen($request) > 1) {
            $path_parts = explode('/', $request);
            $controller_name = ucfirst($path_parts[1]);
            $action_name = $path_parts[2] ?? self::DEFAULT_ACTION;
        }
        $controller_path = $this->getControllerPathNames($action_name);
        $final_controller_path = "{$controller_name}ApplicationController";
        $action_name = "{$controller_path}Action";
        $controller_full_path = $this->controller_list[$final_controller_path];
        $module_name = $this->getModuleNameFromPath($controller_full_path);
        $controller = new $this->controller_list[$final_controller_path]($module_name);
        return $controller->$action_name();
    }

    private function getControllerPathNames(string $action_name): string
    {
         $path_names = explode('-', $action_name);
         $final_path = [];
         foreach ($path_names as $path) {
             $final_path[] = ucfirst($path);
         }

         return implode('', $final_path);
    }

    /**
     * @param string $full_path
     * @return string
     */
     private function getModuleNameFromPath(string $full_path): string
     {
         $path_parts = explode('\\', $full_path);
         return $path_parts[0];
     }
}
