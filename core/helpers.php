<?php

use Core\Application;
use Core\Session;

/**
 * Get application instance
 */
function app(): Application
{
    return Application::getInstance();
}

/**
 * Get configuration value
 */
function config(string $key, $default = null)
{
    return app()->config($key, $default);
}

/**
 * Include view
 */
function partial(string $view, array $data = [])
{

    return Application::$app->view->partial($view, $data);
}

/**
 * Generate URL
 */
function url(string $path = ''): string
{
    $baseUrl = $_ENV['APP_URL'] ?? 'http://localhost';
    return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
}


/**
 * Generate asset URL
 */
function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

/**
 * Redirect to URL
 */
function redirect(string $url, int $code = 302): void
{
    app()->getResponse()->redirect($url, $code);
}

/**
 * Get request instance
 */
function request(): Core\Request
{
    return app()->getRequest();
}

/**
 * Get old input value
 */
function old(string $key, $default = null)
{
    return Session::get('old_input.' . $key, $default);
}

/**
 * CSRF token field
 */
function csrf_field(): string
{
    $token = Session::get('csrf_token');
    if (!$token) {
        $token = bin2hex(random_bytes(32));
        Session::set('csrf_token', $token);
    }
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Get CSRF token
 */
function csrf_token(): string
{
    $token = Session::get('csrf_token');
    if (!$token) {
        $token = bin2hex(random_bytes(32));
        Session::set('csrf_token', $token);
    }
    return $token;
}

/**
 * Escape output
 */
function e($value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Dump and die
 */
function dd(...$vars): void
{
    echo '<pre>';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    die();
}

/**
 * Get environment variable
 */
function env(string $key, $default = null)
{
    return $_ENV[$key] ?? $default;
}

/**
 * Check if user is authenticated
 */
function auth(): bool
{
    return Session::has('user_id');
}

/**
 * Get authenticated user ID
 */
function auth_id(): ?int
{
    return Session::get('user_id');
}

/**
 * Handle file upload with optional image resizing and thumbnail creation
 *
 * @param string $fileKey The key in the $_FILES array
 * @param string $uploadDir The directory to upload files to
 * @param int|null $desiredWidth The desired width to resize the image to (optional)
 * @param int $thumbnailWidth The width of the thumbnail to create (default 200px)
 * @return string|null The path to the uploaded file or null if no file was uploaded
 * @throws Exception If the upload fails
 */
function uploadFile($fileKey, $uploadDir = 'public/uploads', $desiredWidth = null, $thumbnailWidth = 200): ?string
{
    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
        return null; // No file uploaded or error
    }

    $file = $_FILES[$fileKey];
    $filename = time() . '_' . preg_replace('/\s+/', '_', basename($file['name']));
    $targetPath = rtrim($uploadDir, '/') . '/' . $filename;

    // Create upload folder if not exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception("Failed to upload file.");
    }

    // If image, process resizing and thumbnail
    $fileType = mime_content_type($targetPath);
    if (strpos($fileType, 'image') !== false) {
        [$origWidth, $origHeight] = getimagesize($targetPath);

        // Crop/resize to desired width if provided
        if ($desiredWidth && $origWidth > $desiredWidth) {
            $ratio = $origHeight / $origWidth;
            $newHeight = intval($desiredWidth * $ratio);

            $resized = imagecreatetruecolor($desiredWidth, $newHeight);

            switch ($fileType) {
                case 'image/jpeg':
                    $source = imagecreatefromjpeg($targetPath);
                    break;
                case 'image/png':
                    $source = imagecreatefrompng($targetPath);
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                    break;
                case 'image/gif':
                    $source = imagecreatefromgif($targetPath);
                    break;
                default:
                    $source = null;
            }

            if ($source) {
                imagecopyresampled($resized, $source, 0, 0, 0, 0, $desiredWidth, $newHeight, $origWidth, $origHeight);
                imagejpeg($resized, $targetPath, 90);
                imagedestroy($resized);
                imagedestroy($source);

                // update dimensions after resize
                $origWidth = $desiredWidth;
                $origHeight = $newHeight;
            }
        }

        // Create thumbnail
        $thumbDir = rtrim($uploadDir, '/') . '/thumbnails';
        if (!is_dir($thumbDir)) {
            mkdir($thumbDir, 0777, true);
        }

        $thumbPath = $thumbDir . '/' . $filename;
        $ratio = $origHeight / $origWidth;
        $thumbHeight = intval($thumbnailWidth * $ratio);

        $thumb = imagecreatetruecolor($thumbnailWidth, $thumbHeight);

        switch ($fileType) {
            case 'image/jpeg':
                $source = imagecreatefromjpeg($targetPath);
                break;
            case 'image/png':
                $source = imagecreatefrompng($targetPath);
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);
                break;
            case 'image/gif':
                $source = imagecreatefromgif($targetPath);
                break;
            default:
                $source = null;
        }

        if ($source) {
            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $thumbnailWidth, $thumbHeight, $origWidth, $origHeight);
            imagejpeg($thumb, $thumbPath, 85);
            imagedestroy($thumb);
            imagedestroy($source);
        }
    }
    return $filename; // store relative path
}

function deleteFile(string $filePath, string $uploadDir = 'public/uploads'): bool
{
    if (!$filePath) {
        return false;
    }

    // Rebuild full path to original file
    $fullPath = __DIR__ . '/../' . rtrim($uploadDir, '/') . '/' . basename($filePath);

    $deleted = false;

    // Delete original file
    if (file_exists($fullPath)) {
        unlink($fullPath);
        $deleted = true;
    }

    // Delete thumbnail
    $thumbPath = __DIR__ . '/../' . rtrim($uploadDir, '/') . '/thumbnails/' . basename($filePath);
    if (file_exists($thumbPath)) {
        unlink($thumbPath);
        $deleted = true;
    }

    return $deleted;
}
