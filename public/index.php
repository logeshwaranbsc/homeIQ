<?php
declare(strict_types=1);

/**
 * HOMEIQ E-Commerce — Front Controller
 */

$baseDir = dirname(__DIR__);

require_once $baseDir . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'App.php';

use App\Core\App;

App::init($baseDir);

require_once $baseDir . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'routes.php';

App::run();

