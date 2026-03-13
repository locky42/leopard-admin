<?php

namespace Leopard\Admin\Traits;

use Doctrine\ORM\Mapping as ORM;
use Leopard\Admin\Contracts\Models\AdminPermissionInterface;
use Leopard\Admin\Contracts\Models\AdminUserInterface;
use Leopard\User\Traits\RoleModelTrait;

/**
 * Trait AdminRoleModelTrait
 *
 * This trait can be used in the AdminRole model to establish relationships with AdminPermission and AdminUser.
 */
trait AdminRoleModelTrait
{
    use RoleModelTrait;

    #[ORM\ManyToMany(targetEntity: AdminPermissionInterface::class, inversedBy: "adminRoles")]
    #[ORM\JoinTable(name: "admin_role_permissions")]
    #[ORM\JoinColumn(name: "role_id", referencedColumnName: "id", onDelete: "CASCADE")]
    #[ORM\InverseJoinColumn(name: "permission_id", referencedColumnName: "id", onDelete: "CASCADE")]
    protected $permissions;

    #[ORM\ManyToMany(targetEntity: AdminUserInterface::class, mappedBy: "adminRoles")]
    protected $users;
}
