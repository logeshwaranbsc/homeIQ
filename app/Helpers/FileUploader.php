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

    private static array $allowedVideoMimes = [
        'video/mp4'        => 'mp4',
        'video/webm'       => 'webm',
        'video/ogg'        => 'ogv',
        'video/quicktime'  => 'mov',
        'video/x-matroska' => 'mkv',
        'video/3gpp'       => '3gp',
        'video/avi'        => 'avi',
        'video/msvideo'    => 'avi',
        'video/x-msvideo'  => 'avi',
    ];

    private static array $allowedVideoExts = [
        'mp4', 'webm', 'ogv', 'ogg', 'mov', 'mkv', '3gp', 'avi'
    ];

    private static int $maxSizeBytes = 500 * 1024 * 1024; // 500MB

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
            return ['success' => false, 'filename' => null, 'error' => 'File size exceeds 500MB limit.'];
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

        // Mirror to root uploads folder for environments routing from root or public
        $rootTargetDir = App::getBasePath() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $cleanSubfolder;
        if (!is_dir($rootTargetDir)) {
            @mkdir($rootTargetDir, 0755, true);
        }
        @copy($destination, $rootTargetDir . DIRECTORY_SEPARATOR . $newFilename);

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
     * Upload a video file to designated folder without size restriction
     * @return array [success => bool, filename => string|null, error => string|null]
     */
    public static function uploadVideo(array $file, string $subfolder = 'video_testimonials', int $maxSizeBytes = 0): array
    {
        if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['success' => false, 'filename' => null, 'error' => 'No video file was uploaded or file is invalid.'];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $msg = match ($file['error']) {
                UPLOAD_ERR_INI_SIZE => 'The video file exceeds the server maximum upload limit (' . ini_get('upload_max_filesize') . ').',
                UPLOAD_ERR_FORM_SIZE => 'The video file exceeds the HTML form MAX_FILE_SIZE limit.',
                UPLOAD_ERR_PARTIAL => 'The video file was only partially uploaded.',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                default => 'Video upload error code: ' . $file['error'],
            };
            return ['success' => false, 'filename' => null, 'error' => $msg];
        }

        // Only enforce a limit if explicitly specified greater than 0 (no artificial limit by default)
        if ($maxSizeBytes > 0 && $file['size'] > $maxSizeBytes) {
            $mb = round($maxSizeBytes / (1024 * 1024));
            return ['success' => false, 'filename' => null, 'error' => "Video file size exceeds {$mb}MB limit."];
        }

        // Check MIME type
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        $clientExt = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));

        $extension = null;
        if (isset(self::$allowedVideoMimes[$mime])) {
            $extension = self::$allowedVideoMimes[$mime];
        } elseif (in_array($clientExt, self::$allowedVideoExts, true)) {
            // Some Windows systems report application/octet-stream for mp4/webm
            $extension = $clientExt;
        } else {
            return ['success' => false, 'filename' => null, 'error' => 'Invalid video format. Allowed formats: MP4, WebM, MOV, OGV, MKV.'];
        }

        // Prevent subfolder path traversal
        $cleanSubfolder = trim(str_replace(['..', '/', '\\'], '', $subfolder));
        $targetDir = App::getBasePath() . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $cleanSubfolder;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Generate safe random filename
        $newFilename = 'vid_' . bin2hex(random_bytes(10)) . '_' . time() . '.' . $extension;
        $destination = $targetDir . DIRECTORY_SEPARATOR . $newFilename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => false, 'filename' => null, 'error' => 'Failed to save uploaded video to disk.'];
        }

        // Mirror to root uploads folder for environments routing from root or public
        $rootTargetDir = App::getBasePath() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $cleanSubfolder;
        if (!is_dir($rootTargetDir)) {
            @mkdir($rootTargetDir, 0755, true);
        }
        @copy($destination, $rootTargetDir . DIRECTORY_SEPARATOR . $newFilename);

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

        // Protect seed/default demonstration files
        $protectedSeeds = [
            'products/fingerprint-lock.svg',
            'products/homeiq-ai-face-lock.svg',
            'products/palm-vein-lock.svg',
            'products/video-door-lock.svg',
        ];
        if (in_array(ltrim(str_replace('\\', '/', $path), '/'), $protectedSeeds, true)) {
            return false;
        }

        $fullPath = App::getBasePath() . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . ltrim($path, '/\\');
        $deleted = false;
        if (file_exists($fullPath) && is_file($fullPath)) {
            $deleted = @unlink($fullPath);
        }

        $rootPath = App::getBasePath() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . ltrim($path, '/\\');
        if (file_exists($rootPath) && is_file($rootPath) && realpath($rootPath) !== realpath($fullPath)) {
            $deleted = @unlink($rootPath) || $deleted;
        }

        return $deleted;

        return false;
    }
}

