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
     * @return bool
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     *
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
     * @return bool
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     *
     */
    private function roleIsDoesntExists(): bool
    {
        return !$this->roleIsExists();
    }

    /**
     * @return bool
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     *
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
     * @return bool
     *
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     *
     */
    private function permissionIsDoesntExists(): bool
    {
        return !$this->permissionIsExists();
    }
}
