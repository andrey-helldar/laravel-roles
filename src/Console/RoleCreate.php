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
        if ($this->roleIsExists()) {
            $this->error(\sprintf('Role "%s" already exists!', $this->name()));

            return;
        }

        $this->create();
    }

    private function create()
    {
        $item = Role::create(['name' => $this->name()]);

        $this->info(\sprintf('Role "%s" created successfully!', $this->name()));
        $this->line($item);
    }
}
