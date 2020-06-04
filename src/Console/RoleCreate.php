<?php

namespace Helldar\Roles\Console;

use Helldar\Roles\Models\Role;
use Helldar\Roles\Traits\Commands;
use Illuminate\Console\Command;

class RoleCreate extends Command
{
    use Commands;

    protected $signature = 'acl:role-create {name}';

    protected $description = 'Create a new role';

    public function handle()
    {
        if ($this->roleIsExist()) {
            $this->error(sprintf('Role "%s" already exists!', $this->name()));

            return;
        }

        $this->create();
    }

    protected function create()
    {
        $name = $this->name();

        $item = Role::create(compact('name'));

        $this->info(sprintf('Role "%s" created successfully!', $name));
        $this->line($item);
    }
}
