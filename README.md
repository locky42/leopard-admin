# Leopard Admin Module

`locky42/leopard-admin` provides admin-domain models, mappings, and controllers on top of `leopard-user`.

## Features

- Base admin models:
	- `Leopard\Admin\Models\BaseAdminUser`
	- `Leopard\Admin\Models\BaseAdminRole`
	- `Leopard\Admin\Models\BaseAdminPermission`
- Contract-based model mapping for admin entities
- Doctrine bootstrap integration (`ResolveTargetEntity` + model path registration)
- Admin controllers and templates for admin panel flows

## Requirements

- PHP `^8.3`
- `doctrine/orm ^3`
- `locky42/leopard-user`
- `locky42/leopard-events`

## Contracts

- `Leopard\Admin\Contracts\Models\AdminUserInterface`
- `Leopard\Admin\Contracts\Models\AdminRoleInterface`
- `Leopard\Admin\Contracts\Models\AdminPermissionInterface`

## Doctrine Integration

Package bootstrap (`packages/leopard-admin/bootstrap.php`) does two things:

1. Registers resolve-target mappings before EntityManager metadata resolution:
	 - `AdminUserInterface -> BaseAdminUser`
	 - `AdminRoleInterface -> BaseAdminRole`
	 - `AdminPermissionInterface -> BaseAdminPermission`
2. Adds admin model paths to the current metadata driver via `addPaths([__DIR__ . '/src/Models'])`.

## Quick Start

In application projects, map admin contracts to your app entities (or keep defaults).

```php
use Leopard\Core\Factory\ContractFactory;
use Leopard\Admin\Contracts\Models\AdminUserInterface;
use Leopard\Admin\Contracts\Models\AdminRoleInterface;
use Leopard\Admin\Contracts\Models\AdminPermissionInterface;
use App\Models\Admin\AdminUser;
use App\Models\Admin\AdminRole;
use App\Models\Admin\AdminPermission;

ContractFactory::register(AdminUserInterface::class, AdminUser::class);
ContractFactory::register(AdminRoleInterface::class, AdminRole::class);
ContractFactory::register(AdminPermissionInterface::class, AdminPermission::class);
```

`ContractFactory::register(...)` also synchronizes resolve-target mappings when `leopard-doctrine` is available.

## Controllers

Main controller:

- `Leopard\Admin\Controllers\AdminController`

The controller uses `AuthenticationService` + `AuthorizationService` and resolves concrete model classes through `ResolveTargetEntityRegistry`.

## Routing Requirements

For admin module UI to work, your app must provide routes for these controllers:

- `\Leopard\Admin\Controllers\AdminController`
- `\Leopard\Admin\Controllers\AdminAssetsController`

You can choose one of these integration strategies:

1. Register routes directly to these package controllers.
2. Create your own controllers that extend these classes and route to your subclasses.
3. Implement equivalent controllers in your app with the required functionality.

If routes for admin pages/assets are missing, admin panel screens and static asset endpoints will not be reachable.

## Testing

From repository root:

```bash
vendor/bin/phpunit --testsuite=leopard-admin
```

or

```bash
composer test:admin
```

## License

MIT


