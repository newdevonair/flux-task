<?php

declare(strict_types=1);

require_once "../vendor/autoload.php";
$config = require_once "../config/main-config.php";
$modules_list = $config['modules'];
foreach ($modules_list as $module) {
    $module_config = require_once "../src/modules/{$module}/config/config.module.php";
    $config = array_merge_recursive($config, $module_config);
}
try {
    $application = new Application\MainApplication($config);
    $application->run();
} catch (Throwable $t) {
    var_dump($t);
}
