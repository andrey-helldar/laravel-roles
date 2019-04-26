<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Models\Permission;
use Helldar\Roles\Models\Role;
use Illuminate\Support\Str;

trait Commands
{
    private $name;

    private function name(): string
    {
        if (\is_null($this->name)) {
            $name = $this->argument('name');

            $this->name = Str::slug($name, '_');
        }

        return $this->name;
    }

    private function roleIsExists(): bool
    {
        return (bool) Role::query()
            ->whereId($this->name())
            ->orWhereName($this->name())
            ->exists();
    }

    private function roleIsDoesntExists(): bool
    {
        return !$this->roleIsExists();
    }

    private function permissionIsExists(): bool
    {
        return (bool) Permission::query()
            ->whereId($this->name())
            ->orWhereName($this->name())
            ->exists();
    }

    private function permissionIsDoesntExists(): bool
    {
        return !$this->permissionIsExists();
    }
}
