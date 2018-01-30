<?php
if (PHP_SAPI == 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';
$dotenv = new Dotenv\Dotenv(__DIR__ . '/../');
$dotenv->load();
//session_start();


// Instantiate the app
$settings = require __DIR__ . '/../app/config/settings.php';
$app      = new \Slim\App($settings);
use think\Db;
$dbconfig = $settings['settings']['db'];
Db::setConfig($dbconfig);

// Set up dependencies
require __DIR__ . '/../app/config/dependencies.php';

// Register middleware
require __DIR__ . '/../app/config/middleware.php';

// Register routes
require __DIR__ . '/../app/config/routes.php';

// Run app
$app->run();

