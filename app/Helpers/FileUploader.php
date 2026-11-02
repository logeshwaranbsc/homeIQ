<?php
declare(strict_types=1);

namespace App\Helpers;

use App\Core\App;

class FileUploader
{
    private static array $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/svg+xml' => 'svg',
    ];

    private static int $maxSizeBytes = 5 * 1024 * 1024; // 5MB

    /**
     * Upload an image file to designated folder
     * @return array [success => bool, filename => string|null, error => string|null]
     */
    public static function upload(array $file, string $subfolder): array
    {
        if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['success' => false, 'filename' => null, 'error' => 'No file was uploaded or file is invalid.'];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'filename' => null, 'error' => 'Upload error code: ' . $file['error']];
        }

        if ($file['size'] > self::$maxSizeBytes) {
            return ['success' => false, 'filename' => null, 'error' => 'File size exceeds 5MB limit.'];
        }

        // Check MIME type
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if (!isset(self::$allowedMimes[$mime])) {
            return ['success' => false, 'filename' => null, 'error' => 'Invalid file format. Allowed formats: JPG, PNG, WEBP, SVG.'];
        }

        $extension = self::$allowedMimes[$mime];
        
        // Prevent subfolder path traversal
        $cleanSubfolder = trim(str_replace(['..', '/', '\\'], '', $subfolder));
        $targetDir = App::getBasePath() . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $cleanSubfolder;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Generate safe random filename
        $newFilename = bin2hex(random_bytes(10)) . '_' . time() . '.' . $extension;
        $destination = $targetDir . DIRECTORY_SEPARATOR . $newFilename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => false, 'filename' => null, 'error' => 'Failed to save uploaded file to disk.'];
        }

        return [
            'success' => true,
            'filename' => $cleanSubfolder . '/' . $newFilename,
            'error' => null,
        ];
    }

    /**
     * Delete an uploaded file safely
     */
    public static function delete(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        // Prevent path traversal
        if (str_contains($path, '..')) {
            return false;
        }

        $fullPath = App::getBasePath() . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . ltrim($path, '/\\');
        if (file_exists($fullPath) && is_file($fullPath)) {
            return @unlink($fullPath);
        }

        return false;
    }
}

