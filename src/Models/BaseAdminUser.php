<?php

namespace Leopard\Admin\Models;

use Doctrine\ORM\Mapping as ORM;
use Leopard\Admin\Traits\AdminUserModelTrait;
use Leopard\Admin\Contracts\Models\AdminUserInterface;

#[ORM\Entity]
#[ORM\Table(name: "admin_users")]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
class BaseAdminUser implements AdminUserInterface
{
    use AdminUserModelTrait;
}
