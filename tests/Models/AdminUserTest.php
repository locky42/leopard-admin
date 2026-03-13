<?php

namespace Leopard\Admin\Tests;

use Leopard\Admin\Models\BaseAdminRole;
use Leopard\Admin\Models\BaseAdminUser;
use PHPUnit\Framework\TestCase;

class AdminUserTest extends TestCase
{
    private BaseAdminUser $adminUser;

    protected function setUp(): void
    {
        $this->adminUser = new BaseAdminUser();

        $reflection = new \ReflectionClass($this->adminUser);
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
            $idProperty->setValue($this->adminUser, 1);
        }
    }

    public function testAdminUserInitialization(): void
    {
        $this->assertInstanceOf(BaseAdminUser::class, $this->adminUser);
        $this->assertInstanceOf(\DateTime::class, $this->adminUser->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $this->adminUser->getUpdatedAt());
        $this->assertTrue($this->adminUser->isActive());
    }

    public function testSetAndGetUsername(): void
    {
        $this->adminUser->setUsername('admin_1');

        $this->assertSame('admin_1', $this->adminUser->getUsername());
    }

    public function testSetAndGetEmail(): void
    {
        $this->adminUser->setEmail('admin@example.com');

        $this->assertSame('admin@example.com', $this->adminUser->getEmail());
    }

    public function testSetAndGetFirstAndLastName(): void
    {
        $this->adminUser->setFirstName('Max');
        $this->adminUser->setLastName('Admin');

        $this->assertSame('Max', $this->adminUser->getFirstName());
        $this->assertSame('Admin', $this->adminUser->getLastName());
    }

    public function testCanUseInheritedRoleMethods(): void
    {
        $role = new BaseAdminRole();
        $role->setName('super_admin');

        $this->adminUser->addRole($role);

        $this->assertTrue($this->adminUser->hasRole('super_admin'));
    }

    public function testToArrayContainsBaseUserFields(): void
    {
        $array = $this->adminUser->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('createdAt', $array);
        $this->assertArrayHasKey('updatedAt', $array);
        $this->assertArrayHasKey('isActive', $array);
    }
}
