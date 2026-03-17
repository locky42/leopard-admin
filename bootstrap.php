<?php

use Leopard\Events\EventManager;
use Leopard\Doctrine\EntityManager;
use Leopard\Doctrine\ResolveTargetEntityRegistry;
use Leopard\Doctrine\Events\AfterInitEntityManagerEvent;
use Leopard\Doctrine\Events\BeforeInitEntityManagerEvent;

use Leopard\Admin\Models\BaseAdminUser;
use Leopard\Admin\Models\BaseAdminRole;
use Leopard\Admin\Models\BaseAdminPermission;
use Leopard\Admin\Contracts\Models\AdminUserInterface;
use Leopard\Admin\Contracts\Models\AdminRoleInterface;
use Leopard\Admin\Contracts\Models\AdminPermissionInterface;

EventManager::addEvent(new BeforeInitEntityManagerEvent(), function () {
    ResolveTargetEntityRegistry::addResolveTargetEntity(
        AdminRoleInterface::class,
        BaseAdminRole::class,
        []
    );
    
    ResolveTargetEntityRegistry::addResolveTargetEntity(
        AdminPermissionInterface::class,
        BaseAdminPermission::class,
        []
    );

    ResolveTargetEntityRegistry::addResolveTargetEntity(
        AdminUserInterface::class,
        BaseAdminUser::class,
        []
    );
});

EventManager::addEvent(new AfterInitEntityManagerEvent(), function () {
    $entityManager = EntityManager::getEntityManager();
    $config = $entityManager->getConfiguration();
    $paths = [ __DIR__ . '/src/Models' ];

    /** @var Doctrine\ORM\Mapping\Driver\AttributeDriver $existingDriver */
    $existingDriver = $config->getMetadataDriverImpl();
    $existingDriver->addPaths($paths);
});
