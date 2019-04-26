<?php

namespace Helldar\Roles\Console;

use Helldar\Roles\Models\Role;
use Helldar\Roles\Traits\Commands;
use Illuminate\Console\Command;

class RoleDelete extends Command
{
    use Commands;

    protected $signature = 'acl:role-delete {name|ID or role name}';

    protected $description = 'Deleting a role';

    /**
     * @throws \Exception
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
     * @throws \Exception
     */
    private function remove()
    {
        Role::query()
            ->whereId($this->name())
            ->orWhereName($this->name())
            ->delete();

        $this->info(\sprintf('Role "%s" successfully deleted!', $this->name()));
    }
}
