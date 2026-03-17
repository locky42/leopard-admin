<?php
/**
 * @var string $title
 * @var array $descriptionBlocks
 * @var array $faqList
 */

use App\Controllers\Site\PageController;

global $container;
$router = $container->get('router');
?>
<footer class="site-footer">
    <p>&copy; <?= date('Y') ?> Leopard Framework. All rights reserved.</p>
</footer>
<div class="alert-container" id="alert_container" style="display: none;">
    <div class="alert-header">
    </div>
    <div class="message"></div>
</div>
<a href="<?php echo $router->getRoute(PageController::class, 'donate', true); ?>" id="donate-link" title="Donate">Donate</a>
