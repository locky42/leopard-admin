<?php

namespace Leopard\Admin\Traits;

use Doctrine\ORM\Mapping as ORM;
use Leopard\User\Traits\PermissionModelTrait;
use Leopard\Admin\Contracts\Models\AdminRoleInterface;

/**
 * Trait AdminPermissionModelTrait
 *
 * This trait can be used in the AdminPermission model to establish a many-to-many relationship with AdminRole.
 */
trait AdminPermissionModelTrait
{
    use PermissionModelTrait;

    #[ORM\ManyToMany(targetEntity: AdminRoleInterface::class, mappedBy: "adminPermissions")]
    protected $roles;
}
