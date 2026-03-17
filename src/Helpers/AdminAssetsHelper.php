<?php

namespace Leopard\Admin\Helpers;

/**
 * Class AdminAssetsHelper
 *
 * This helper class is responsible for serving AdminLTE assets from the vendor directory.
 * It ensures that only valid asset paths are served and prevents directory traversal attacks.
 */
class AdminAssetsHelper
{
    /**
     * Get the requested admin asset.
     *
     * @param string $path The path to the requested asset relative to the admin assets directory.
     * @return string|bool The contents of the asset file or false if not found.
     */
    public static function getAdminAsset(string $path)
    {
        $base = dirname(__DIR__, 4) . '/vendor/almasaeed2010/adminlte/';
        $file = realpath($base . $path);

        if (!$file || !str_starts_with($file, realpath($base))) {
            return false;
        }

        if (!file_exists($file)) {
            return false;
        }

        // Prefer extension-based MIME mapping (more reliable for assets), fallback to mime_content_type
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $map = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'map' => 'application/json',
        ];

        $mime = $map[$ext] ?? mime_content_type($file) ?: 'application/octet-stream';

        return [
            'content' => file_get_contents($file),
            'mime' => $mime,
        ];
    }
}
