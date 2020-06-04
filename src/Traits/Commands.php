<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Models\Permission;
use Helldar\Roles\Models\Role;
use Illuminate\Support\Str;

trait Commands
{
    use Searchable;

    protected $slug;

    protected function name(): string
    {
        if (is_null($this->slug)) {
            $name = $this->argument('name');

            $this->slug = Str::slug($name, '_');
        }

        return $this->slug;
    }

    protected function roleIsExist(): bool
    {
        return $this->searchExist(Role::class, $this->name());
    }

    protected function roleIsDoesntExist(): bool
    {
        return ! $this->roleIsExist();
    }

    protected function permissionIsExist(): bool
    {
        return $this->searchExist(Permission::class, $this->name());
    }

    protected function permissionIsDoesntExist(): bool
    {
        return ! $this->permissionIsExist();
    }
}
