<?php
declare(strict_types=1);

/**
 * HOMEIQ — Customer Warranty Registration Entrypoint
 * Standalone entrypoint matching architecture: public/warranty-registration.php
 */

$baseDir = dirname(__DIR__);

require_once $baseDir . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Core' . DIRECTORY_SEPARATOR . 'App.php';

use App\Core\App;
use App\Core\Request;
use App\Controllers\Storefront\WarrantyRegistrationController;

App::init($baseDir);

$controller = new WarrantyRegistrationController();
$request = Request::createFromGlobals();

if ($request->method() === 'POST') {
    // Validate CSRF if applicable
    $csrfToken = $request->input('_csrf');
    if (!verify_csrf((string)$csrfToken)) {
        flash('error', 'Security token expired. Please try submitting again.');
        redirect('warranty-registration');
        exit;
    }
    $controller->submit($request);
} else {
    $controller->index($request);
}
