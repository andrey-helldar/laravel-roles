<?php

namespace Helldar\Roles\Console;

use Helldar\Roles\Traits\Commands;
use Illuminate\Console\Command;

class RoleDelete extends Command
{
    use Commands;

    protected $signature = 'acl:role-delete {name|ID or role name}';

    protected $description = 'Deleting a role';

    /**
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     */
    public function handle()
    {
        if ($this->roleIsDoesntExists()) {
            $this->error(\sprintf('Role "%s" doesn\'t exists!', $this->name()));

            return;
        }

        $this->remove();
    }

    /**
     * @throws \Helldar\Roles\Exceptions\UnknownModelKeyException
     * @throws \Exception
     */
    private function remove()
    {
        /** @var \Helldar\Roles\Models\Role $role */
        $model = $this->model('role');

        $model::query()
            ->whereId($this->name())
            ->orWhereName($this->name())
            ->delete();

        $this->info(\sprintf('Role "%s" successfully deleted!', $this->name()));
    }
}
