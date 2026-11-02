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
     * Upload multiple image files to designated folder
     * @return array Array of uploaded file paths [ 'products/abc.png', ... ]
     */
    public static function uploadMultiple(?array $files, string $subfolder): array
    {
        $uploaded = [];
        if (!$files || empty($files['name']) || !is_array($files['name'])) {
            return $uploaded;
        }

        $count = count($files['name']);
        for ($i = 0; $i < $count; $i++) {
            $err = $files['error'][$i] ?? UPLOAD_ERR_NO_FILE;
            $tmp = $files['tmp_name'][$i] ?? '';
            $name = $files['name'][$i] ?? '';

            if ($err === UPLOAD_ERR_NO_FILE || empty($tmp) || empty($name)) {
                continue;
            }

            $singleFile = [
                'name'     => $name,
                'type'     => $files['type'][$i] ?? '',
                'tmp_name' => $tmp,
                'error'    => $err,
                'size'     => $files['size'][$i] ?? 0,
            ];

            $result = self::upload($singleFile, $subfolder);
            if ($result['success'] && !empty($result['filename'])) {
                $uploaded[] = $result['filename'];
            }
        }

        return $uploaded;
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

        // Protect seed/default demonstration files
        $protectedSeeds = [
            'products/accessories-battery.svg',
            'products/accessories-fobs.svg',
            'products/fingerprint-lock.svg',
            'products/homeiq-ai-face-lock.svg',
            'products/mortise-lock.svg',
            'products/palm-vein-lock.svg',
            'products/pin-rfid-lock.svg',
            'products/video-door-lock.svg',
            'products/wifi-smart-lock.svg',
            'banners/hero-banner-main.svg',
            'banners/hero-app-control.svg',
            'banners/hero-fingerprint.svg',
        ];
        if (in_array(ltrim(str_replace('\\', '/', $path), '/'), $protectedSeeds, true)) {
            return false;
        }

        $fullPath = App::getBasePath() . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . ltrim($path, '/\\');
        if (file_exists($fullPath) && is_file($fullPath)) {
            return @unlink($fullPath);
        }

        return false;
    }
}

