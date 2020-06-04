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

    public function handle()
    {
        if ($this->roleIsDoesntExist()) {
            $this->error(sprintf('Role "%s" doesn\'t exists!', $this->name()));

            return;
        }

        $this->remove();
    }

    protected function remove()
    {
        $name = $this->name();

        $this->searchBuilder(Role::class, $name)->delete();

        $this->info(sprintf('Role "%s" successfully deleted!', $name));
    }
}
