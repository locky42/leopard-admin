<?php

namespace Leopard\Admin\Models;

use Doctrine\ORM\Mapping as ORM;
use Leopard\Admin\Contracts\Models\AdminPermissionInterface;
use Leopard\Admin\Traits\AdminPermissionModelTrait;

#[ORM\Entity]
#[ORM\Table(name: "admin_permissions")]
#[ORM\InheritanceType("SINGLE_TABLE")]
class BaseAdminPermission implements AdminPermissionInterface
{
    use AdminPermissionModelTrait;
}
