<?php

namespace Helldar\Roles\Console;

use Helldar\Roles\Models\Permission;
use Helldar\Roles\Traits\Commands;
use Illuminate\Console\Command;

class PermissionCreate extends Command
{
    use Commands;

    protected $signature = 'acl:permission-create {name}';

    protected $description = 'Create a new permission';

    public function handle()
    {
        if ($this->permissionIsExist()) {
            $this->error(sprintf('Permission "%s" already exists!', $this->name()));

            return;
        }

        $this->create();
    }

    protected function create()
    {
        $name = $this->name();
        $item = Permission::create(compact('name'));

        $this->info(sprintf('Permission "%s" created successfully!', $name));
        $this->line($item);
    }
}
