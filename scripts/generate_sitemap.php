<?php
declare(strict_types=1);

$baseDir = dirname(__DIR__);
require_once $baseDir . '/app/Core/App.php';

use App\Core\App;
use App\Controllers\Admin\AdminSeoController;

App::init($baseDir);

$seo = new AdminSeoController();
$seo->generateSitemap();
$seo->generateRobotsTxt();

echo "sitemap.xml and robots.txt generated successfully.\n";

