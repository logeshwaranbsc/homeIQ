<?php
declare(strict_types=1);

/**
 * HOMEIQ Backup Utility
 * Backs up all JSON databases in storage/
 * Usage: php scripts/backup.php
 */

$baseDir = dirname(__DIR__);
$storageDir = $baseDir . '/storage';
$backupDir = $storageDir . '/backups';

if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

$timestamp = date('Ymd_His');
$targetDir = $backupDir . '/backup_' . $timestamp;
mkdir($targetDir, 0755, true);

$jsonFiles = glob($storageDir . '/*.json');
$count = 0;

foreach ($jsonFiles as $file) {
    $dest = $targetDir . '/' . basename($file);
    if (copy($file, $dest)) {
        $count++;
    }
}

echo "Successfully backed up {$count} JSON databases to: {$targetDir}\n";
echo "To restore: Copy the contents of this folder back into storage/.\n";

