<?php

namespace Helldar\Roles\Console;

use Helldar\Roles\Models\Role;
use Helldar\Roles\Traits\Commands;
use Illuminate\Console\Command;

class RoleCreate extends Command
{
    use Commands;

    protected $signature = 'acl:role-create {slug}';

    protected $description = 'Create a new role';

    public function handle()
    {
        if ($this->roleIsExist()) {
            $this->error(sprintf('Role "%s" already exists!', $this->slug()));

            return;
        }

        $this->create();
    }

    protected function create()
    {
        $slug = $this->slug();

        $item = Role::create(compact('slug'));

        $this->info(sprintf('Role "%s" created successfully!', $slug));
        $this->line($item);
    }
}
