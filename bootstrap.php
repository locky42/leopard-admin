<?php

use Leopard\Doctrine\EntityManager;
use Leopard\Events\EventManager;
use Leopard\Doctrine\Events\InitEntityManagerEvent;
use Doctrine\ORM\Tools\ResolveTargetEntityListener;
use Doctrine\ORM\Events;

EventManager::addEvent(new InitEntityManagerEvent(), function () {
    $entityManager = EntityManager::getEntityManager();
    $config = $entityManager->getConfiguration();
    $paths = [ __DIR__ . '/src/Models' ];

    /** @var Doctrine\ORM\Mapping\Driver\AttributeDriver $existingDriver */
    $existingDriver = $config->getMetadataDriverImpl();
    $existingDriver->addPaths($paths);

    $evm = $entityManager->getEventManager();
    $resolveTargetEntityListener = new ResolveTargetEntityListener();

    $resolveTargetEntityListener->addResolveTargetEntity(
        \Leopard\Admin\Contracts\Models\AdminUserInterface::class,
        \Leopard\Admin\Models\BaseAdminUser::class, []
    );

    $resolveTargetEntityListener->addResolveTargetEntity(
        \Leopard\Admin\Contracts\Models\AdminRoleInterface::class,
        \Leopard\Admin\Models\BaseAdminRole::class, []
    );

    $resolveTargetEntityListener->addResolveTargetEntity(
        \Leopard\Admin\Contracts\Models\AdminPermissionInterface::class,
        \Leopard\Admin\Models\BaseAdminPermission::class, []
    );

    $evm->addEventListener(
        [Events::loadClassMetadata],
        $resolveTargetEntityListener
    );
});
