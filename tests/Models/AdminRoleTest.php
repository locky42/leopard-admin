<?php

namespace Leopard\Admin\Tests;

use Doctrine\Common\Collections\ArrayCollection;
use Leopard\Admin\Models\BaseAdminRole;
use PHPUnit\Framework\TestCase;

class AdminRoleTest extends TestCase
{
    private BaseAdminRole $adminRole;

    protected function setUp(): void
    {
        $this->adminRole = new BaseAdminRole();

        $reflection = new \ReflectionClass($this->adminRole);
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
            $idProperty->setValue($this->adminRole, 1);
        }
    }

    public function testAdminRoleInitialization(): void
    {
        $this->assertInstanceOf(BaseAdminRole::class, $this->adminRole);
        $this->assertInstanceOf(\DateTime::class, $this->adminRole->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $this->adminRole->getUpdatedAt());
    }

    public function testSetAndGetName(): void
    {
        $this->adminRole->setName('content_admin');

        $this->assertSame('content_admin', $this->adminRole->getName());
    }

    public function testUsersCollectionIsInitialized(): void
    {
        $users = $this->adminRole->getUsers();

        $this->assertInstanceOf(ArrayCollection::class, $users);
        $this->assertCount(0, $users);
    }

    public function testPermissionsCollectionIsInitialized(): void
    {
        $permissions = $this->adminRole->getPermissions();

        $this->assertInstanceOf(ArrayCollection::class, $permissions);
        $this->assertCount(0, $permissions);
    }

    public function testToArrayContainsBaseRoleFields(): void
    {
        $this->adminRole->setName('ops_admin');
        $array = $this->adminRole->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('createdAt', $array);
        $this->assertArrayHasKey('updatedAt', $array);
    }
}
