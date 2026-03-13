<?php

namespace Leopard\Admin\Tests;

use Leopard\Admin\Models\BaseAdminPermission;
use Leopard\Admin\Models\BaseAdminRole;
use PHPUnit\Framework\TestCase;

class AdminPermissionTest extends TestCase
{
    private BaseAdminPermission $permission;

    protected function setUp(): void
    {
        $this->permission = new BaseAdminPermission();

        $reflection = new \ReflectionClass($this->permission);
        $idProperty = null;
        if ($reflection->hasProperty('id')) {
            $idProperty = $reflection->getProperty('id');
        } else {
            $parent = $reflection->getParentClass();
            if ($parent && $parent->hasProperty('id')) {
                $idProperty = $parent->getProperty('id');
            }
        }

        if ($idProperty) {
            $idProperty->setAccessible(true);
            $idProperty->setValue($this->permission, 1);
        }
    }

    public function testPermissionInitialization(): void
    {
        $this->assertInstanceOf(BaseAdminPermission::class, $this->permission);
        $this->assertInstanceOf(\DateTime::class, $this->permission->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $this->permission->getUpdatedAt());
    }

    public function testSetAndGetName(): void
    {
        $this->permission->setName('manage_posts');

        $this->assertSame('manage_posts', $this->permission->getName());
    }

    public function testRolesCollectionIsInitialized(): void
    {
        $roles = $this->permission->getRoles();

        $this->assertInstanceOf(\Doctrine\Common\Collections\ArrayCollection::class, $roles);
        $this->assertCount(0, $roles);
    }

    public function testAddRoleAddsBackReference(): void
    {
        $role = new BaseAdminRole();
        $role->setName('editor');

        $this->permission->addRole($role);

        $this->assertTrue($this->permission->getRoles()->contains($role));
        $this->assertTrue($role->getPermissions()->contains($this->permission));
    }

    public function testRemoveRoleRemovesBackReference(): void
    {
        $role = new BaseAdminRole();
        $role->setName('editor');

        $this->permission->addRole($role);
        $this->permission->removeRole($role);

        $this->assertFalse($this->permission->getRoles()->contains($role));
        $this->assertFalse($role->getPermissions()->contains($this->permission));
    }

    public function testToArrayContainsBasePermissionFields(): void
    {
        $this->permission->setName('view_reports');
        $array = $this->permission->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('createdAt', $array);
        $this->assertArrayHasKey('updatedAt', $array);
    }
}
