<?php

namespace Helldar\Roles\Traits;

use Helldar\Roles\Exceptions\UnknownModelKeyException;
use Illuminate\Support\Str;

use function is_null;

trait Commands
{
    use Find;

    private $slug;

    protected function name(): string
    {
        if (is_null($this->slug)) {
            $name = $this->argument('name');

            $this->slug = Str::slug($name, '_');
        }

        return $this->slug;
    }

    /**
     * @throws UnknownModelKeyException
     *
     * @return bool
     */
    protected function roleIsExists(): bool
    {
        $model = $this->model('role');

        return (bool) $model::query()
            ->whereId($this->name())
            ->orWhereName($this->name())
            ->exists();
    }

    /**
     * @throws UnknownModelKeyException
     *
     * @return bool
     */
    protected function roleIsDoesntExists(): bool
    {
        return !$this->roleIsExists();
    }

    /**
     * @throws UnknownModelKeyException
     *
     * @return bool
     */
    protected function permissionIsExists(): bool
    {
        $model = $this->model('permission');

        return (bool) $model::query()
            ->whereId($this->name())
            ->orWhereName($this->name())
            ->exists();
    }

    /**
     * @throws UnknownModelKeyException
     *
     * @return bool
     */
    protected function permissionIsDoesntExists(): bool
    {
        return !$this->permissionIsExists();
    }
}
