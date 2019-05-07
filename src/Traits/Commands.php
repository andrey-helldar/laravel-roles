<?php

namespace Helldar\Roles\Traits;

use Illuminate\Support\Str;

trait Commands
{
    use Models;

    private $slug;

    private function name(): string
    {
        if (\is_null($this->slug)) {
            $name = $this->argument('name');

            $this->slug = Str::slug($name, '_');
        }

        return $this->slug;
    }

    /**
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     * @return bool
     */
    private function roleIsExists(): bool
    {
        $model = $this->model('role');

        return (bool) $model::query()
            ->whereId($this->name())
            ->orWhereName($this->name())
            ->exists();
    }

    /**
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     * @return bool
     */
    private function roleIsDoesntExists(): bool
    {
        return !$this->roleIsExists();
    }

    /**
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     * @return bool
     */
    private function permissionIsExists(): bool
    {
        $model = $this->model('permission');

        return (bool) $model::query()
            ->whereId($this->name())
            ->orWhereName($this->name())
            ->exists();
    }

    /**
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     * @return bool
     */
    private function permissionIsDoesntExists(): bool
    {
        return !$this->permissionIsExists();
    }
}
