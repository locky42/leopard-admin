<?php

namespace Leopard\Admin\Models;

use Doctrine\ORM\Mapping as ORM;
use Leopard\Admin\Traits\AdminRoleModelTrait;
use Leopard\Admin\Contracts\Models\AdminRoleInterface;

#[ORM\Entity]
#[ORM\Table(name: "admin_roles")]
#[ORM\InheritanceType("SINGLE_TABLE")]
class BaseAdminRole implements AdminRoleInterface
{
    use AdminRoleModelTrait;
}
