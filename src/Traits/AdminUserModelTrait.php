<?php

namespace Leopard\Admin\Traits;

use Doctrine\ORM\Mapping as ORM;
use Leopard\Admin\Contracts\Models\AdminRoleInterface;
use Leopard\User\Traits\UserModelTrait;

/**
 * Trait AdminUserModelTrait
 *
 * This trait can be used in the AdminUser model to establish a many-to-many relationship with AdminRole.
 */
trait AdminUserModelTrait
{
    use UserModelTrait;

    #[ORM\ManyToMany(targetEntity: AdminRoleInterface::class, inversedBy: "adminUsers")]
    #[ORM\JoinTable(name: "admin_user_roles")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id", onDelete: "CASCADE")]
    #[ORM\InverseJoinColumn(name: "role_id", referencedColumnName: "id", onDelete: "CASCADE")]
    protected $roles;
}
