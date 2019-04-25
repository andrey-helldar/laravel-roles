# Laravel Basic Roles and Permissions

Basic roles and permissions handling for Laravel 5.5 and up.

<p align="center">
    <a href="https://styleci.io/repos/183436706"><img src="https://styleci.io/repos/183436706/shield" alt="StyleCI" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/laravel-roles"><img src="https://img.shields.io/packagist/dt/andrey-helldar/laravel-roles.svg?style=flat-square" alt="Total Downloads" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/laravel-roles"><img src="https://poser.pugx.org/andrey-helldar/laravel-roles/v/stable?format=flat-square" alt="Latest Stable Version" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/laravel-roles"><img src="https://poser.pugx.org/andrey-helldar/laravel-roles/v/unstable?format=flat-square" alt="Latest Unstable Version" /></a>
    <a href="LICENSE"><img src="https://poser.pugx.org/andrey-helldar/laravel-roles/license?format=flat-square" alt="License" /></a>
</p>


## Contents

* [Installation](#installation)
* [Usage](#usage)


## Installation

To get the latest version of Laravel Roles, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require andrey-helldar/laravel-roles
```

Or manually update `require` block of `composer.json` and run `composer update`.

```json
{
    "require-dev": {
        "andrey-helldar/laravel-roles": "^1.0"
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

You can create the DB tables by running the migrations:
```
php artisan migrate
```

This command will create such `roles`, `permissions`, `user_roles` and `role_permissions` tables.

Next, you can add middlewares in `$routeMiddleware` of `app/Http/Kernel.php`:
```php
use Helldar\Roles\Http\Middleware\Permissions;
use Helldar\Roles\Http\Middleware\Roles;

protected $routeMiddleware = [
    // ...
    
    'roles'       => Roles::class,
    'permissions' => Permissions::class,
]
```

Then you can protect your routes using middleware rules:
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


## Usage

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

This package allows for users to be associated with permissions and roles. Every role is associated with multiple permissions. A `Role` and a `Permission` are regular Eloquent models. They require a name and can be created like this:

```php
use Helldar\Roles\Models\Role;
use Helldar\Roles\Models\Permission;

$role = Role::create(['name' => 'admin']);
$permission = Permission::create(['name' => 'update']);
```

A permission can be assigned to a role using `assignRole()` and `assignPermission()` methods:
```php
$role->assignPermission($permission);
$permission->assignRole($role);
```

Multiple permissions can be synced to a role using `syncPermissions()` method:
```php
$role->syncPermissions(array $permissions_ids);
$permission->syncRoles(array $roles_ids);
```

A permission can be removed from a role using 1 of these methods:
```php
$role->revokePermission($permission);
$permission->revokeRole($role);
```


## License

This package is released under the [MIT License](LICENSE).
