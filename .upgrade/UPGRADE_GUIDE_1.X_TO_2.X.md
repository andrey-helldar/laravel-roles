# Upgrade Guide

#### Upgrading To 2.x From 1.x

## High Impact Changes

#### Database

Tables have been renamed:

| Old Name | New Name |
| --- | --- |
| user_roles | user_role |
| role_permissions | role_permission |


#### Contracts

Contracts `Helldar\Roles\Contracts\Role` and `Helldar\Roles\Contracts\Permission` are deprecated and removed from the package.


#### Exceptions

| Old Name | New Name | Status Code |
| --- | --- | --- |
| `Helldar\Roles\Exceptions\PermissionNotFoundException` | `Helldar\Roles\Exceptions\Core\PermissionNotFoundException` | `500 ` |
| `Helldar\Roles\Exceptions\RoleNotFoundException` | `Helldar\Roles\Exceptions\Core\RoleNotFoundException` | `500 ` |
| `Helldar\Roles\Exceptions\UnknownModelKeyException` | `Helldar\Roles\Exceptions\Core\UnknownModelKeyException` | `500 ` |
| `Helldar\Roles\Exceptions\PermissionAccessIsDeniedException` | `Helldar\Roles\Exceptions\Http\PermissionAccessIsDeniedHttpException` | `403 ` |
| `Helldar\Roles\Exceptions\RoleAccessIsDeniedException` | `Helldar\Roles\Exceptions\Http\RoleAccessIsDeniedHttpException` | `403 ` |


## Low Impact Changes

#### Settings

Remove the `tables` and `models` blocks from the settings.


#### Cache

Default cache time changed from 5 minutes to 1 hour.


### `hasRootRole` method

In the `HasRoles` trait, a method for checking root access for a role `hasRootRole` has been added.

