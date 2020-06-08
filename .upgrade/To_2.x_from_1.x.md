# Upgrade Guide

### Upgrading To 2.x From 1.x

## High Impact Changes

### Database

#### Migrations

Before you apply migration, you need to publish them by running the `php artisan vendor:publish --provider="Helldar\Roles\ServiceProvider"` command.

> Note: the change has been introduced since version 2.4.0.

#### Tables have been renamed:

| Old Name | New Name |
| --- | --- |
| user_roles | user_role |
| role_permissions | role_permission |

> Just run the `php artisan migrate` command.

#### Root roles

Added the `is_root` column to the `roles` table.


#### Columns

Removed columns `created_at` and `updated_at` from `roles` and `permissions` tables.

The column `title` is added to the tables of `roles` and `permissions` (starting from version 2.4.0).


### Configuration

The configuration file is renamed to [roles.php](../config/roles.php).


### Contracts

Contracts `Helldar\Roles\Contracts\Role` and `Helldar\Roles\Contracts\Permission` are deprecated and removed from the package.


### Exceptions

| Status Code | Old Name | New Name |
| --- | --- | --- |
| `500 ` | `Helldar\Roles\Exceptions\PermissionNotFoundException` | `Helldar\Roles\Exceptions\Core\PermissionNotFoundException` |
| `500 ` | `Helldar\Roles\Exceptions\RoleNotFoundException` | `Helldar\Roles\Exceptions\Core\RoleNotFoundException` |
| `500 ` | `Helldar\Roles\Exceptions\UnknownModelKeyException` | `Helldar\Roles\Exceptions\Core\UnknownModelKeyException` |
| `403 ` | `Helldar\Roles\Exceptions\PermissionAccessIsDeniedException` | `Helldar\Roles\Exceptions\Http\PermissionAccessIsDeniedHttpException` |
| `403 ` | `Helldar\Roles\Exceptions\RoleAccessIsDeniedException` | `Helldar\Roles\Exceptions\Http\RoleAccessIsDeniedHttpException` |


## Low Impact Changes

### Settings

Remove the `root_roles`, `tables` and `models` blocks from the settings.


### Cache

Default cache time changed from 5 minutes to 1 hour.


### `hasRootRole` method

In the `HasRoles` trait, a method for checking root access for a role `hasRootRole` has been added.

