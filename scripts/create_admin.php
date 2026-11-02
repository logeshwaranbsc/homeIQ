<?php
declare(strict_types=1);

/**
 * HOMEIQ CLI Admin Creator & Password Reset Tool
 * Usage: php scripts/create_admin.php <username> <email> <password> [role]
 */

$baseDir = dirname(__DIR__);
require_once $baseDir . '/app/Core/App.php';

use App\Core\App;
use App\Repositories\AdminRepository;

App::init($baseDir);

$username = $argv[1] ?? null;
$email = $argv[2] ?? null;
$password = $argv[3] ?? null;
$role = $argv[4] ?? 'Super Admin';

if (!$username || !$email || !$password) {
    echo "Usage: php scripts/create_admin.php <username> <email> <password> [role]\n";
    echo "Example: php scripts/create_admin.php john john@homeiq.com SecretPassword123! 'Product Manager'\n";
    exit(1);
}

$adminRepo = new AdminRepository();
$existing = $adminRepo->findByUsername($username) ?? $adminRepo->findByEmail($email);

if ($existing) {
    echo "Admin already exists. Updating password and role...\n";
    $adminRepo->update($existing['id'], [
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'role' => $role,
        'is_active' => true,
    ]);
    echo "Password and role updated successfully for admin '{$username}'.\n";
    exit(0);
}

$created = $adminRepo->create([
    'name' => ucfirst($username),
    'username' => mb_strtolower($username),
    'email' => mb_strtolower($email),
    'role' => $role,
    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    'is_active' => true,
]);

echo "Created new administrator '{$created['username']}' with role '{$created['role']}'.\n";

