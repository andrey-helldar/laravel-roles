# Laravel Basic Roles and Permissions

Basic roles and permissions handling for Laravel 5.5 and up.

![laravel roles](https://user-images.githubusercontent.com/10347617/56795701-ad270f00-6819-11e9-854c-df13a53d1e8c.png)

[![StyleCI Status][badge_styleci]][link_styleci]
[![Github Workflow Status][badge_build]][link_build]
[![Coverage Status][badge_coverage]][link_scrutinizer]
[![Scrutinizer Code Quality][badge_quality]][link_scrutinizer]
[![For Laravel][badge_laravel]][link_packagist]

[![Stable Version][badge_stable]][link_packagist]
[![Unstable Version][badge_unstable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![License][badge_license]][link_license]


## Contents

* [Installation](#installation)
* [Using](#using)
    * [User model](#user-model)
    * [Middleware](#middleware)
    * [Creating](#creating)
    * [Assign, revoke and sync permissions](#assign-revoke-and-sync-permissions)
        * [Assign permissions](#assign-permissions)
        * [Revoke permissions](#revoke-permissions)
        * [Syncing permissions](#syncing-permissions)
    * [Blade](#blade)
    * [Checking for permissions](#checking-for-permissions)
        * [Checking the root role for user](#checking-the-root-role-for-user)
        * [Checking one role for a user](#checking-one-role-for-a-user)
        * [Checking multiple roles for a user:](#checking-multiple-roles-for-a-user)
        * [Check permissions for one role](#check-permissions-for-one-role)
        * [Checking permissions for multiple roles](#checking-permissions-for-multiple-roles)
    * [Artisan commands](#artisan-commands)
* [License](#license)


## Installation

> If you upgrade from the old version, you can see the list of changes in the Upgrade Guide:
> * [Upgrading To 2.x From 1.x](.upgrade/UPGRADE_GUIDE_1.X_TO_2.X.md)

To get the latest version of `Laravel Roles`, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require andrey-helldar/laravel-roles
```

Or manually update `require` block of `composer.json` and run `composer update`.

```json
{
    "require-dev": {
        "andrey-helldar/laravel-roles": "^2.0"
    }
}
```


You can also publish the config file to change implementations (ie. interface to specific class):
```
php artisan vendor:publish --provider="Helldar\Roles\ServiceProvider"
```

You can create the DB tables by running the migrations:
```
php artisan migrate
```

This command will create such `roles`, `permissions`, `user_role` and `role_permission` tables.


## Using

### User model

First, add the `Helldar\Roles\Traits\HasRoles` trait to your `User` model:

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Helldar\Roles\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    // ...
}
```


### Middleware

You can add middlewares in `$routeMiddleware` of `app/Http/Kernel.php` file:
```php
use Helldar\Roles\Http\Middleware\Permission;
use Helldar\Roles\Http\Middleware\Permissions;
use Helldar\Roles\Http\Middleware\Role;
use Helldar\Roles\Http\Middleware\Roles;

protected $routeMiddleware = [
    // ...
    
    'role'        => Role::class,        // Checks for the entry of one of the specified permissions.
    'roles'       => Roles::class,       // Checks the entry of all of the specified permissions.
    'permission'  => Permission::class,  // Checks for the occurrence of one of the specified roles.
    'permissions' => Permissions::class, // Checks the entry of all of the specified roles.
]
```

Now you can check if one of the conditions is met:
```php
// Example, user has been a `foo` role and a `baz` permission

// success access
app('router')
    ->middleware('role:foo,bar', 'permission:foo,bar')
    ->get(...)

// success access
app('router')
    ->middleware('role:foo,bar')
    ->get(...)

// failed access
app('router')
    ->middleware('permission:foo,bar')
    ->get(...)
```

Or check the entry of all conditions:
```php
// Example, user has been a `foo` role and a `baz` permission

// failed access
app('router')
    ->middleware('roles:foo,bar', 'permissions:foo,bar')
    ->get(...)

// failed access
app('router')
    ->middleware('roles:foo,bar')
    ->get(...)

// success access
app('router')
    ->middleware('roles:foo')
    ->get(...)

// failed access
app('router')
    ->middleware('permissions:foo,bar')
    ->get(...)

// success access
app('router')
    ->middleware('permissions:baz')
    ->get(...)
```


### Creating

```php
use Helldar\Roles\Models\Role;
use Helldar\Roles\Models\Permission;

$role = Role::create(['name' => 'admin']);
$permission = Permission::create(['name' => 'update']);

$role->assignPermission($permission);

// or

$user = User::find(1);

$role = $user->createRole('Mega Admin'); // creating Role instance with "mega_admin" name.

$role->createPermission('Post edit'); // creating Permission instance with "post_edit" name.
```


### Assign, revoke and sync permissions

This package allows for users to be associated with permissions and roles. Every role is associated with multiple permissions. A `Role` and a `Permission` are regular Eloquent models.


#### Assign permissions

To add roles and permissions, use the following methods:

```php
use \Helldar\Roles\Models\Role;

// For User
$user->assignRole('role_name');
$user->assignRole(Role::find(1));
$user->assignRole(1);

$user->assignRoles($role_1, 'role_name_2', 3, ...);


// For Role
use \Helldar\Roles\Models\Permission;

$role->assignPermission('permission_name');
$role->assignPermission(Permission::find(1));
$role->assignPermission(1);

$role->assignPermissions($permission_1, 'permission_2', 3, ...);


// For Permission
use \Helldar\Roles\Models\Role;

$permission->assignRole('role_name');
$permission->assignRole(Role::find(1));
$permission->assignRole(1);

$permission->assignRoles($role_1, 'role_2', 3, ...);
```


#### Revoke permissions

To revoke roles and permissions, use the following methods:

```php
use \Helldar\Roles\Models\Role;

// For User
$user->revokeRole('role_name');
$user->revokeRole(Role::find(1));
$user->revokeRole(1);

$user->revokeRoles($role_1, 'role_name_2', 3, ...);


// For Role
use \Helldar\Roles\Models\Permission;

$role->revokePermission('permission_name');
$role->revokePermission(Permission::find(1));
$role->revokePermission(1);

$role->revokePermissions($permission_1, 'permission_2', 3, ...);


// For Permission
use \Helldar\Roles\Models\Role;

$permission->revokeRole('role_name');
$permission->revokeRole(Role::find(1));
$permission->revokeRole(1);

$permission->revokeRoles($role_1, 'role_2', 3, ...);
```


#### Syncing permissions

To synchronization roles and permissions, use the following methods:

```php
// For User
$user->syncRoles([1, 2, 3, ...]);


// For Role
$role->syncPermissions([1, 2, 3, ...]);


// For Permission
$permission->syncRoles([1, 2, 3, ...]);
```


### Blade

If you enabled the use of directives in the [config](src/config/settings.php) file, you can still using `can()` blade directive with additional `role()` and `permission()` directives:

```php
@can('permission_name')
    I can see this text
@endcan

@if(auth()->user()->can('permission_name'))
    I can see this text
@endif


@role('role_name')
    I can see this text
@endrole

@role(auth()->user()->hasRole('role_name'))
    I can see this text
@endrole


@roles('role_name_1', 'role_name_2', 'role_name_3')
    I can see this text
@endroles

@roles(auth()->user()->hasRole('role_name'))
    I can see this text
@endroles


@permission('permission_name')
    I can see this text
@endpermission

@permission(auth()->user()->hasPermission('permission_name'))
    I can see this text
@endpermission


@permissions('permission_name_1', 'permission_name_2', 'permission_name_3')
    I can see this text
@endpermissions

@permissions(auth()->user()->hasPermission('permission_name'))
    I can see this text
@endpermissions
```

You can only use blade directives with role/permission id or slug.

Note: use `@can()`, `@role()`, `@roles()`, `@permission()` and `@permissions()` directives is enabling separately. See [config](src/config/settings.php) file.


### Checking for permissions

#### Checking the `root` role for user:
```php
$user = User::find(1);

// Checks if the user has at least one role with root access:
$user->hasRootRole(): bool
```

#### Checking one role for a user:
```php
$user = User::find(1);

// with role slug:
$user->hasRole('role_slug'): bool

// with role ID:
$user->hasRole(1): bool

// with role instance:
$user->hasRole(Role::find(1)): bool

// with permission slug:
$user->hasPermission('permission_slug'): bool

// with permission instance:
$user->hasPermission(Permission::find(1)): bool

// If the `use_can_directive` option is set to true in the settings,
// then you can also check permissions through the `can` directive:
auth()->user()->can('permission_slug'): bool
```

#### Checking multiple roles for a user:
```php
$user = User::find(1);

// with role slug:
$user->hasRoles('role_slug_1', 'role_slug_2'): bool

// with role slug as array:
$user->hasRoles(['role_slug_1', 'role_slug_2']): bool

// with role ID:
$user->hasRoles(1, 2, 3): bool

// with role instance:
$user->hasRoles(Role::find(1), Role::find(2)): bool

// with permission slug:
$user->hasPermissions('permission_slug_1', 'permission_slug_2'): bool

// with permission slug as array:
$user->hasPermissions(['permission_slug_1', 'permission_slug_2']): bool

// with permission instance:
$user->hasPermissions(Permission::find(1), Permission::find(2)): bool
```

#### Check permissions for one role:
```php
$role = Role::find(1);

// with permission slug:
$role->hasPermission('permission_slug'): bool

// with permission ID:
$role->hasPermission(1): bool

// with permission instance:
$role->hasPermission(Permission::find(1)): bool

// If the `use_can_directive` option is set to true in the settings,
// then you can also check permissions through the `can` directive:
auth()->user()->can('permission_slug'): bool
```

#### Checking permissions for multiple roles:
```php
$role = Role::find(1);

// with permission slug:
$role->hasPermissions('permission_slug_1', 'permission_slug_1'): bool

// with permission slug as array:
$role->hasPermissions(['permission_slug_1', 'permission_slug_1']): bool

// with permission ID:
$role->hasPermissions(1, 2, 3): bool

// with permission ID as array:
$role->hasPermissions([1, 2, 3]): bool

// with permission instance:
$role->hasPermissions(Permission::find(1), Permission::find(2)): bool

// with permission instance as array:
$role->hasPermissions([Permission::find(1), Permission::find(2)]): bool
```


### Artisan commands

You can create/delete a role or a permission from a console with artisan commands:

```
php artisan acl:role-create {name}
php artisan acl:role-delete {id|ID or role name}

php artisan acl:permission-create {name}
php artisan acl:permission-delete {id|ID or permission name}
```

You can also invoke the creation of roles and permissions from your application:
```php
Artisan::call('acl:role-create', ['name' => $name]);
Artisan::call('acl:role-delete', ['name' => $name]);

Artisan::call('acl:permission-create', ['name' => $name]);
Artisan::call('acl:permission-delete', ['name' => $name]);
```


## License

This package is released under the [MIT License](LICENSE).


[badge_styleci]:        https://styleci.io/repos/183436706/shield
[badge_build]:          https://img.shields.io/github/workflow/status/andrey-helldar/laravel-roles/phpunit?style=flat-square
[badge_coverage]:       https://img.shields.io/scrutinizer/coverage/g/andrey-helldar/laravel-roles.svg?style=flat-square
[badge_laravel]:        https://img.shields.io/badge/Laravel-5.5+%20%7C%206.x%20%7C%207.x%20%7C%208.x-orange.svg?style=flat-square
[badge_stable]:         https://img.shields.io/github/v/release/andrey-helldar/laravel-roles?label=stable&style=flat-square
[badge_unstable]:       https://img.shields.io/badge/unstable-dev--master-orange?style=flat-square
[badge_downloads]:      https://img.shields.io/packagist/dt/andrey-helldar/laravel-roles.svg?style=flat-square
[badge_license]:        https://img.shields.io/packagist/l/andrey-helldar/laravel-roles.svg?style=flat-square

[link_styleci]:         https://github.styleci.io/repos/183436706
[link_build]:           https://github.com/andrey-helldar/laravel-roles/actions
[link_scrutinizer]:     https://scrutinizer-ci.com/g/andrey-helldar/laravel-roles/?branch=master
[link_packagist]:       https://packagist.org/packages/andrey-helldar/laravel-roles
[link_license]:         LICENSE
