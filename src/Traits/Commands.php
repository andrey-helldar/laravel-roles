<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Models\Permission;
use Helldar\Roles\Models\Role;
use Illuminate\Support\Str;

trait Commands
{
    use Searchable;

    protected $slug;

    protected function slug(): string
    {
        if (is_null($this->slug)) {
            $slug = $this->argument('slug');

            $this->slug = Str::slug($slug, '_');
        }

        return $this->slug;
    }

    protected function roleIsExist(): bool
    {
        return $this->searchExist(Role::class, $this->slug());
    }

    protected function roleIsDoesntExist(): bool
    {
        return ! $this->roleIsExist();
    }

    protected function permissionIsExist(): bool
    {
        return $this->searchExist(Permission::class, $this->slug());
    }

    protected function permissionIsDoesntExist(): bool
    {
        return ! $this->permissionIsExist();
    }
}
