<?php

use Leopard\Admin\Helpers\AdminAssetsHelper;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Login' ?></title>
    <link rel="stylesheet" href="/assets/admin/dist/css/adminlte.min.css">
</head>

<body class="hold-transition <?php if (!isset($authenticationService) || !$authenticationService->isAuthenticated()) { echo 'login-page'; } ?>">

    <?= $content ?>

    <script src="/assets/admin/plugins/jquery/jquery.min.js"></script>
    <script src="/assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/admin/dist/js/adminlte.min.js"></script>
</body>
</html>
