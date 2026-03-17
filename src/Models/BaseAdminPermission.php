<?php

namespace Leopard\Admin\Models;

use Doctrine\ORM\Mapping as ORM;
use Leopard\Admin\Traits\AdminPermissionModelTrait;
use Leopard\Admin\Contracts\Models\AdminPermissionInterface;

#[ORM\Entity]
#[ORM\Table(name: "admin_permissions")]
#[ORM\InheritanceType("SINGLE_TABLE")]
class BaseAdminPermission implements AdminPermissionInterface
{
    use AdminPermissionModelTrait;
}
