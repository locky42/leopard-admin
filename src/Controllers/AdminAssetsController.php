<?php

namespace Leopard\Admin\Controllers;

use Leopard\Admin\Helpers\AdminAssetsHelper;

/**
 * Class AdminAssetsController
 *
 * This controller is responsible for serving static assets for the admin panel.
 * It uses the AdminAssetsHelper to retrieve and serve the requested asset files.
 */
class AdminAssetsController
{
    /**
     * Serve the requested admin asset.
     *
     * @param string $path The path to the requested asset relative to the admin assets directory.
     * @return string|bool
     */
    public function index(string $path)
    {
        $result = AdminAssetsHelper::getAdminAsset($path);
        if ($result === false) {
            http_response_code(404);
            return '';
        }

        // Autonomous behavior: set native header and return content string.
        header('Content-Type: ' . $result['mime']);
        return $result['content'];
    }
}
