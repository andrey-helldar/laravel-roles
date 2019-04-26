# Laravel Basic Roles and Permissions

Basic roles and permissions handling for Laravel 5.5 and up.

![laravel roles](https://user-images.githubusercontent.com/10347617/56795701-ad270f00-6819-11e9-854c-df13a53d1e8c.png)

<p align="center">
    <a href="https://styleci.io/repos/183436706"><img src="https://styleci.io/repos/183436706/shield" alt="StyleCI" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/laravel-roles"><img src="https://img.shields.io/packagist/dt/andrey-helldar/laravel-roles.svg?style=flat-square" alt="Total Downloads" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/laravel-roles"><img src="https://poser.pugx.org/andrey-helldar/laravel-roles/v/stable?format=flat-square" alt="Latest Stable Version" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/laravel-roles"><img src="https://poser.pugx.org/andrey-helldar/laravel-roles/v/unstable?format=flat-square" alt="Latest Unstable Version" /></a>
    <a href="https://travis-ci.org/andrey-helldar/laravel-roles"><img src="https://travis-ci.org/andrey-helldar/laravel-roles.svg?branch=master" alt="Travis CI" /></a>
    <a href="LICENSE"><img src="https://poser.pugx.org/andrey-helldar/laravel-roles/license?format=flat-square" alt="License" /></a>
</p>


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
* [License](#license)


## Installation

To get the latest version of Laravel Roles, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require andrey-helldar/laravel-roles
```

Or manually update `require` block of `composer.json` and run `composer update`.

```json
{
    "require-dev": {
        "andrey-helldar/laravel-roles": "^1.1"
    }
}
```


If you don't use auto-discovery, add the ServiceProvider to the providers array in `app/Providers/AppServiceProvider.php`:

```php
public function register()
{
    if($this->app->environment() !== 'production') {
        $this->app->register(\Helldar\Roles\ServiceProvider::class);
    }
}
```

Copy the package config to your local config with the publish command:
```
php artisan vendor:publish --provider="Helldar\Roles\ServiceProvider"
```

You can create the DB tables by running the migrations:
```
php artisan migrate
```

This command will create such `roles`, `permissions`, `user_roles` and `role_permissions` tables.


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
use Helldar\Roles\Http\Middleware\Permissions;
use Helldar\Roles\Http\Middleware\Roles;

protected $routeMiddleware = [
    // ...
    
    'roles'       => Roles::class,
    'permissions' => Permissions::class,
]
```

Now you can use the rules:
```php
app('router')
    ->middleware('roles:foo,bar', 'permissions:foo,bar')
    ->get(...)
    
app('router')
    ->middleware('roles:foo,bar')
    ->get(...)
    
app('router')
    ->middleware('permissions:foo,bar')
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


@permission('permission_name')
    I can see this text
@endpermission

@permission(auth()->user()->hasPermission('permission_name'))
    I can see this text
@endpermission
```

You can only use blade directives with role/permission id or slug.

Note: use `can()` and `role() / permission()` is enabling separately. See [config](src/config/settings.php) file.


### Checking for permissions

For user:
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
```

For role:
```php
$role = Role::find(1);

// with permission slug:
$role->hasPermission('permission_slug'): bool

// with permission ID:
$role->hasPermission(1): bool

// with permission instance:
$role->hasPermission(Permission::find(1)): bool
```


## License

This package is released under the [MIT License](LICENSE).
