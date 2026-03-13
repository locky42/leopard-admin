# Leopard Admin Module

`locky42/leopard-admin` is a module for managing the administration area of an application: admin users, roles and permissions, helper traits and tests.

## Requirements

- PHP `>= 8.3`
- `doctrine/orm ^3`

## Overview

The package provides core model implementations for an admin panel:

- `BaseAdminUser` — admin panel users
- `BaseAdminRole` — admin panel roles
- `BaseAdminPermission` — admin panel permissions

Models are implemented by traits (`AdminUserModelTrait`, `AdminRoleModelTrait`, `AdminPermissionModelTrait`) that extend common traits from the `leopard-user` package.

## Contracts

The package exposes contracts (interfaces) so you can swap implementations in your application:

- `Leopard\\Admin\\Contracts\\Models\\AdminUserInterface`
- `Leopard\\Admin\\Contracts\\Models\\AdminRoleInterface`
- `Leopard\\Admin\\Contracts\\Models\\AdminPermissionInterface`

You can register custom implementations using `Leopard\\Core\\Factory\\ContractFactory` when needed.

## Doctrine integration

This package registers its entity models in `packages/leopard-admin/bootstrap.php`. Important: a package should not overwrite the global EntityManager metadata driver. The bootstrap merges package mapping paths/drivers into the existing driver (via `getMetadataDriverImpl()` and `MappingDriverChain`).

Why: if a package calls `setMetadataDriverImpl()` with a new `AttributeDriver` without merging, it may overwrite mappings from other packages and only the last-registered package entities will be discovered (resulting in only that package's tables being created).

Recommended approach when creating a package bootstrap:

1. Retrieve the current driver: `$existing = $config->getMetadataDriverImpl();`.
2. If it's a `MappingDriverChain`, call `addDriver()` to register an `AttributeDriver` for your package paths and model namespace.
3. If it's a single driver, create a `MappingDriverChain`, add the existing driver and your `AttributeDriver`, then call `$config->setMetadataDriverImpl($chain)` once.

The bootstrap for this package also registers a `ResolveTargetEntityListener` to map contracts to concrete implementations (`BaseAdminUser`, `BaseAdminRole`, `BaseAdminPermission`).

## Models and traits

- `AdminUserModelTrait` — adds fields and relations for admin users; extends `UserModelTrait`.
- `AdminRoleModelTrait` — adds relations between roles, permissions and users.
- `AdminPermissionModelTrait` — adds relations between permissions and roles.

Use contracts in your application code instead of concrete classes to keep implementations swappable.

## Testing

The package contains PHPUnit tests under `tests/Models`. You can run tests locally using `phpunit` or the repository helper script `run-tests.sh`.

Examples:

```bash
# Run package tests (from project root)
./run-tests.sh leopard-admin

# Or run phpunit directly (in container or local)
vendor/bin/phpunit --testsuite=leopard-admin
```

The `run-tests.sh` helper accepts an optional argument — a `testsuite` name or a composer `test:*` script.

## What is tested

Tests cover basic model initialization, collection behavior for roles/permissions, adding/removing relations, and `toArray()` methods for core models.

## How to override implementations

To override a contract implementation register your class with `ContractFactory`, e.g.:

```php
use Leopard\\Core\\Factory\\ContractFactory;
use Leopard\\Admin\\Contracts\\Models\\AdminUserInterface;
use MyApp\\Models\\AdminUser;

ContractFactory::register(AdminUserInterface::class, AdminUser::class);
```

Ensure your implementation implements the interface and can be instantiated without required constructor arguments (for factory creation).

## Caveats

- When modifying a package bootstrap, be careful with the metadata driver — do not overwrite it without merging paths.
- If after adding a package you observe only that package's tables are created, check which code overwrites the metadata driver and replace it with the `getMetadataDriverImpl()`/`MappingDriverChain` merging approach.

---

If you want, I can add model code examples or a more detailed breakdown of each trait/method in this file.


