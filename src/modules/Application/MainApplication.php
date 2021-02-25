<?php

declare(strict_types=1);

namespace Application;

use Core\Controller;

class MainApplication
{
    private array $config;
    public static \PDO $adapter;
    private Controller $controller;

    /**
     * MainApplication constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->controller = new Controller($config['controllers']);
        self::$adapter = $this->buildAdapterClass();

    }

    /**
     * @return mixed
     */
    public function run()
    {
        return $this->controller->runAction();
    }

    /**
     * @return \PDO
     */
    private function buildAdapterClass(): \PDO
    {
        $database_config = $this->config['db-config'];
        return new \PDO($database_config['host'], $database_config['username'], $database_config['password']);

    }
}
